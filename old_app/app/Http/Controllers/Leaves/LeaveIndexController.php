<?php

namespace App\Http\Controllers\Leaves;
use App\Http\Controllers\Controller;
use App\DataTables\LeaveRequestDataTable;
use App\Models\{LeaveRequest, LeaveType, LeaveBalance, User, EmployeeDetail};
use Carbon\Carbon;

class LeaveIndexController extends Controller
{
    public function index(LeaveRequestDataTable $dataTable)
    {
        $user = auth()->user();
        $leaveTypes = LeaveType::where('is_active', 1)->get();

        $query = LeaveRequest::where('tenant_id', $user->tenant_id);

        // if ($user->isEmployee()) {
        //     $query->where('user_id', $user->id);
        // } elseif ($user->isManager() || $user->isTL()) {
        //     $teamIds = User::where('reporting_manager', $user->id)
        //         ->orWhere('sub_reporting_manager', $user->id)
        //         ->pluck('id');

        //     $query->whereIn('user_id', $teamIds);
        // }

         $query = LeaveRequest::where('tenant_id', $user->tenant_id)
        ->visibleTo($user); 

        return $dataTable
            ->with(['baseQuery' => $query])
            ->render('pages.leaves.index', [
                'leaveTypes' => $leaveTypes,
                'pendingCount' => (clone $query)->where('status', 'Pending')->count(),
                'approvedCount' => (clone $query)->where('status', 'Approved')->count(),
                'rejectedCount' => (clone $query)->where('status', 'Rejected')->count(),
                'cancelledCount' => (clone $query)->where('status', 'Cancelled')->count(),
                // Show summary only if user can see own leaves
                'leaveSummary' => activeRoleCan('view-leave')
                    ? $this->employeeLeaveSummary($user)
                    : [],
                'user' => $user,
            ]);
    }


    public function employeeLeaveSummary($user)
{
    $year = now()->year;

    $employee = EmployeeDetail::where('user_id', $user->id)->first();
    if (!$employee || !$employee->date_joined) {
        return [];
    }

    $joinDate = Carbon::parse($employee->date_joined);
    $summary  = [];

    $leaveTypes = LeaveType::where('is_active', 1)
        ->where(function ($q) use ($user) {
            $q->where('gender', 0)->orWhere('gender', $user->gender);
        })
        ->get();

    foreach ($leaveTypes as $type) {

        $balance = LeaveBalance::where([
            'user_id'       => $user->id,
            'leave_type_id' => $type->id,
            'year'          => $year,
        ])->first();

        $used       = $balance?->used_leaves ?? 0;
        $remaining  = $balance?->remaining_leaves ?? 0;
        $earnedTill = ($balance?->opening_balance ?? 0)
                    + ($balance?->accrued_leaves ?? 0)
                    + ($balance?->carry_forwarded ?? 0);

        /* ================= UNPAID ================= */
        if (!$type->is_paid) {
            $summary[] = [
                'name'      => $type->name,
                'mode'      => 'Unlimited',
                'earned'    => 'Unlimited',
                'used'      => $used,
                'remaining' => 'Unlimited',
            ];
            continue;
        }

        /* ================= JOINING DATE RESTRICTION ================= */
        if (
            $type->available_after_joining_days &&
            $joinDate->copy()->addDays($type->available_after_joining_days)->isFuture()
        ) {
            $summary[] = [
                'name'      => $type->name,
                'mode'      => 'Not Eligible Yet',
                'earned'    => 0,
                'used'      => 0,
                'remaining' => 0,
                'eligible_on' =>
                    $joinDate->copy()
                        ->addDays($type->available_after_joining_days)
                        ->toDateString(),
            ];
            continue;
        }

        /* ================= MONTHLY ================= */
        if ($type->monthly_accrual) {

            $monthlyRate = $type->accrual_rate
                ?: round(($type->max_days_per_year ?? 0) / 12, 2);

            $summary[] = [
                'name'      => $type->name,
                'mode'      => 'Monthly',
                'earned'    => $monthlyRate,      // UI hint
                'used'      => $used,
                'remaining' => $remaining,        // DB truth
                'earned_till_now' => round($earnedTill, 2),
            ];
            continue;
        }

        /* ================= YEARLY ================= */
        $summary[] = [
            'name'      => $type->name,
            'mode'      => 'Yearly',
            'earned'    => $type->max_days_per_year,
            'used'      => $used,
            'remaining' => $remaining,
            'earned_till_now' => round($earnedTill, 2),
        ];
    }

    return $summary;
}




}
