<?php

namespace App\Policies;

use App\Models\User;
use App\Models\LeaveRequest;

class LeaveRequestPolicy
{
    /* =========================
     * VIEW
     * ========================= */

  public function view(User $user, LeaveRequest $leave): bool
{
    // Super Admin handled by Gate::before
     if (!$leave->leaveType) {
        return false;
    }

    if (
    $leave->approval_stage === 'L2' &&
    $user->roles()
        ->whereIn('roles.id', $leave->leaveType->l2_roles ?? [])
        ->where('roles.tenant_id', $leave->tenant_id)
        ->exists()
) {
    return true;
}

    // ALL
    if (activeRoleCan('view-all-leave')) {
        return true;
    }

    // TEAM + SUB TEAM
    if (
        activeRoleCan('view-team-leave') &&
        in_array($leave->user_id, $this->teamUserIds($user))
    ) {
        return true;
    }

    // DIRECT TEAM
    if (
        activeRoleCan('view-direct-team-leave') &&
        $leave->user->reporting_manager === $user->id
    ) {
        return true;
    }

    // OWN
    if (
        activeRoleCan('view-leave') &&
        $user->id === $leave->user_id
    ) {
        return true;
    }

    return false;
}



    public function edit(User $user, LeaveRequest $leave): bool
    {
        return
            activeRoleCan('edit-leave') &&
            $user->id === $leave->user_id &&
            $leave->status === 'Pending' &&
            $leave->approval_stage === 'L1' &&
            is_null($leave->approved_level_1_id);
    }


    public function approve(User $user, LeaveRequest $leave): bool
    {
        // No self approval
        if ($user->id === $leave->user_id) {
            return false;
        }

        // Already final
        if ($leave->approval_stage === 'FINAL') {
            return false;
        }

        $requiresL2 = (int) ($leave->leaveType->requires_l2_approval ?? 0);

        /* =========================
        * ADMIN (FULL CONTROL)
        * ========================= */
        if (activeRoleCan('approve-all-leave')) {
            return true;
        }

        /* =========================
        * L1 STAGE
        * ========================= */
        if ($leave->approval_stage === 'L1') {

            // L2 NOT REQUIRED
            if ($requiresL2 === 0) {
                return
                    activeRoleCan('approve-leave') // HR, Manager
                    || (
                        activeRoleCan('approve-team-leave') &&
                        (int) $leave->user->reporting_manager === (int) $user->id
                    );
            }

            // L2 REQUIRED → ONLY TL
            return
                activeRoleCan('approve-team-leave') &&
                (int) $leave->user->reporting_manager === (int) $user->id;
        }

        /* =========================
        * L2 STAGE
        * ========================= */
        if ($leave->approval_stage === 'L2') {

            if ($requiresL2 === 0) return false;

            $l2Roles = $leave->leaveType->l2_roles ?? [];

            return $user->roles()
                ->whereIn('roles.id', $l2Roles)
                ->where('roles.tenant_id', $leave->tenant_id)
                ->exists();
        }

        return false;
    }

    /* =========================
     * DELETE
     * ========================= */
    public function delete(User $user, LeaveRequest $leave): bool
    {
        return
            activeRoleCan('delete-leave')
            && $user->id === $leave->user_id
            && $leave->status === 'Pending'
            && $leave->approval_stage === 'L1'
            && is_null($leave->approved_level_1_id);
    }

    /* =========================
     * TEAM HELPER
     * ========================= */
    private function teamUserIds(User $user): array
    {
        return \App\Models\User::where(function ($q) use ($user) {
            $q->where('reporting_manager', $user->id)
              ->orWhere('sub_reporting_manager', $user->id);
        })->pluck('id')->toArray();
    }
}

