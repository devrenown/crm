<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\LeaveBalance;
use App\Models\User;
use App\DataTables\LeaveRequestDataTable;
use Illuminate\Support\Facades\DB;

class LeaveController extends Controller
{
    /* =====================================================
     * INDEX
     * ===================================================== */
    public function index(LeaveRequestDataTable $dataTable)
    {
        $user = auth()->user();
        $leaveTypes = LeaveType::where('is_active', 1)->get();

        $query = LeaveRequest::where('tenant_id', $user->tenant_id);

        if ($user->isEmployee()) {
            $query->where('user_id', $user->id);
        } elseif ($user->isManager() || $user->isTL()) {
            $teamIds = User::where('reporting_manager', $user->id)
                ->orWhere('sub_reporting_manager', $user->id)
                ->pluck('id');
            $query->whereIn('user_id', $teamIds);
        }

        return $dataTable
            ->with(['baseQuery' => $query])
            ->render('pages.leaves.index', [
                'leaveTypes' => $leaveTypes,
                'pendingCount' => (clone $query)->where('status', 'Pending')->count(),
                'approvedCount' => (clone $query)->where('status', 'Approved')->count(),
                'rejectedCount' => (clone $query)->where('status', 'Rejected')->count(),
                'cancelledCount' => (clone $query)->where('status', 'Cancelled')->count(),
                'leaveSummary' => $user->isEmployee() ? $this->employeeLeaveSummary($user) : [],
                'user' => $user,
            ]);
    }

    /* =====================================================
     * CREATE
     * ===================================================== */
    public function create()
    {
        return view('pages.leaves.create', [
            'leaveTypes' => LeaveType::where('is_active', 1)->get()
        ]);
    }

