<?php

namespace App\Jobs;

use App\Models\AttendanceTimestamp;
use App\Models\Tenant;
use App\Services\TenantService;
use Carbon\Carbon;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class AutoClockoutUnsignedAttendances implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, SerializesModels;

    public $queue = 'default';
    public $connection = 'database';

    public function handle(): void
    {
        Tenant::chunk(50, function ($tenants) {

            foreach ($tenants as $tenant) {

                $timezone = TenantService::timezone($tenant->id);
                app()->instance('tenant', $tenant);

                $now = Carbon::now($timezone);

                logger()->info('AUTO CLOCKOUT JOB STARTED', [
                    'tenant_id' => $tenant->id,
                    'timezone'  => $timezone,
                    'run_at'    => $now->toDateTimeString(),
                ]);

                // Fetch all open attendance timestamps for this tenant
                $timestamps = AttendanceTimestamp::whereNull('endTime')
                    ->whereHas('attendance', fn($q) => $q->where('tenant_id', $tenant->id))
                    ->with('attendance.user.employeeShifts.shift')
                    ->get();

                foreach ($timestamps as $timestamp) {

                    $attendance = $timestamp->attendance;
                    if (!$attendance || !$attendance->user) continue;

                    $user = $attendance->user;

                    try {
                        // Clock-in datetime in tenant timezone
                        $clockInAt = Carbon::parse($timestamp->startTime, $timezone);

                        // Get latest shift at clock-in time
                        $shiftAssignment = $user->employeeShifts()
                            ->where('created_at', '<=', $clockInAt)
                            ->latest('created_at')
                            ->with('shift')
                            ->first();

                        $shift = $shiftAssignment?->shift;
                        if (!$shift || !$shift->end_time) {
                            logger()->warning('AUTO CLOCKOUT SKIPPED - NO VALID SHIFT', [
                                'tenant_id' => $tenant->id,
                                'user_id' => $user->id,
                                'attendance_id' => $attendance->id,
                                'clock_in_at' => $clockInAt->toDateTimeString(),
                            ]);
                            continue;
                        }

                        // Shift end datetime in tenant timezone
                        $shiftEnd = Carbon::parse($clockInAt->toDateString() . ' ' . $shift->end_time, $timezone);

                        // Handle night shift
                        if ($shiftEnd->lessThanOrEqualTo($clockInAt)) {
                            $shiftEnd->addDay();
                        }

                        // Auto clock-out 5 minutes after shift end
                        $autoClockoutAt = $shiftEnd->copy()->addMinutes(5);

                        // Prevent auto clock-out at midnight
                        if ($autoClockoutAt->format('H:i:s') === '00:00:00') {
                            logger()->warning('AUTO CLOCKOUT BLOCKED AT MIDNIGHT', [
                                'tenant_id' => $tenant->id,
                                'user_id' => $user->id,
                                'attendance_id' => $attendance->id,
                                'shift_id' => $shift->id,
                                'auto_clockout_at' => $autoClockoutAt->toDateTimeString(),
                            ]);
                            continue;
                        }

                        // Shift about to end (optional alert)
                        $alertTime = $shiftEnd->copy()->subMinutes(5);
                        if ($now->between($alertTime, $shiftEnd)) {
                            logger()->info('SHIFT ABOUT TO END', [
                                'tenant_id' => $tenant->id,
                                'user_id' => $user->id,
                                'attendance_id' => $attendance->id,
                                'shift_id' => $shift->id,
                                'shift_end' => $shiftEnd->toDateTimeString(),
                                'current_time' => $now->toDateTimeString(),
                            ]);
                        }

                        // Execute auto clock-out if open
                        if ($timestamp->endTime === null && $now->greaterThanOrEqualTo($autoClockoutAt)) {
                            $timestamp->update([
                                'endTime' => $autoClockoutAt->copy()->setTimezone('UTC'),
                            ]);
                            $attendance->update([
                                'endDate' => $autoClockoutAt->toDateString()
                            ]);

                            logger()->info('AUTO CLOCK-OUT EXECUTED', [
                                'tenant_id' => $tenant->id,
                                'user_id' => $user->id,
                                'attendance_id' => $attendance->id,
                                'shift_id' => $shift->id,
                                'clockout_at' => $autoClockoutAt->toDateTimeString(),
                                'now' => $now->toDateTimeString(),
                            ]);
                        }

                    } catch (\Exception $e) {
                        logger()->error('AUTO CLOCKOUT PARSE ERROR', [
                            'tenant_id' => $tenant->id,
                            'timestamp_id' => $timestamp->id,
                            'error' => $e->getMessage(),
                        ]);
                        continue;
                    }
                }

                logger()->info('AUTO CLOCKOUT JOB FINISHED', [
                    'tenant_id' => $tenant->id,
                    'ended_at' => Carbon::now($timezone)->toDateTimeString(),
                ]);
            }
        });
    }
}
