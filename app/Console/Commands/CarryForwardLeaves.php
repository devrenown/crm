<?php

namespace App\Console\Commands;
use App\Settings\CompanySettings;
use Illuminate\Console\Command;
use App\Models\{Tenant, LeaveBalance, LeaveType};
use App\Services\TenantService;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CarryForwardLeaves extends Command
{
    protected $signature = 'leaves:carry-forward';

    protected $description = 'Carry forward unused leaves to next financial year';

    public function handle()
    {
        Tenant::each(function ($tenant) {

            app()->instance('tenant', $tenant);

            $tz  = TenantService::timezone($tenant->id);
            $now = now($tz);

            /*
            |--------------------------------------------------
            | Run only on April 1st (SAFE - no hour dependency)
            |--------------------------------------------------
            */
            if ($now->month !== 4 || $now->day !== 1) {
                $this->info("Skipping carry forward: not execution date");
                return;
            }

            $currentYear = $now->year;
            $nextYear    = $currentYear + 1;

            $this->info("Tenant {$tenant->id}: {$currentYear} → {$nextYear}");

            $probationDays = (int) (app(CompanySettings::class)->probation_period ?? 90);

            $leaveTypes = LeaveType::where('carry_forward', 1)
                ->where('is_active', 1)
                ->get();

            foreach ($leaveTypes as $type) {

                LeaveBalance::with(['user.employeeDetail'])
                    ->where('tenant_id', $tenant->id)
                    ->where('leave_type_id', $type->id)
                    ->where('year', $currentYear)
                    ->chunkById(100, function ($balances) use (
                        $tenant,
                        $type,
                        $currentYear,
                        $nextYear,
                        $now,
                        $probationDays
                    ) {

                        foreach ($balances as $balance) {

                            DB::transaction(function () use (
                                $tenant,
                                $type,
                                $balance,
                                $currentYear,
                                $nextYear,
                                $now,
                                $probationDays
                            ) {

                                /*
                                |----------------------------
                                | LOCK ROW (prevents double run)
                                |----------------------------
                                */
                                $balance = LeaveBalance::where('id', $balance->id)
                                    ->lockForUpdate()
                                    ->first();

                                $user     = $balance->user;
                                $employee = $user?->employeeDetail;

                                if (
                                    !$user ||
                                    !$employee ||
                                    !$user->is_active ||
                                    !$user->is_onboarding_complete ||
                                    $employee->date_exit ||
                                    !$employee->date_joined
                                ) {
                                    return;
                                }

                                /*
                                |----------------------------
                                | Gender restriction
                                |----------------------------
                                */
                                if (
                                    $type->gender &&
                                    (int) $type->gender !== (int) $user->gender
                                ) {
                                    return;
                                }

                                /*
                                |----------------------------
                                | Probation check (FIXED)
                                |----------------------------
                                */
                                $joinDate = Carbon::parse($employee->date_joined, $now->timezone);
                                $probationEnd = $joinDate->copy()->addDays($probationDays);

                                $isInProbation = $now->lt($probationEnd);

                                if ($isInProbation && !$type->allow_during_probation) {
                                    return;
                                }

                                /*
                                |----------------------------
                                | Prevent duplicate processing
                                |----------------------------
                                */
                                if ($balance->carry_forwarded_from_year === $currentYear) {
                                    return;
                                }

                                /*
                                |----------------------------
                                | SAFE UNUSED CALCULATION (FIXED)
                                |----------------------------
                                */
                                $used =
                                    ($balance->opening_balance +
                                     $balance->accrued_leaves +
                                     $balance->carry_forwarded)
                                    - $balance->used_leaves;

                                $unused = max(0, $used);

                                $carry = $type->max_carry_forward
                                    ? min($unused, $type->max_carry_forward)
                                    : $unused;

                                /*
                                |----------------------------
                                | Mark processed even if zero
                                |----------------------------
                                */
                                if ($carry <= 0) {

                                    $balance->update([
                                        'carry_forwarded_from_year' => $currentYear,
                                    ]);

                                    return;
                                }

                                /*
                                |----------------------------
                                | NEXT YEAR BALANCE
                                |----------------------------
                                */
                                $nextBalance = LeaveBalance::firstOrCreate(
                                    [
                                        'tenant_id'     => $tenant->id,
                                        'user_id'       => $balance->user_id,
                                        'leave_type_id' => $type->id,
                                        'year'          => $nextYear,
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
                                |----------------------------
                                | YEAR CAP SAFETY
                                |----------------------------
                                */
                                if ($type->max_days_per_year) {

                                    $available =
                                        $type->max_days_per_year -
                                        (
                                            $nextBalance->opening_balance +
                                            $nextBalance->accrued_leaves +
                                            $nextBalance->carry_forwarded
                                        );

                                    $carry = max(0, min($carry, $available));
                                }

                                if ($carry <= 0) {
                                    return;
                                }

                                /*
                                |----------------------------
                                | APPLY CARRY FORWARD
                                |----------------------------
                                */
                                $nextBalance->increment('carry_forwarded', $carry);
                                $nextBalance->increment('remaining_leaves', $carry);

                                /*
                                |----------------------------
                                | MARK AS PROCESSED
                                |----------------------------
                                */
                                $balance->update([
                                    'carry_forwarded_from_year' => $currentYear,
                                ]);

                                $this->info(
                                    "User {$balance->user_id} → {$type->name} carry +{$carry}"
                                );
                            });
                        }
                    });
            }
        });

        $this->info('Year-end carry forward completed successfully.');

        return Command::SUCCESS;
    }
}