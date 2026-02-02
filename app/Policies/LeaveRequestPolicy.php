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

    /* =========================
     * APPROVAL (L1 / L2)
     * ========================= */
    // public function approve(User $user, LeaveRequest $leave): bool
    // {
    //     if (!$this->view($user, $leave)) return false;
    //     if ($leave->approval_stage === 'FINAL') return false;

    //     // L1
    //     if ($leave->approval_stage === 'L1') {
    //         if ((int) $leave->leaveType->requires_l2_approval === 0) {
    //             return true;
    //         }

    //         return $leave->user->reporting_manager === $user->id;
    //     }

    //     // L2
    //     if ($leave->approval_stage === 'L2') {
    //         return $user->roles()
    //             ->whereIn('roles.id', $leave->leaveType->l2_roles ?? [])
    //             ->where('roles.tenant_id', $leave->tenant_id)
    //             ->exists();
    //     }

    //     return false;
    // }

  public function approve(User $user, LeaveRequest $leave): bool
{
    // ❌ No self approval
    if ($user->id === $leave->user_id) {
        return false;
    }

    // ❌ No approval after final
    if ($leave->approval_stage === 'FINAL') {
        return false;
    }

    $requiresL2 = (int) ($leave->leaveType->requires_l2_approval ?? 0);

    /* =========================
     * L1 APPROVAL
     * ========================= */
    if ($leave->approval_stage === 'L1') {

        // 🔹 If L2 NOT required → anyone who can VIEW can approve (FINAL)
        if ($requiresL2 === 0) {
            return $this->view($user, $leave);
        }

        // 🔹 If L2 required → ONLY reporting manager
        return (int) $leave->user->reporting_manager === (int) $user->id;
    }

    /* =========================
     * L2 APPROVAL
     * ========================= */
    if ($leave->approval_stage === 'L2') {

        $l2Roles = $leave->leaveType->l2_roles ?? [];

        if (empty($l2Roles)) {
            return false;
        }

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

