<?php

namespace App\Http\Controllers\Leaves;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{LeaveRequest, LeaveBalance};
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;

class LeaveApprovalController extends Controller
{
    public function update(Request $request, LeaveRequest $leave)
    {
        $user = auth()->user();
        $isEmployee = $user->isEmployee();
        $type = $leave->leaveType;
        

        $leave->refresh();
        if ($leave->approval_stage === 'FINAL') {
            return back()->with([
                'message' => 'This leave request is already finalized and cannot be modified.',
                'alert-type' => 'warning',
            ]);
        }

        if ($isEmployee) {
            $this->authorize('edit', $leave);
        } else {
            $this->authorize('approve', $leave);
        }
        
        $requiresL2 = (int) $type->requires_l2_approval === 1;
        
        $stage = $leave->approval_stage; // L1 | L2 | FINAL
        $willBeFinalApproval = ($stage === 'L2') || ($stage === 'L1' && !$requiresL2);

        /* =========================
         * VALIDATION WITH TOAST SUPPORT
         * ========================= */
        $validator = Validator::make($request->all(), [
            'status' => [
                Rule::requiredIf(fn () => !$isEmployee),
                Rule::in(['Approved', 'Rejected', 'Cancelled']),
            ],
            'approved_level_1_remark' => [
                Rule::requiredIf(fn () => $stage === 'L1' && $request->status === 'Approved'),
                'nullable', 'max:500',
            ],
            'approved_level_2_remark' => [
                Rule::requiredIf(function () use ($stage, $request, $leave) {
                    if ($stage !== 'L2' || $request->status !== 'Approved') return false;
                    if ($request->filled('manual_adjustment')) return true;

                    $balance = LeaveBalance::where([
                        'tenant_id'     => $leave->tenant_id,
                        'user_id'       => $leave->user_id,
                        'leave_type_id' => $leave->leave_type_id,
                        'year'          => now()->year,
                    ])->first();

                    return $balance && $balance->remaining_leaves >= $leave->days;
                }),
                'nullable', 'max:500',
            ],
            'rejection_reason_l1' => [
                Rule::requiredIf(fn () => $stage === 'L1' && $request->status === 'Rejected'),
                'nullable', 'max:500',
            ],
            'rejection_reason_l2' => [
                Rule::requiredIf(fn () => $stage === 'L2' && $request->status === 'Rejected'),
                'nullable', 'max:500',
            ],
            'manual_adjustment' => ['nullable', 'numeric', 'min:0'],
            'adjustment_reason' => [
                Rule::requiredIf(fn () => $request->manual_adjustment > 0),
                'nullable', 'max:500',
            ],
        ]);

        if ($validator->fails()) {
            return back()
                ->withInput()
                ->with([
                    'message' => $validator->errors()->first(),
                    'alert-type' => 'danger'
                ]);
        }

        /* =========================
         * CHECK FOR MANUAL ADJUSTMENT IF BALANCE INSUFFICIENT
         * ========================= */
        if (
            $request->status === 'Approved'
            && $willBeFinalApproval
            && !$leave->is_balance_applied
            && !($stage === 'L1' && $requiresL2)
        ) {
            $balance = $this->getBalance($leave);
            $shortfall = $leave->days - $balance->remaining_leaves;

            if ($shortfall > 0 && (!$request->filled('manual_adjustment') || $request->manual_adjustment < $shortfall)) {
                return back()->withInput()->with([
                    'message' => "Insufficient leave balance ({$balance->remaining_leaves} remaining). Manual adjustment required.",
                    'alert-type' => 'warning',
                    'allow_manual_adjust' => true,
                    'leave_id' => $leave->id,
                    'required_adjustment' => $shortfall,
                ]);
            }
        }


            //  L1 cannot reject after approving
            if (
                $request->status === 'Rejected' &&
                $leave->approval_stage === 'L1' &&
                $leave->approved_level_1_id
            ) {
                return back()->with([
                    'message' => 'You have already approved this leave at Level. Rejection is not allowed.',
                    'alert-type' => 'warning',
                ]);
            }

            //  L1 cannot reject when L2 approval is required
            if (
                $request->status === 'Rejected' &&
                $leave->approval_stage === 'L1' &&
                $requiresL2
            ) {
                return back()->with([
                    'message' => 'This leave requires Level 2 approval. cannot reject it.',
                    'alert-type' => 'warning',
                ]);
            }


        /* =========================
         * PROCESS LEAVE ACTION
         * ========================= */
        DB::transaction(function () use ($request, $leave, $user, $requiresL2, $stage) {

            // CANCEL
            if ($request->status === 'Cancelled') {
                if ($leave->is_balance_applied) $this->restoreBalance($leave);

                $leave->update([
                    'status' => 'Cancelled',
                    'cancelled_by' => $user->id,
                    'cancelled_on' => now(),
                    'approval_stage' => 'FINAL',
                    'is_balance_applied' => 0,
                ]);
                return;
            }

            // REJECT
           if ($request->status === 'Rejected') {

                // Safety: L1 cannot reject after approving
                if ($stage === 'L1' && $leave->approved_level_1_id) {
                    return;
                }

                if ($requiresL2 && $stage === 'L1') {
                    return;
                }

                if ($leave->is_balance_applied) {
                    $this->restoreBalance($leave);
                }

                $leave->update([
                    'status' => 'Rejected',
                    'rejected_at_level' => $stage,
                    'approval_stage' => 'FINAL',
                    'rejection_reason_' . strtolower($stage) =>
                        $request->input('rejection_reason_' . strtolower($stage)),
                    'is_balance_applied' => 0,
                ]);

                return;
            }

            // L1 APPROVAL
            if ($stage === 'L1') {
                if ($leave->approved_level_1_id) 
                    return back()->with([
                    'message' => 'This leave has already been approved at Level 1.',
                    'alert-type' => 'warning',
                ]);
                if ($requiresL2 && $request->filled('manual_adjustment')) 
                    return back()->with([
                    'message' => 'Level 1 cannot adjust leave balance when Level 2 approval is required.',
                    'alert-type' => 'warning',
                ]);

                $leave->update([
                    'approved_level_1_id' => $user->id,
                    'approved_level_1_on' => now(),
                    'approved_level_1_remark' => $request->approved_level_1_remark,
                    'approved_days' => $leave->days,
                    'approval_stage' => $requiresL2 ? 'L2' : 'FINAL',
                ]);

                if ($requiresL2) return;
            }

            // L2 APPROVAL (FINAL)
            if ($stage === 'L2') {
                if ($leave->approved_level_2_id)
                    
                    return back()->with([
                        'message' => 'This leave has already been approved at Level 2.',
                        'alert-type' => 'warning',
                    ]);

                $leave->update([
                    'approved_level_2_id' => $user->id,
                    'approved_level_2_on' => now(),
                    'approved_level_2_remark' => $request->approved_level_2_remark,
                    'approved_days' => $leave->days,
                    'approval_stage' => 'FINAL',
                ]);
            }

            // DEDUCT BALANCE (FINAL)
            if ($leave->approval_stage === 'FINAL' && !$leave->is_balance_applied) {
                if ($request->filled('manual_adjustment')) {
                    $balance = $this->getBalance($leave);
                    $balance->increment('manual_adjustment', $request->manual_adjustment);
                    $balance->update(['adjustment_reason' => $request->adjustment_reason]);
                }

                $this->deductBalance($leave);

                $leave->update([
                    'status' => 'Approved',
                    'is_balance_applied' => 1,
                ]);
            }
        });

        /* =========================
         * SUCCESS TOAST
         * ========================= */
        return back()->with([
            'message' => match (true) {
                $request->status === 'Cancelled'
                    => 'Leave request has been cancelled successfully.',

                $request->status === 'Rejected'
                    => 'Leave request has been rejected successfully.',

                $requiresL2 && $stage === 'L1'
                    => 'Leave approved at Level 1 and forwarded to Level 2 for final approval.',

                default
                    => 'Leave approved successfully.',
            },
            'alert-type' => 'success',
        ]);
    }