    /* =====================================================
     * STORE
     * ===================================================== */
    public function store(Request $request)
    {
        $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'term_details'  => 'required|array|min:1',
            'term_details.*.date' => 'required|date',
            'term_details.*.term' => 'required|in:Fullday,Halfday,Shortleave',
            'term_details.*.half_day_type' => 'nullable|in:First,Second',
            'term_details.*.short_leave_hours' => 'nullable|numeric|min:0.25|max:8',
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'reason' => 'nullable|string|max:500',
        ]);

        $user = auth()->user();
        $type = LeaveType::findOrFail($request->leave_type_id);

        // ---------- Gender Rule ----------
        if ($type->gender != 0 && $type->gender != $user->gender) {
            return back()->withErrors(['Leave not allowed for your gender']);
        }

        // ---------- Notice Period ----------
        if ($type->min_days_notice > 0) {
            $firstDate = collect($request->term_details)->min('date');
            if (now()->diffInDays(Carbon::parse($firstDate), false) < $type->min_days_notice) {
                return back()->withErrors([
                    "Minimum {$type->min_days_notice} days notice required"
                ]);
            }
        }

        // ---------- Document ----------
        if ($type->requires_document && !$request->hasFile('document')) {
            return back()->withErrors(['Document is required']);
        }

        // ---------- Overlap Check ----------
        foreach ($request->term_details as $day) {
            $exists = LeaveRequest::where('user_id', $user->id)
                ->whereIn('status', ['Approved', 'Pending'])
                ->whereJsonContains('term_details', [['date' => $day['date']]])
                ->exists();

            if ($exists) {
                return back()->withErrors([
                    "Leave already exists on {$day['date']}"
                ]);
            }
        }

        DB::transaction(function () use ($request, $user, $type, &$leave) {
            $leave = LeaveRequest::create([
                'tenant_id' => $user->tenant_id,
                'user_id' => $user->id,
                'leave_type_id' => $type->id,
                'start_date' => collect($request->term_details)->min('date'),
                'end_date' => collect($request->term_details)->max('date'),
                'term' => 'Mixed',
                'term_details' => $request->term_details,
                'reason' => $request->reason,
                'status' => 'Pending',
                'approval_stage' => 'L1',
                'is_balance_applied' => 0,
                'ip_address' => $request->ip(),
                'user_agent' => $request->userAgent(),
            ]);

            if ($request->hasFile('document')) {
                $path = $request->file('document')->storeAs(
                    "leaves/{$user->tenant_id}/{$user->id}/leave_{$leave->id}",
                    $request->file('document')->getClientOriginalName(),
                    'public'
                );
                $leave->update(['document_path' => $path]);
            }
        });

        return redirect()->route('leaves.index')
            ->with('success', 'Leave request submitted successfully');
    }

    /* =====================================================
     * EDIT / SHOW
     * ===================================================== */
    public function show(LeaveRequest $leave)
    {
        $this->authorize('view', $leave);
        return view('pages.leaves.show', compact('leave'));
    }

    public function edit(LeaveRequest $leave)
    {
        $this->authorize('update', $leave);
        $leaveTypes = LeaveType::where('is_active', 1)->get();
        return view('pages.leaves.edit', compact('leave', 'leaveTypes'));
    }

    /* =====================================================
     * UPDATE (APPROVE / REJECT / CANCEL)
     * ===================================================== */
    public function update(Request $request, LeaveRequest $leave)
    {
        $this->authorize('update', $leave);

        $user = auth()->user();
        $type = $leave->leaveType;
        $level = $leave->approval_stage;

        // Determine if user can change status
        $canChangeStatus = false;
        if ($level === 'L1' && !$user->isEmployee()) {
            $canChangeStatus = true;
        } elseif ($level === 'L2' && $type->requires_l2_approval) {
            $l2Roles = json_decode($type->l2_roles, true);
            if (!empty($l2Roles) && $user->hasAnyRole($l2Roles)) {
                $canChangeStatus = true;
            }
        }

        // Employees can only edit details if status is not pending and L1/L2 not approved
        if ($user->isEmployee()) {
            if ($leave->status !== 'Pending' || in_array($level, ['L1', 'L2'])) {
                $canChangeStatus = false;
            }
        }

        $request->validate([
            'status' => 'required|in:Approved,Rejected,Cancelled',
            'remarks' => 'nullable|string|max:500',
        ]);

        DB::transaction(function () use ($request, $leave, $user, $type, $level, $canChangeStatus) {

            // REJECT
            if ($request->status === 'Rejected') {
                if ($leave->is_balance_applied) $this->restoreBalance($leave);

                $leave->update([
                    'status' => 'Rejected',
                    'rejected_at_level' => $leave->approval_stage,
                    'approval_stage' => 'REJECTED',
                    'rejection_reason_' . strtolower($level) => $request->input("rejection_reason_" . strtolower($level)),
                    'remarks' => $request->remarks,
                    'is_balance_applied' => 0,
                ]);
                return;
            }

            // CANCEL
            if ($request->status === 'Cancelled') {
                if ($leave->is_balance_applied) $this->restoreBalance($leave);

                $leave->update([
                    'status' => 'Cancelled',
                    'approval_stage' => 'CANCELLED',
                    'cancelled_by' => $user->id,
                    'cancelled_on' => now(),
                    'cancellation_reason_' . strtolower($level) => $request->input("cancellation_reason_" . strtolower($level)),
                    'remarks' => $request->remarks,
                    'is_balance_applied' => 0,
                ]);
                return;
            }

            // APPROVE
            if ($level === 'L1') {
                $leave->update([
                    'approved_level_1_id' => $user->id,
                    'approved_level_1_on' => now(),
                    'approved_level_1_remark' => $request->approved_level_1_remark ?? null,
                    'approval_stage' => $type->requires_l2_approval ? 'L2' : 'FINAL',
                ]);
                if ($type->requires_l2_approval) return;
            }

            if ($level === 'L2') {
                $leave->update([
                    'approved_level_2_id' => $user->id,
                    'approved_level_2_on' => now(),
                    'approved_level_2_remark' => $request->approved_level_2_remark ?? null,
                    'approval_stage' => 'FINAL',
                ]);
            }

            if (!$leave->is_balance_applied) {
                if (!$this->hasSufficientBalance($leave)) {
                    throw new \Exception('Insufficient leave balance');
                }
                $this->deductBalance($leave);
            }

            $leave->update([
                'status' => 'Approved',
                'approved_days' => $leave->days,
                'is_balance_applied' => 1,
            ]);
        });

        return back()->with('success', 'Leave updated successfully');
    }

    

    /* =====================================================
     * DESTROY
     * ===================================================== */
    public function destroy(LeaveRequest $leave)
    {
        if ($leave->document_path) {
            Storage::disk('public')->deleteDirectory(dirname($leave->document_path));
        }
        $leave->delete();
        return back()->with('success', 'Leave deleted');
    }

    /* =====================================================
     * BALANCE HELPERS
     * ===================================================== */
    private function getBalance(LeaveRequest $leave)
    {
        $balance = LeaveBalance::firstOrCreate([
            'tenant_id' => $leave->tenant_id,
            'user_id' => $leave->user_id,
            'leave_type_id' => $leave->leave_type_id,
            'year' => now()->year,
        ], [
            'opening_balance' => $leave->leaveType->max_days_per_year,
            'used_leaves' => 0,
            'remaining_leaves' => $leave->leaveType->max_days_per_year,
        ]);

        // Monthly accrual
        if ($leave->leaveType->monthly_accrual) {
            $accrued = $leave->leaveType->accrual_rate * now()->month;
            $balance->opening_balance = min($balance->opening_balance + $accrued, $leave->leaveType->max_days_per_year);
        }

        // Carry-forward
        if ($leave->leaveType->carry_forward && $leave->leaveType->max_carry_forward > 0) {
            $balance->remaining_leaves = min($balance->remaining_leaves, $leave->leaveType->max_carry_forward);
        }

        return $balance;
    }

    private function hasSufficientBalance(LeaveRequest $leave)
    {
        return $this->getBalance($leave)->remaining_leaves >= $leave->days;
    }

    private function deductBalance(LeaveRequest $leave)
    {
        $balance = $this->getBalance($leave);
        $deduct = min($leave->days, $balance->remaining_leaves);
        $balance->used_leaves += $deduct;
        $balance->remaining_leaves -= $deduct;
        $balance->save();
    }

    private function restoreBalance(LeaveRequest $leave)
    {
        $balance = $this->getBalance($leave);
        $restore = min($leave->days, $balance->used_leaves);
        $balance->used_leaves -= $restore;
        $balance->remaining_leaves += $restore;
        $balance->save();
    }

    private function employeeLeaveSummary($user)
    {
        $summary = [];
        $types = LeaveType::where('is_active', 1)->get();

        foreach ($types as $type) {
            $balance = LeaveBalance::firstOrCreate([
                'tenant_id' => $user->tenant_id,
                'user_id' => $user->id,
                'leave_type_id' => $type->id,
                'year' => now()->year,
            ], [
                'opening_balance' => $type->max_days_per_year,
                'used_leaves' => 0,
                'remaining_leaves' => $type->max_days_per_year,
            ]);

            $summary[] = [
                'name' => $type->name,
                'total' => $balance->opening_balance,
                'used' => $balance->used_leaves,
                'remaining' => $balance->remaining_leaves,
            ];
        }

        return $summary;
    }
}





