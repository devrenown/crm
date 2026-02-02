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
        // Process tenants in chunks
        Tenant::chunk(50, function ($tenants) {

            foreach ($tenants as $tenant) {

                $timezone = TenantService::timezone($tenant->id);
                app()->instance('tenant', $tenant);

                $now = Carbon::now($timezone);

                // logger()->info('AUTO CLOCKOUT JOB STARTED', [
                //     'tenant_id' => $tenant->id,
                //     'timezone'  => $timezone,
                //     'run_at'    => $now->toDateTimeString(),
                // ]);

                // Get all open attendance timestamps
                $timestamps = AttendanceTimestamp::whereNull('endTime')
                    ->whereHas('attendance', fn($q) => $q->where('tenant_id', $tenant->id))
                    ->with('attendance.user.employeeShifts.shift')
                    ->get();

                foreach ($timestamps as $timestamp) {
                    $attendance = $timestamp->attendance;
                    if (!$attendance || !$attendance->user) continue;

                    $user = $attendance->user;

                    // Get the latest shift for this user at the attendance creation time
                    // $shiftAssignment = $user->employeeShifts()
                    //     ->where('created_at', '<=', $attendance->created_at)
                    //     ->latest('created_at')
                    //     ->with('shift')
                    //     ->first();

                    // Build clock-in datetime first (MUST come before shift query)
                    $startTime = preg_match('/^\d{2}:\d{2}:\d{2}$/', $timestamp->startTime)
                    ? $timestamp->startTime
                    : Carbon::parse($timestamp->startTime, $timezone)->format('H:i:s');

                    $clockInAt = Carbon::parse(
                    $attendance->startDate . ' ' . $startTime,
                    $timezone
                    );

                    // Pick shift based on actual clock-in moment
                    $shiftAssignment = $user->employeeShifts()
                    ->where('created_at', '<=', $clockInAt)
                    ->latest('created_at')
                    ->with('shift')
                    ->first();

                    $shift = $shiftAssignment?->shift;

                    if (!$shift || !$shift->end_time) {
                    // logger()->warning('AUTO CLOCKOUT SKIPPED - NO VALID SHIFT', [
                    //     'tenant_id' => $tenant->id,
                    //     'user_id' => $user->id,
                    //     'attendance_id' => $attendance->id,
                    //     'clock_in_at' => $clockInAt->toDateTimeString(),
                    // ]);
                    continue;
                    }


                    $shift = $shiftAssignment?->shift;
                    if (!$shift || !$shift->end_time) continue;

                    try {
                        // Parse clock-in time
                        $startTime = preg_match('/^\d{2}:\d{2}:\d{2}$/', $timestamp->startTime)
                            ? $timestamp->startTime
                            : Carbon::parse($timestamp->startTime, $timezone)->format('H:i:s');

                        $clockInAt = Carbon::parse($attendance->startDate . ' ' . $startTime, $timezone);

                        // Parse shift end time
                        $shiftEndTime = preg_match('/^\d{2}:\d{2}:\d{2}$/', $shift->end_time)
                            ? $shift->end_time
                            : Carbon::parse($shift->end_time, $timezone)->format('H:i:s');

                        $shiftEnd = Carbon::parse($clockInAt->toDateString() . ' ' . $shiftEndTime, $timezone);

                        // Handle night shifts
                        if ($shiftEnd->lessThanOrEqualTo($clockInAt)) {
                            $shiftEnd->addDay();
                        }

                        // Auto clock-out 5 minutes after shift end
                        $autoClockoutAt = $shiftEnd->copy()->addMinutes(5);

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

                        // Debug log
                        // logger()->info('AUTO CLOCKOUT DEBUG', [
                        //     'tenant_id' => $tenant->id,
                        //     'user_id' => $user->id,
                        //     'attendance_id' => $attendance->id,
                        //     'shift_id' => $shift->id,
                        //     'clock_in_at' => $clockInAt->toDateTimeString(),
                        //     'shift_end' => $shiftEnd->toDateTimeString(),
                        //     'auto_clockout_at' => $autoClockoutAt->toDateTimeString(),
                        //     'now' => $now->toDateTimeString(),
                        // ]);

                        // Mark shifts about to end (optional alert)
                        $aboutToEndMinutes = 5;
                        $alertTime = $shiftEnd->copy()->subMinutes($aboutToEndMinutes);
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

                        // Auto clock-out execution
                        if ($timestamp->endTime === null && $now->greaterThanOrEqualTo($autoClockoutAt)) {
                            $timestamp->update(['endTime' => $autoClockoutAt->format('H:i:s')]);
                            $attendance->update(['endDate' => $autoClockoutAt->toDateString()]);

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

                // logger()->info('AUTO CLOCKOUT JOB FINISHED', [
                //     'tenant_id' => $tenant->id,
                //     'ended_at' => Carbon::now($timezone)->toDateTimeString(),
                // ]);
            }

        });
    }
}
