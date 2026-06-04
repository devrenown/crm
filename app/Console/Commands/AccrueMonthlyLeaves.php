<?php

namespace App\Console\Commands;
use App\Settings\CompanySettings;
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

                app()->instance('tenant', $tenant);

                $tz  = TenantService::timezone($tenant->id);
                $now = now($tz);

                $year         = $now->year;
                $currentMonth = $now->format('Y-m');

                $this->info("Tenant {$tenant->id} → Accruing {$currentMonth}");
                
                $probationDays = (int) (app(CompanySettings::class)->probation_period ?? 90);

                EmployeeDetail::whereNull('date_exit')
                    ->whereNotNull('date_joined')
                    ->whereHas('user', function ($q) {

                        $q->where('is_active', 1)
                          ->where('is_onboarding_complete', 1);
                    })
                    ->with('user')
                    ->chunk(100, function ($employees) use (
                        $tenant,
                        $year,
                        $currentMonth,
                        $now,
                        $probationDays
                    ) {

                        foreach ($employees as $employee) {

                            $user = $employee->user;

                            if (!$user) {
                                continue;
                            }

                            $joinDate = Carbon::parse(
                                $employee->date_joined,
                                $now->timezone
                            );

                            $leaveTypes = LeaveType::where('monthly_accrual', 1)
                                ->where('is_active', 1)
                                ->where('is_paid', 1)
                                ->where(function ($q) use ($user) {

                                    $q->where('gender', 0)
                                      ->orWhere('gender', (int) $user->gender);
                                })
                                ->get();

                            foreach ($leaveTypes as $type) {

                                /*
                                |--------------------------------------------------
                                | PROBATION LOGIC (CLEAN + SINGLE SOURCE OF TRUTH)
                                |--------------------------------------------------
                                */
                                $probationEndDate = $joinDate->copy()->addDays($probationDays);
                                $isInProbation = $now->lt($probationEndDate);

                                if ($isInProbation && !$type->allow_during_probation) {
                                    continue;
                                }

                                /*
                                |--------------------------------------------------
                                | FIRST MONTH RULE (INDEPENDENT OF PROBATION)
                                |--------------------------------------------------
                                */
                                if ($joinDate->day > 20) {

                                    $firstEligibleMonth = $joinDate
                                        ->copy()
                                        ->addMonth()
                                        ->format('Y-m');

                                    if ($currentMonth === $firstEligibleMonth) {
                                        continue;
                                    }
                                }

                                /*
                                |--------------------------------------------------
                                | MONTHLY ACCRUAL RATE
                                |--------------------------------------------------
                                */
                                $monthlyRate = $type->accrual_rate
                                    ?: round(($type->max_days_per_year ?? 0) / 12, 2);

                                if ($monthlyRate <= 0) {
                                    continue;
                                }

                                /*
                                |--------------------------------------------------
                                | LEAVE BALANCE
                                |--------------------------------------------------
                                */
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

                                /*
                                |--------------------------------------------------
                                | PREVENT DOUBLE ACCRUAL
                                |--------------------------------------------------
                                */
                                if ($balance->last_accrued_month === $currentMonth) {
                                    continue;
                                }

                                /*
                                |--------------------------------------------------
                                | YEARLY CAP CONTROL
                                |--------------------------------------------------
                                */
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

                                if ($monthlyRate <= 0) {
                                    continue;
                                }

                                /*
                                |--------------------------------------------------
                                | APPLY ACCRUAL
                                |--------------------------------------------------
                                */
                                $balance->update([
                                    'accrued_leaves' =>
                                        $balance->accrued_leaves + $monthlyRate,

                                    'remaining_leaves' =>
                                        $balance->remaining_leaves + $monthlyRate,

                                    'last_accrued_month' => $currentMonth,
                                ]);

                                $this->info(
                                    "User {$employee->user_id} → {$type->name} +{$monthlyRate}"
                                );
                            }
                        }
                    });
            }
        });

        $this->info('Monthly leave accrual completed.');

        return Command::SUCCESS;
    }
}