    /* =========================
     * BALANCE HELPERS
     * ========================= */
    private function getBalance(LeaveRequest $leave): LeaveBalance
    {
        return LeaveBalance::where([
            'tenant_id'     => $leave->tenant_id,
            'user_id'       => $leave->user_id,
            'leave_type_id' => $leave->leave_type_id,
            'year'          => now()->year,
        ])->lockForUpdate()->firstOrFail();
    }

    private function deductBalance(LeaveRequest $leave): void
    {
        $balance = $this->getBalance($leave);
        $balance->increment('used_leaves', $leave->approved_days);
        $balance->decrement('remaining_leaves', $leave->approved_days);
    }

    private function restoreBalance(LeaveRequest $leave): void
    {
        if (!$leave->approved_days) return;

        $balance = $this->getBalance($leave);
        $balance->decrement('used_leaves', $leave->approved_days);
        $balance->increment('remaining_leaves', $leave->approved_days);
    }
}


/*
namespace App\Http\Controllers\Leaves;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{LeaveRequest, LeaveBalance};
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class LeaveApprovalController extends Controller
{
    public function update(Request $request, LeaveRequest $leave)
    {
        $user = auth()->user();
        $type = $leave->leaveType;

        // POLICY CHECK
        $this->authorize('update', $leave);

        $requiresL2 = (int) $type->requires_l2_approval === 1;

        $leave->refresh();
        $stage = $leave->approval_stage; // L1 | L2 | FINAL

        $willBeFinalApproval =
            ($stage === 'L2') || ($stage === 'L1' && !$requiresL2);

        
        $request->validate([
            'status' => ['required', Rule::in(['Approved', 'Rejected', 'Cancelled'])],

            'approved_level_1_remark' => [
                Rule::requiredIf(fn () => $stage === 'L1' && $request->status === 'Approved'),
                'nullable', 'max:500',
            ],

            'approved_level_2_remark' => [
                Rule::requiredIf(function () use ($stage, $request, $leave) {

                    if ($stage !== 'L2' || $request->status !== 'Approved') {
                        return false;
                    }

                    if ($request->filled('manual_adjustment')) {
                        return true;
                    }

                    $balance = LeaveBalance::where([
                        'tenant_id'     => $leave->tenant_id,
                        'user_id'       => $leave->user_id,
                        'leave_type_id' => $leave->leave_type_id,
                        'year'          => now()->year,
                    ])->first();

                    return $balance && $balance->remaining_leaves >= $leave->days;
                }),
                'nullable', 'max:500',
            ],

            'rejection_reason_l1' => [
                Rule::requiredIf(fn () => $stage === 'L1' && $request->status === 'Rejected'),
                'nullable', 'max:500',
            ],

            'rejection_reason_l2' => [
                Rule::requiredIf(fn () => $stage === 'L2' && $request->status === 'Rejected'),
                'nullable', 'max:500',
            ],

            'manual_adjustment' => ['nullable', 'numeric', 'min:0'],
            'adjustment_reason' => [
                Rule::requiredIf(fn () => $request->manual_adjustment > 0),
                'nullable', 'max:500',
            ],
        ]);

        
        if (
            $request->status === 'Approved'
            && $willBeFinalApproval
            && !$leave->is_balance_applied
            && !($stage === 'L1' && $requiresL2)
        ) {
            $balance = $this->getBalance($leave);
            $shortfall = $leave->days - $balance->remaining_leaves;

            if ($shortfall > 0 && (!$request->filled('manual_adjustment') || $request->manual_adjustment < $shortfall)) {
                return back()->withInput()->with([
                    'message' => "Insufficient leave balance ({$balance->remaining_leaves} remaining). Manual adjustment required.",
                    'alert-type' => 'warning',
                    'allow_manual_adjust' => true,
                    'leave_id' => $leave->id,
                    'required_adjustment' => $shortfall,
                ]);
            }
        }

        
        DB::transaction(function () use ($request, $leave, $user, $requiresL2, $stage) {

            
            if ($request->status === 'Cancelled') {
                if ($leave->is_balance_applied) $this->restoreBalance($leave);

                $leave->update([
                    'status' => 'Cancelled',
                    'cancelled_by' => $user->id,
                    'cancelled_on' => now(),
                    'approval_stage' => 'FINAL',
                    'is_balance_applied' => 0,
                ]);
                return;
            }

            
            if ($request->status === 'Rejected') {
                if ($requiresL2 && $stage === 'L1') abort(403, 'Level 1 cannot reject when Level 2 approval is required.');
                if ($leave->is_balance_applied) $this->restoreBalance($leave);

                $leave->update([
                    'status' => 'Rejected',
                    'rejected_at_level' => $stage,
                    'approval_stage' => 'FINAL',
                    'rejection_reason_' . strtolower($stage)
                        => $request->input('rejection_reason_' . strtolower($stage)),
                    'is_balance_applied' => 0,
                ]);
                return;
            }

            
            if ($stage === 'L1') {
                if ($leave->approved_level_1_id) abort(409, 'This leave has already been approved at Level 1.');
                if ($requiresL2 && $request->filled('manual_adjustment')) abort(403, 'Level 1 cannot adjust leave balance when Level 2 approval is required.');

                $leave->update([
                    'approved_level_1_id' => $user->id,
                    'approved_level_1_on' => now(),
                    'approved_level_1_remark' => $request->approved_level_1_remark,
                    'approved_days' => $leave->days,
                    'approval_stage' => $requiresL2 ? 'L2' : 'FINAL',
                ]);

                if ($requiresL2) return;
            }

            
            if ($stage === 'L2') {
                if ($leave->approved_level_2_id) abort(409, 'This leave has already been approved at Level 2.');

                $leave->update([
                    'approved_level_2_id' => $user->id,
                    'approved_level_2_on' => now(),
                    'approved_level_2_remark' => $request->approved_level_2_remark,
                    'approved_days' => $leave->days,
                    'approval_stage' => 'FINAL',
                ]);
            }

            
            if ($leave->approval_stage === 'FINAL' && !$leave->is_balance_applied) {
                if ($request->filled('manual_adjustment')) {
                    $balance = $this->getBalance($leave);
                    $balance->increment('manual_adjustment', $request->manual_adjustment);
                    $balance->update(['adjustment_reason' => $request->adjustment_reason]);
                }

                $this->deductBalance($leave);

                $leave->update([
                    'status' => 'Approved',
                    'is_balance_applied' => 1,
                ]);
            }
        });

       
        return back()->with([
            'message' => match (true) {
                $request->status === 'Cancelled'
                    => 'Leave request has been cancelled successfully.',

                $request->status === 'Rejected'
                    => 'Leave request has been rejected successfully.',

                $requiresL2 && $stage === 'L1'
                    => 'Leave approved at Level 1 and forwarded to Level 2 for final approval.',

                default
                    => 'Leave approved successfully.',
            },
            'alert-type' => 'success',
        ]);
    }

    

    private function getBalance(LeaveRequest $leave): LeaveBalance
    {
        return LeaveBalance::where([
            'tenant_id'     => $leave->tenant_id,
            'user_id'       => $leave->user_id,
            'leave_type_id' => $leave->leave_type_id,
            'year'          => now()->year,
        ])->lockForUpdate()->firstOrFail();
    }

    private function deductBalance(LeaveRequest $leave): void
    {
        $balance = $this->getBalance($leave);
        $balance->increment('used_leaves', $leave->approved_days);
        $balance->decrement('remaining_leaves', $leave->approved_days);
    }

    private function restoreBalance(LeaveRequest $leave): void
    {
        if (!$leave->approved_days) return;

        $balance = $this->getBalance($leave);
        $balance->decrement('used_leaves', $leave->approved_days);
        $balance->increment('remaining_leaves', $leave->approved_days);
    }
}


//////////////////////////////////////////////


namespace App\Http\Controllers\Leaves;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\{LeaveRequest, LeaveBalance};
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class LeaveApprovalController extends Controller
{
    public function update(Request $request, LeaveRequest $leave)
    {
        $user = auth()->user();
        $type = $leave->leaveType;

        // POLICY CHECK
        $this->authorize('update', $leave);

        $requiresL2 = (int) $type->requires_l2_approval === 1;

        $leave->refresh();
        $stage = $leave->approval_stage; // L1 | L2 | FINAL

        $willBeFinalApproval =
        ($stage === 'L2')
        || ($stage === 'L1' && !$requiresL2);

        
        $request->validate([
            'status' => ['required', Rule::in(['Approved', 'Rejected', 'Cancelled'])],

            'approved_level_1_remark' => [
                Rule::requiredIf(fn () => $stage === 'L1' && $request->status === 'Approved'),
                'nullable', 'max:500',
            ],

            'approved_level_2_remark' => [
                Rule::requiredIf(function () use ($stage, $request, $leave) {

                    if ($stage !== 'L2' || $request->status !== 'Approved') {
                        return false;
                    }

                    // If manual adjustment is being submitted, this is final approval
                    if ($request->filled('manual_adjustment')) {
                        return true;
                    }

                    // If balance is already sufficient, final approval
                    $balance = LeaveBalance::where([
                        'tenant_id'     => $leave->tenant_id,
                        'user_id'       => $leave->user_id,
                        'leave_type_id' => $leave->leave_type_id,
                        'year'          => now()->year,
                    ])->first();

                    return $balance && $balance->remaining_leaves >= $leave->days;
                }),
                'nullable',
                'max:500',
            ],


            // 'approved_level_2_remark' => [
            //     Rule::requiredIf(fn () => $stage === 'L2' && $request->status === 'Approved'),
            //     'nullable', 'max:500',
            // ],

            'rejection_reason_l1' => [
                Rule::requiredIf(fn () => $stage === 'L1' && $request->status === 'Rejected'),
                'nullable', 'max:500',
            ],

            'rejection_reason_l2' => [
                Rule::requiredIf(fn () => $stage === 'L2' && $request->status === 'Rejected'),
                'nullable', 'max:500',
            ],

            'manual_adjustment' => ['nullable', 'numeric', 'min:0'],
            'adjustment_reason' => [
                Rule::requiredIf(fn () => $request->manual_adjustment > 0),
                'nullable', 'max:500',
            ],
        ]);


        if (
                $request->status === 'Approved'
                && $willBeFinalApproval
                && !$leave->is_balance_applied
                && !($stage === 'L1' && $requiresL2)
            ) {
                $balance = $this->getBalance($leave);
                $shortfall = $leave->days - $balance->remaining_leaves;

                if (
                    $shortfall > 0 &&
                    (
                        !$request->filled('manual_adjustment')
                        || $request->manual_adjustment < $shortfall
                    )
                ) {
                    return back()
                    ->withInput()
                    ->with([
                        'type' => 'warning',
                        'message' => "Insufficient leave balance ({$balance->remaining_leaves} remaining). Manual adjustment required.",
                        'allow_manual_adjust' => true,
                        'leave_id' => $leave->id,
                        'required_adjustment' => $shortfall,
                    ]);
                }
            }

        DB::transaction(function () use ($request, $leave, $user, $requiresL2, $stage) {

           
            if ($request->status === 'Cancelled') {

                if ($leave->is_balance_applied) {
                    $this->restoreBalance($leave);
                }

                $leave->update([
                    'status' => 'Cancelled',
                    'cancelled_by' => $user->id,
                    'cancelled_on' => now(),
                    'approval_stage' => 'FINAL',
                    'is_balance_applied' => 0,
                ]);

                return;
            }

            
            if ($request->status === 'Rejected') {

                //  L1 cannot reject when L2 is required
                if ($requiresL2 && $stage === 'L1') {
                    abort(403, 'Level 1 cannot reject when Level 2 approval is required.');
                }

                if ($leave->is_balance_applied) {
                    $this->restoreBalance($leave);
                }

                $leave->update([
                    'status' => 'Rejected',
                    'rejected_at_level' => $stage,
                    'approval_stage' => 'FINAL',
                    'rejection_reason_' . strtolower($stage)
                        => $request->input('rejection_reason_' . strtolower($stage)),
                    'is_balance_applied' => 0,
                ]);

                return;
            }

            
            if ($stage === 'L1') {

                if ($leave->approved_level_1_id) {
                    abort(409, 'This leave has already been approved at Level 1.');
                }

                //  L1 manual adjustment not allowed if L2 required
                if ($requiresL2 && $request->filled('manual_adjustment')) {
                    abort(403, 'Level 1 cannot adjust leave balance when Level 2 approval is required.');
                }

                $leave->update([
                    'approved_level_1_id' => $user->id,
                    'approved_level_1_on' => now(),
                    'approved_level_1_remark' => $request->approved_level_1_remark,
                    'approved_days' => $leave->days,
                    'approval_stage' => $requiresL2 ? 'L2' : 'FINAL',
                ]);

                if ($requiresL2) {
                    return;
                }
            }

            
            if ($stage === 'L2') {

                if ($leave->approved_level_2_id) {
                    abort(409, 'This leave has already been approved at Level 2.');
                }

                $leave->update([
                    'approved_level_2_id' => $user->id,
                    'approved_level_2_on' => now(),
                    'approved_level_2_remark' => $request->approved_level_2_remark,
                    'approved_days' => $leave->days,
                    'approval_stage' => 'FINAL',
                ]);
            }

            
            if ($leave->approval_stage === 'FINAL' && !$leave->is_balance_applied) {

                if ($request->filled('manual_adjustment')) {
                    $balance = $this->getBalance($leave);
                    $balance->increment('manual_adjustment', $request->manual_adjustment);
                    $balance->update([
                        'adjustment_reason' => $request->adjustment_reason,
                    ]);
                }

                $this->deductBalance($leave);

                $leave->update([
                    'status' => 'Approved',
                    'is_balance_applied' => 1,
                ]);
            }
        });

        
        return back()->with([
            'type' => 'success',
            'message' => match (true) {
                $request->status === 'Cancelled'
                    => 'Leave request has been cancelled successfully.',

                $request->status === 'Rejected'
                    => 'Leave request has been rejected successfully.',

                $requiresL2 && $stage === 'L1'
                    => 'Leave approved at Level 1 and forwarded to Level 2 for final approval.',

                default
                    => 'Leave approved successfully.',
            },
        ]);
    }

    private function getBalance(LeaveRequest $leave): LeaveBalance
    {
        return LeaveBalance::where([
            'tenant_id'     => $leave->tenant_id,
            'user_id'       => $leave->user_id,
            'leave_type_id' => $leave->leave_type_id,
            'year'          => now()->year,
        ])->lockForUpdate()->firstOrFail();
    }

    private function deductBalance(LeaveRequest $leave): void
    {
        $balance = $this->getBalance($leave);
        $balance->increment('used_leaves', $leave->approved_days);
        $balance->decrement('remaining_leaves', $leave->approved_days);
    }

    private function restoreBalance(LeaveRequest $leave): void
    {
        if (!$leave->approved_days) return;

        $balance = $this->getBalance($leave);
        $balance->decrement('used_leaves', $leave->approved_days);
        $balance->increment('remaining_leaves', $leave->approved_days);
    }
}
*/