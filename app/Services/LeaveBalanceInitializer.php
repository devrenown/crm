<?php

namespace App\Services;

use App\Models\{User, LeaveType, LeaveBalance};
use Carbon\Carbon;
use DB;

class LeaveBalanceInitializer
{
    public function initialize(User $user): void
    {
        $year = now()->year;
        $joinDate = Carbon::parse($user->joining_date);

        $activeLeaveTypes = LeaveType::where('tenant_id', $user->tenant_id)
            ->where('is_active', 1)
            ->get();

        DB::transaction(function () use ($user, $year, $joinDate, $activeLeaveTypes) {

            foreach ($activeLeaveTypes as $leaveType) {

                // Prevent duplicates
                $exists = LeaveBalance::where([
                    'tenant_id'     => $user->tenant_id,
                    'user_id'       => $user->id,
                    'leave_type_id' => $leaveType->id,
                    'year'          => $year,
                ])->exists();

                if ($exists) {
                    continue;
                }

                [$opening, $accrued] = $this->calculateLeave(
                    $leaveType,
                    $joinDate,
                    $year
                );

                LeaveBalance::create([
                    'tenant_id'         => $user->tenant_id,
                    'user_id'           => $user->id,
                    'leave_type_id'     => $leaveType->id,
                    'year'              => $year,
                    'opening_balance'   => $opening,
                    'accrued_leaves'    => $accrued,
                    'used_leaves'       => 0,
                    'encashed_leaves'   => 0,
                    'manual_adjustment' => 0,
                    'remaining_leaves'  => $opening + $accrued,
                ]);
            }
        });
    }

    private function calculateLeave(LeaveType $leaveType, Carbon $joinDate, int $year): array
    {
        // FULL YEAR LEAVE (e.g. Casual, Sick)
        if (!$leaveType->monthly_accrual) {
            return [
                $leaveType->max_days_per_year ?? 0,
                0
            ];
        }

        // MONTHLY ACCRUAL LEAVE (e.g. Earned Leave)
        $monthsRemaining = 12 - $joinDate->month + 1;
        $accrued = round($monthsRemaining * $leaveType->accrual_rate, 2);

        return [0, $accrued];
    }
}
