<?php

namespace App\Console\Commands;

use App\Models\Tenant;
use Illuminate\Console\Command;
use App\Models\{EmployeeDetail, LeaveType, LeaveBalance};
use App\Services\TenantService;
use Carbon\Carbon;

class AccrueMonthlyLeaves extends Command
{
    protected $signature = 'leaves:accrue-monthly';
    protected $description = 'Accrue monthly leaves for employees';

    public function handle()
    {
        Tenant::chunk(50, function ($tenants) {

            foreach ($tenants as $tenant) {

                // Tenant timezone
                $tz  = TenantService::timezone($tenant->id);
                $now = now($tz);

                $year         = $now->year;
                $currentMonth = $now->format('Y-m');

                $this->info("Tenant {$tenant->id} → Accruing {$currentMonth}");

                app()->instance('tenant', $tenant);

                EmployeeDetail::with('user')->chunk(100, function ($employees) use (
                    $tenant,
                    $year,
                    $currentMonth,
                    $now
                ) {

                    foreach ($employees as $employee) {

                        if (
                            !$employee->user ||
                            !$employee->user->is_onboarding_complete ||
                            $employee->date_exit
                        ) {
                            continue;
                        }

                        $joinDate = Carbon::parse($employee->date_joined, $now->timezone);

                        //  Joined after 15th → skip current month
                        if (
                            $joinDate->year === $now->year &&
                            $joinDate->month === $now->month &&
                            $joinDate->day > 15
                        ) {
                            continue;
                        }

                        $leaveTypes = LeaveType::where('monthly_accrual', 1)
                            ->where('is_active', 1)
                            ->where('is_paid', 1)
                            ->where(function ($q) use ($employee) {
                                if ($employee->user->gender === 'male') {
                                    $q->whereIn('gender', [0, 1]);
                                } elseif ($employee->user->gender === 'female') {
                                    $q->whereIn('gender', [0, 2]);
                                } else {
                                    $q->whereIn('gender', [0, 3]);
                                }
                            })
                            ->get();

                        foreach ($leaveTypes as $type) {

                            $monthlyRate = $type->accrual_rate
                                ?: round(($type->max_days_per_year ?? 0) / 12, 2);

                            if ($monthlyRate <= 0) continue;

                            $balance = LeaveBalance::firstOrCreate(
                                [
                                    'tenant_id'     => $tenant->id,
                                    'user_id'       => $employee->user_id,
                                    'leave_type_id' => $type->id,
                                    'year'          => $year,
                                ],
                                [
                                    'opening_balance'  => 0,
                                    'accrued_leaves'   => 0,
                                    'carry_forwarded'  => 0,
                                    'used_leaves'      => 0,
                                    'remaining_leaves' => 0,
                                ]
                            );

                            //  Prevent double accrual
                            if ($balance->last_accrued_month === $currentMonth) {
                                continue;
                            }

                            $totalEarned =
                                $balance->opening_balance +
                                $balance->accrued_leaves +
                                $balance->carry_forwarded;

                            if ($type->max_days_per_year) {
                                $monthlyRate = min(
                                    $monthlyRate,
                                    max(0, $type->max_days_per_year - $totalEarned)
                                );
                            }

                            if ($monthlyRate <= 0) continue;

                            $balance->update([
                                'accrued_leaves'     => $balance->accrued_leaves + $monthlyRate,
                                'remaining_leaves'   => $balance->remaining_leaves + $monthlyRate,
                                'last_accrued_month' => $currentMonth,
                            ]);
                        }
                    }
                });
            }
        });

        $this->info('Monthly leave accrual completed.');
    }
}

