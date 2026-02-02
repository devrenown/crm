<?php

namespace App\Services;

use App\Models\LeaveBalance;
use Illuminate\Support\Facades\DB;

class CreateLeaveBalance
{
    public static function saveBalances(
        int $tenantId,
        int $userId,
        int $year,
        array $leaveTypes
    ): void {
        DB::transaction(function () use ($tenantId, $userId, $year, $leaveTypes) {

            foreach ($leaveTypes as $leaveTypeId => $data) {

                $opening = (float) ($data['opening_balance'] ?? 0);
                $accrued = (float) ($data['accrued_leaves'] ?? 0);
                $carry   = (float) ($data['carry_forwarded'] ?? 0);
                $used    = (float) ($data['used_leaves'] ?? 0);
                $encash  = (float) ($data['encashed_leaves'] ?? 0);
                $manual  = (float) ($data['manual_adjustment'] ?? 0);

                $remaining = $opening + $accrued + $carry + $manual
                             - $used - $encash;

                LeaveBalance::updateOrCreate(
                    [
                        'tenant_id'     => $tenantId,
                        'user_id'       => $userId,
                        'leave_type_id' => $leaveTypeId,
                        'year'          => $year,
                    ],
                    [
                        'opening_balance'   => $opening,
                        'accrued_leaves'    => $accrued,
                        'carry_forwarded'   => $carry,
                        'used_leaves'       => $used,
                        'encashed_leaves'   => $encash,
                        'manual_adjustment' => $manual,
                        'adjustment_reason' => $data['adjustment_reason'] ?? null,
                        'remaining_leaves'  => $remaining,
                    ]
                );
            }
        });
    }
}