/*
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Carbon\Carbon;
use App\Models\LeaveRequest;
use App\Models\LeaveType;
use App\Models\LeaveBalance;
use App\Models\User;
use App\DataTables\LeaveRequestDataTable;

class LeaveController extends Controller
{
   
    public function index(LeaveRequestDataTable $dataTable)
    {
        $user = auth()->user();
        $leaveTypes = LeaveType::where('is_active', 1)->get();

        $query = LeaveRequest::where('tenant_id', $user->tenant_id);

        if ($user->isEmployee()) {
            $query->where('user_id', $user->id);
        } elseif ($user->isManager() || $user->isTL()) {
            $teamIds = User::where('reporting_manager', $user->id)
                ->orWhere('sub_reporting_manager', $user->id)
                ->pluck('id');
            $query->whereIn('user_id', $teamIds);
        }

        $pendingCount   = (clone $query)->where('status', 'Pending')->count();
        $approvedCount  = (clone $query)->where('status', 'Approved')->count();
        $rejectedCount  = (clone $query)->where('status', 'Rejected')->count();
        $cancelledCount = (clone $query)->where('status', 'Cancelled')->count();

        $leaveSummary = $user->isEmployee()
            ? $this->employeeLeaveSummary($user)
            : [];

        return $dataTable
            ->with(['baseQuery' => $query])
            ->render('pages.leaves.index', compact(
                'leaveTypes',
                'pendingCount',
                'approvedCount',
                'rejectedCount',
                'cancelledCount',
                'leaveSummary',
                'user'
            ));
    }

    
    public function create()
    {
        $leaveTypes = LeaveType::where('is_active', 1)->get();
        return view('pages.leaves.create', compact('leaveTypes'));
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'leave_type_id' => 'required|exists:leave_types,id',
            'start_date' => 'required|date|before_or_equal:end_date',
            'end_date' => 'required|date',
            'term' => 'required|in:Fullday,Halfday,Shortleave',
            'short_leave_hours' => 'nullable|numeric|min:0|max:8',
            'half_day_type' => 'nullable|in:First,Second',
            'document' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:4096',
            'reason' => 'nullable|string|max:500',
            
        ]);

        $user = auth()->user();
        $type = LeaveType::findOrFail($request->leave_type_id);

       
        if ($type->gender != 0 && $type->gender != $user->gender) {
            return back()->withErrors(['Leave not allowed for your gender']);
        }

        
        if ($type->min_days_notice > 0) {
            $notice = Carbon::now()->diffInDays(Carbon::parse($request->start_date), false);
            if ($notice < $type->min_days_notice) {
                return back()->withErrors([
                    "Minimum {$type->min_days_notice} days notice required"
                ]);
            }
        }

        
        if ($type->requires_document && !$request->hasFile('document')) {
            return back()->withErrors(['Document is required']);
        }

        
        $days = match ($request->term) {
            'Fullday' => Carbon::parse($request->start_date)
                            ->diffInDays(Carbon::parse($request->end_date)) + 1,
            'Halfday' => 0.5,
            'Shortleave' => round($request->short_leave_hours / 8, 2),
        };

      
        $overlap = LeaveRequest::where('user_id', $user->id)
            ->where('status', 'Approved')
            ->where(function ($q) use ($request) {
                $q->whereBetween('start_date', [$request->start_date, $request->end_date])
                  ->orWhereBetween('end_date', [$request->start_date, $request->end_date]);
            })->exists();

        if ($overlap) {
            return back()->withErrors(['Overlapping approved leave exists']);
        }

        
        $leave = LeaveRequest::create([
            'tenant_id' => $user->tenant_id,
            'user_id' => $user->id,
            'leave_type_id' => $type->id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'term' => $request->term,
            'days' => $days,
            'short_leave_hours' => $request->term === 'Shortleave' ? $request->short_leave_hours : null,
            'is_half_day' => $request->term === 'Halfday' ? 1 : 0,
            'half_day_type' => $request->term === 'Halfday' ? $request->half_day_type : null,
            'reason' => $request->reason,
            'status' => 'Pending',
            'approval_stage' => 'L1',
            'is_balance_applied' => 0,
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
        ]);

       
        if ($request->hasFile('document')) {
            $path = $request->file('document')->storeAs(
                "leaves/{$user->tenant_id}/{$user->id}/leave_{$leave->id}",
                $request->file('document')->getClientOriginalName(),
                'public'
            );
            $leave->update(['document_path' => $path]);
        }

        return redirect()->route('leaves.index')
            ->with('success', 'Leave request submitted successfully');
    }

    
    public function edit(LeaveRequest $leave)
    {
        $this->authorize('update', $leave);
        $leaveTypes = LeaveType::where('is_active', 1)->get();
        return view('pages.leaves.edit', compact('leave', 'leaveTypes'));
    }

   
    public function update(Request $request, LeaveRequest $leave)
    {
        $this->authorize('update', $leave);

        $request->validate([
            'status' => 'required|in:Approved,Rejected,Cancelled',
            'remarks' => 'nullable|string|max:500',
        ]);

        $type = $leave->leaveType;

      
        if ($request->status === 'Rejected') {

            // Restore balance if already applied
            if ($leave->is_balance_applied) {
                $this->restoreBalance($leave);
            }

            $leave->update([
                'status' => 'Rejected',
                'rejected_at_level' => $leave->approval_stage,
                'approval_stage' => 'REJECTED',
                'rejection_reason' => $request->rejection_reason,
                'remarks' => $request->remarks,
                'is_balance_applied' => 0,
            ]);

            return back()->with('success', 'Leave rejected successfully');
        }

       
        if ($request->status === 'Cancelled') {

            if ($leave->is_balance_applied) {
                $this->restoreBalance($leave);
            }

            $leave->update([
                'status' => 'Cancelled',
                'approval_stage' => 'CANCELLED',
                'remarks' => $request->remarks,
                'is_balance_applied' => 0,
            ]);

            return back()->with('success', 'Leave cancelled');
        }


        
        $user = auth()->user();

        if ($leave->approval_stage === 'L1') {
            $leave->update([
                'approved_level_1_id' => $user->id,
                'approved_level_1_on' => now(),
            ]);
            if ($type->requires_l2_approval) {
                $leave->update(['approval_stage' => 'L2']);
                return back()->with('success', 'Approved at Level 1');
            }
        }

        if ($leave->approval_stage === 'L2') {
            $leave->update([
                'approved_level_2_id' => $user->id,
                'approved_level_2_on' => now(),
            ]);
        }

       
        if (!$leave->is_balance_applied) {
            if (!$this->hasSufficientBalance($leave)) {
                return back()->withErrors(['Insufficient leave balance']);
            }
            $this->deductBalance($leave);
        }

        $leave->update([
            'status' => 'Approved',
            'approval_stage' => 'FINAL',
            'approved_on' => now(),
            'is_balance_applied' => 1,
            'remarks' => $request->remarks,
        ]);

        return back()->with('success', 'Leave fully approved');
    }

  
    public function show(LeaveRequest $leave)
    {
        $this->authorize('view', $leave);
        return view('pages.leaves.show', compact('leave'));
    }

   
    public function destroy(LeaveRequest $leave)
    {
        if ($leave->document_path) {
            Storage::disk('public')->deleteDirectory(dirname($leave->document_path));
        }
        $leave->delete();
        return back()->with('success', 'Leave deleted');
    }

   
    private function getBalance(LeaveRequest $leave)
    {
        return LeaveBalance::firstOrCreate([
            'tenant_id' => $leave->tenant_id,
            'user_id' => $leave->user_id,
            'leave_type_id' => $leave->leave_type_id,
            'year' => now()->year,
        ], [
            'opening_balance' => $leave->leaveType->max_days_per_year,
            'used_leaves' => 0,
            'remaining_leaves' => $leave->leaveType->max_days_per_year,
        ]);
    }

    private function hasSufficientBalance(LeaveRequest $leave)
    {
        return $this->getBalance($leave)->remaining_leaves >= $leave->days;
    }

    private function deductBalance(LeaveRequest $leave)
    {
        $balance = $this->getBalance($leave);
        $balance->used_leaves += $leave->days;
        $balance->remaining_leaves -= $leave->days;
        $balance->save();
    }

    private function restoreBalance(LeaveRequest $leave)
    {
        $balance = $this->getBalance($leave);
        $balance->used_leaves = max(0, $balance->used_leaves - $leave->days);
        $balance->remaining_leaves += $leave->days;
        $balance->save();
    }

   
    private function employeeLeaveSummary($user)
    {
        $summary = [];
        $types = LeaveType::where('is_active', 1)->get();

        foreach ($types as $type) {
            $balance = LeaveBalance::firstOrCreate([
                'tenant_id' => $user->tenant_id,
                'user_id' => $user->id,
                'leave_type_id' => $type->id,
                'year' => now()->year,
            ], [
                'opening_balance' => $type->max_days_per_year,
                'used_leaves' => 0,
                'remaining_leaves' => $type->max_days_per_year,
            ]);

            $summary[] = [
                'name' => $type->name,
                'total' => $balance->opening_balance,
                'used' => $balance->used_leaves,
                'remaining' => $balance->remaining_leaves,
            ];
        }

        return $summary;
    }
}
*/