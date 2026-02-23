<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\{Tenant, LeaveBalance, LeaveType};
use App\Services\TenantService;
use Illuminate\Support\Facades\DB;

class CarryForwardLeaves extends Command
{
    protected $signature = 'leaves:carry-forward';
    protected $description = 'Carry forward unused leaves to next year';

    public function handle()
    {
        Tenant::each(function ($tenant) {

            // Tenant timezone
            $tz  = TenantService::timezone($tenant->id);
            $now = now($tz);

            $currentYear = $now->year;
            $nextYear    = $currentYear + 1;

            $this->info("Tenant {$tenant->id}: {$currentYear} → {$nextYear}");

            app()->instance('tenant', $tenant);

            $leaveTypes = LeaveType::where('carry_forward', 1)
                ->where('is_active', 1)
                ->where('monthly_accrual', 0) // ❗ yearly only
                ->get();

            foreach ($leaveTypes as $type) {

                LeaveBalance::where('tenant_id', $tenant->id)
                    ->where('leave_type_id', $type->id)
                    ->where('year', $currentYear)
                    ->chunkById(100, function ($balances) use (
                        $tenant,
                        $type,
                        $currentYear,
                        $nextYear
                    ) {

                        foreach ($balances as $balance) {

                            DB::transaction(function () use (
                                $tenant,
                                $type,
                                $balance,
                                $currentYear,
                                $nextYear
                            ) {

                                // Already carried forward
                                if ($balance->carry_forwarded_from_year === $currentYear) {
                                    return;
                                }

                                // Skip exited employees
                                if ($balance->user?->employeeDetail?->date_exit) {
                                    return;
                                }

                                $unused = max(0, $balance->remaining_leaves);

                                $carry = $type->max_carry_forward
                                    ? min($unused, $type->max_carry_forward)
                                    : $unused;

                                // Nothing to carry
                                if ($carry <= 0) {
                                    $balance->update([
                                        'carry_forwarded_from_year' => $currentYear,
                                    ]);
                                    return;
                                }

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

                                // Respect yearly cap
                                if ($type->max_days_per_year) {
                                    $available =
                                        $type->max_days_per_year -
                                        ($nextBalance->opening_balance +
                                         $nextBalance->accrued_leaves +
                                         $nextBalance->carry_forwarded);

                                    $carry = max(0, min($carry, $available));
                                }

                                if ($carry > 0) {
                                    $nextBalance->increment('carry_forwarded', $carry);
                                    $nextBalance->increment('remaining_leaves', $carry);
                                }

                                // Lock previous year
                                $balance->update([
                                    'carry_forwarded_from_year' => $currentYear,
                                ]);
                            });
                        }
                    });
            }
        });

        $this->info('Year-end carry forward completed successfully.');
    }
}


