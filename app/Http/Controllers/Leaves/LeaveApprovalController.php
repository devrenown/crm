<?php

namespace App\Http\Controllers\Leaves;

use App\Http\Controllers\Controller;
use App\Models\LeaveRequest;
use App\Models\LeaveBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;

class LeaveApprovalController extends Controller
{
    public function edit(LeaveRequest $leave)
    {
        logger()->info('LEAVE APPROVE EDIT HIT', [
            'leave_id' => $leave->id,
            'user_id' => auth()->id(),
            'approval_stage' => $leave->approval_stage,
            'leave_type_id' => $leave->leave_type_id,
            'leave_type_exists' => (bool) $leave->leaveType,
        ]);

        $this->authorize('approve', $leave);

        if (!$leave->leaveType) {
            logger()->error('LEAVE TYPE MISSING ON EDIT', [
                'leave_id' => $leave->id,
                'leave_type_id' => $leave->leave_type_id,
            ]);

            return back()->with([
                'message' => 'Leave type configuration missing.',
                'alert-type' => 'danger',
            ]);
        }

        return view('pages.leaves.approve', [
            'leave' => $leave
        ]);
    }

  public function update(Request $request, LeaveRequest $leave)
{
    $user = auth()->user();
    $this->authorize('approve', $leave);
    $leave->refresh();

    if (!$leave->leaveType) {
        return back()->with([
            'message' => 'Leave type configuration missing.',
            'alert-type' => 'danger',
        ]);
    }

    if ($leave->approval_stage === 'FINAL') {
        return redirect()->route('leaves.show', $leave->id)
            ->with([
                'message' => 'This leave request is already finalized.',
                'alert-type' => 'warning',
            ]);
    }

    $validator = Validator::make($request->all(), [
        'status' => ['required', Rule::in(['Approved', 'Rejected'])],
        'manual_adjustment' => ['nullable', 'numeric', 'min:0'],
        
        'approved_level_1_remark' => ['nullable', 'string', 'max:1000'],
        'approved_level_2_remark' => ['nullable', 'string', 'max:1000'],
    ]);

    if ($validator->fails()) {
        return back()->withInput()->with([
            'message' => $validator->errors()->first(),
            'alert-type' => 'danger',
        ]);
    }

    $stage = $leave->approval_stage;
    $type = $leave->leaveType;
    $requiresL2 = (int) $type->requires_l2_approval === 1;
    //$isFinal = ($stage === 'L2') || ($stage === 'L1' && !$requiresL2);
    $isAdmin = activeRoleCan('approve-all-leave');
    $isFinal = $isAdmin || ($stage === 'L2') || ($stage === 'L1' && !$requiresL2);

    /* ---------------- BALANCE CHECK ---------------- */
    if ($request->status === 'Approved' && $isFinal && !$leave->is_balance_applied) {
        $balance = $this->getBalance($leave);
        $available = $balance->remaining_leaves + $balance->manual_adjustment;
        $shortfall = max(0, $leave->days - $available);

        if ($shortfall > 0 && (!$request->filled('manual_adjustment') || $request->manual_adjustment < $shortfall)) {
            return back()->withInput()->with([
                'message' => "Insufficient leave balance ({$available} available). Manual adjustment required.",
                'alert-type' => 'warning',
                'allow_manual_adjust' => true,
                'required_adjustment' => $shortfall,
            ]);
        }
    }

    /* ---------------- PROCESS ---------------- */
    DB::transaction(function () use ($request, $leave, $user, $requiresL2, $stage, $isAdmin) {
        
        if ($request->status === 'Rejected') {

            $updateData = [
                'status' => 'Rejected',
                'approval_stage' => 'FINAL',
                'rejected_by' => $user->id,
                'rejected_on' => now(),
                'approved_days' => 0,
            ];
        
            if ($stage === 'L1') {
                $updateData['rejected_at_level'] = 'L1';
                $updateData['rejection_reason_l1'] = $request->approved_level_1_remark;
            }
        
            if ($stage === 'L2') {
                $updateData['rejected_at_level'] = 'L2';
                $updateData['rejection_reason_l2'] = $request->approved_level_2_remark;
            }
        
            $leave->update($updateData);
        
            return;
        }
    
        $isFinalNow = false;
    
        /* =========================
         * ADMIN FINAL APPROVAL
         * ========================= */
        if ($isAdmin) {
    
            $updateData = [
                'approved_days' => $leave->days,
                'approval_stage' => 'FINAL',
                'status' => 'Approved',
            ];
    
            if (!$leave->approved_level_1_id) {
                $updateData['approved_level_1_id'] = $user->id;
                $updateData['approved_level_1_on'] = now();
                $updateData['approved_level_1_remark'] = $request->approved_level_1_remark;
            }
    
            if (!$leave->approved_level_2_id) {
                $updateData['approved_level_2_id'] = $user->id;
                $updateData['approved_level_2_on'] = now();
                $updateData['approved_level_2_remark'] =
                $request->approved_level_2_remark
                ?? $request->approved_level_1_remark;
            }
    
            $leave->update($updateData);
            $isFinalNow = true;
        }
    
        /* =========================
         * NORMAL FLOW
         * ========================= */
        else {
    
            // L1
            if ($stage === 'L1') {
                $leave->update([
                    'approved_level_1_id' => $user->id,
                    'approved_level_1_on' => now(),
                     'approved_level_1_remark' => $request->approved_level_1_remark,
                    'approved_days' => $leave->days,
                    'approval_stage' => $requiresL2 ? 'L2' : 'FINAL',
                    'status' => $requiresL2 ? 'Pending' : 'Approved',
                ]);
    
                if (!$requiresL2) {
                    $isFinalNow = true;
                }
            }
    
            // L2
            elseif ($stage === 'L2') {
                $leave->update([
                    'approved_level_2_id' => $user->id,
                    'approved_level_2_on' => now(),
                    'approved_level_2_remark' => $request->approved_level_2_remark,
                    'approved_days' => $leave->days,
                    'approval_stage' => 'FINAL',
                    'status' => 'Approved',
                ]);
    
                $isFinalNow = true;
            }
        }
    
        /* =========================
         * BALANCE LOGIC (ONLY FINAL)
         * ========================= */
        if ($isFinalNow) {
    
            if ($request->filled('manual_adjustment')) {
                $balance = $this->getBalance($leave);
                $balance->increment('manual_adjustment', $request->manual_adjustment);
            }
    
            if (!$leave->is_balance_applied) {
                $this->deductBalance($leave);
                $leave->update(['is_balance_applied' => 1]);
            }
        }
    });
    

    // ---------------- Redirect safely ----------------
    $leave->refresh();

    // If moved to L2 → DON'T go back to approve page
    if ($leave->approval_stage === 'L2') {
        return redirect()->route('leaves.index')
            ->with([
                'message' => 'L1 approved successfully. Waiting for L2 approval.',
                'alert-type' => 'success',
            ]);
    }
    
     if ($leave->status === 'Rejected') {
        return redirect()->route('leaves.show', $leave->id)
            ->with([
                'message' => 'Leave rejected successfully.',
                'alert-type' => 'warning',
            ]);
    }

    // If still not final (rare case)
    if ($leave->approval_stage !== 'FINAL') {
        return redirect()->route('leaves.approve.edit', $leave->id)
            ->with([
                'message' => 'Leave approved successfully for this stage.',
                'alert-type' => 'success',
            ]);
    }
    

    // FINAL
    return redirect()->route('leaves.show', $leave->id)
        ->with([
            'message' => 'Leave fully approved successfully.',
            'alert-type' => 'success',
        ]);
}


    /* ------------- HELPERS ------------- */

    private function getBalance(LeaveRequest $leave): LeaveBalance
    {
        return LeaveBalance::firstOrCreate(
            [
                'tenant_id' => $leave->tenant_id,
                'user_id' => $leave->user_id,
                'leave_type_id' => $leave->leave_type_id,
                'year' => (int) now()->year,
            ],
            [
                'used_leaves' => 0,
                'remaining_leaves' => 0,
                'manual_adjustment' => 0,
            ]
        )->refresh();
    }

    private function deductBalance(LeaveRequest $leave): void
    {
        $balance = $this->getBalance($leave);

        $balance->increment('used_leaves', $leave->approved_days);
        $balance->remaining_leaves = max(0, $balance->remaining_leaves - $leave->approved_days);
        $balance->save();
    }
}
