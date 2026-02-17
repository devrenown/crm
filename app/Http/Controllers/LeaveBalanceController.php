<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LeaveType;
use App\Models\LeaveBalance;
use App\Services\CreateLeaveBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaveBalanceController extends Controller
{
    /**
     * List users who have leave balances (grouped by user + year)
     */
    public function index()
    {
        $leaveBalances = LeaveBalance::with('user')
            ->where('tenant_id', auth()->user()->tenant_id)
            ->select(
                'user_id',
                'year',
                DB::raw('COUNT(*) as leave_types_count')
            )
            ->groupBy('user_id', 'year')
            ->orderByDesc('year')
            ->paginate(10);

        return view('pages.leaves.leave-balances.index', compact('leaveBalances'));
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('pages.leaves.leave-balances.create', [
            'employees'  => User::whereHas('roles', fn ($q) => $q->where('name', 'Employee'))
                                ->where('tenant_id', auth()->user()->tenant_id)
                                ->get(),

            'leaveTypes' => LeaveType::where('is_active', 1)->get(),

            'year'       => now()->year,
        ]);
    }

    /**
     * Store leave balances for a user (all leave types)
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id'     => 'required|exists:users,id',
            'year'        => 'required|integer',
            'leave_types' => 'required|array',
            'leave_types.*.*' => 'nullable|numeric',
        ]);

        CreateLeaveBalance::saveBalances(
            auth()->user()->tenant_id,
            $request->user_id,
            $request->year,
            $request->leave_types
        );

        return redirect()
            ->route('leave-balances.index')
            ->with('success', 'Leave balances created successfully.');
    }

    /**
     * Edit leave balances for a user + year
     * URL: /leave-balances/{user}?year=2025
     */
    public function edit(Request $request, $userId)
    {
        $year = $request->get('year', now()->year);

        $employee = User::where('tenant_id', auth()->user()->tenant_id)
            ->findOrFail($userId);

        $balances = LeaveBalance::where('tenant_id', auth()->user()->tenant_id)
            ->where('user_id', $userId)
            ->where('year', $year)
            ->get()
            ->keyBy('leave_type_id');

        $leaveTypes = LeaveType::where('is_active', 1)->get();

        return view('pages.leaves.leave-balances.edit', compact(
            'employee',
            'balances',
            'leaveTypes',
            'year'
        ));
    }

    /**
     * Update leave balances for a user + year
     */
    public function update(Request $request, $userId)
    {
        $request->validate([
            'leave_types' => 'required|array',
            'leave_types.*.*' => 'nullable|numeric',
        ]);

        $year = $request->get('year', now()->year);

        $user = User::where('tenant_id', auth()->user()->tenant_id)
            ->findOrFail($userId);

        $leaveTypes = LeaveType::where('is_active', 1)->get()->keyBy('id');

        foreach ($request->leave_types as $leaveTypeId => $data) {

            $leaveType = $leaveTypes->get($leaveTypeId);
            if (!$leaveType) {
                continue;
            }

            // Gender restriction
            if ($leaveType->gender && $leaveType->gender !== $user->gender) {
                continue;
            }

            $balance = LeaveBalance::firstOrCreate(
                [
                    'tenant_id'     => auth()->user()->tenant_id,
                    'user_id'       => $userId,
                    'leave_type_id' => $leaveTypeId,
                    'year'          => $year,
                ],
                [
                    'opening_balance' => 0,
                    'accrued_leaves'  => 0,
                    'carry_forwarded' => 0,
                    'used_leaves'     => 0,
                    'encashed_leaves' => 0,
                    'manual_adjustment' => 0,
                    'remaining_leaves'  => 0,
                ]
            );

            $opening = $data['opening_balance'] ?? $balance->opening_balance;
            $accrued = $data['accrued_leaves'] ?? $balance->accrued_leaves;
            $carry   = $data['carry_forwarded'] ?? 0;
            $manual  = $data['manual_adjustment'] ?? 0;
            $encash  = $data['encashed_leaves'] ?? 0;

            // Rules
            if (!$leaveType->carry_forward) {
                $carry = 0;
            }

            if ($leaveType->max_carry_forward) {
                $carry = min($carry, $leaveType->max_carry_forward);
            }

            if ($leaveType->monthly_accrual) {
                $accrued = min($accrued, $leaveType->accrual_rate * 12);
            }

            if (!$leaveType->is_encashable) {
                $encash = 0;
            }

            $total = $opening + $accrued + $carry + $manual;

            if ($leaveType->max_days_per_year !== null) {
                $total = min($total, $leaveType->max_days_per_year);
            }

            $remaining = max(
                0,
                $total - $balance->used_leaves - $encash
            );

            $balance->update([
                'opening_balance'   => $opening,
                'accrued_leaves'    => $accrued,
                'carry_forwarded'   => $carry,
                'manual_adjustment' => $manual,
                'encashed_leaves'   => $encash,
                'remaining_leaves'  => $remaining,
            ]);
        }

        return redirect()
            ->route('leave-balances.index')
            ->with('success', 'Leave balances updated successfully.');
    }

    /**
     * Delete leave balances for user + year
     */
    public function destroy(Request $request, $userId)
    {
        $year = $request->get('year', now()->year);

        LeaveBalance::where('tenant_id', auth()->user()->tenant_id)
            ->where('user_id', $userId)
            ->where('year', $year)
            ->delete();

        return back()->with('success', 'Leave balances deleted successfully.');
    }
}


/*
namespace App\Http\Controllers;

use App\Models\User;
use App\Models\LeaveType;
use App\Models\LeaveBalance;
use App\Services\CreateLeaveBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LeaveBalanceController extends Controller
{
   
    public function index()
    {
        $leaveBalances = LeaveBalance::with('user')
            ->select(
                'user_id',
                'year',
                DB::raw('COUNT(*) as leave_types_count')
            )
            ->groupBy('user_id', 'year')
            ->orderByDesc('year')
            ->paginate(20);

        return view('pages.leaves.leave-balances.index', compact('leaveBalances'));
    }

    
    public function create()
    {
        return view('pages.leaves.leave-balances.create', [
            'employees'  => User::whereHas('roles', fn ($q) => $q->where('name', 'Employee'))->get(),
            'leaveTypes' => LeaveType::where('is_active', 1)->get(),
            'year'       => now()->year,
        ]);
    }

   
    public function store(Request $request)
    {
        $request->validate([
            'user_id'     => 'required|exists:users,id',
            'year'        => 'required|integer',
            'leave_types' => 'required|array',
            'leave_types.*.*' => 'nullable|numeric|min:0',
        ]);

        CreateLeaveBalance::saveBalances(
            auth()->user()->tenant_id,
            $request->user_id,
            $request->year,
            $request->leave_types
        );

        return redirect()
            ->route('leave-balances.index')
            ->with('success', 'Leave balances created successfully.');
    }

   
    public function edit(Request $request, $userId)
    {
        $year = $request->get('year', now()->year);

        $employee = User::findOrFail($userId);

        $balances = LeaveBalance::where('user_id', $userId)
            ->where('year', $year)
            ->get()
            ->keyBy('leave_type_id');

        $leaveTypes = LeaveType::where('is_active', 1)->get();

        return view('pages.leaves.leave-balances.edit', compact(
            'employee',
            'balances',
            'leaveTypes',
            'year'
        ));
    }

    
    public function update(Request $request, $userId)
    {
        $request->validate([
            'leave_types' => 'required|array',
            'leave_types.*.*' => 'nullable|numeric|min:0',
        ]);

        $user = User::findOrFail($userId);
        $year = now()->year;

        $leaveTypes = LeaveType::where('is_active', 1)->get()->keyBy('id');

        foreach ($request->leave_types as $leaveTypeId => $data) {

            $leaveType = $leaveTypes->get($leaveTypeId);
            if (!$leaveType) {
                continue;
            }

            // Gender check
            if ($leaveType->gender && $leaveType->gender != $user->gender) {
                continue;
            }

            $balance = LeaveBalance::firstOrCreate(
                [
                    'tenant_id'     => auth()->user()->tenant_id,
                    'user_id'       => $userId,
                    'leave_type_id' => $leaveTypeId,
                    'year'          => $year,
                ],
                [
                    'opening_balance' => 0,
                    'accrued_leaves'  => 0,
                    'carry_forwarded' => 0,
                    'used_leaves'     => 0,
                    'encashed_leaves' => 0,
                ]
            );

            $opening = $data['opening_balance'] ?? $balance->opening_balance;
            $accrued = $data['accrued_leaves'] ?? $balance->accrued_leaves;
            $carry   = $data['carry_forwarded'] ?? $balance->carry_forwarded;
            $manual  = $data['manual_adjustment'] ?? $balance->manual_adjustment;
            $encash  = $data['encashed_leaves'] ?? $balance->encashed_leaves;

            if (!$leaveType->carry_forward) {
                $carry = 0;
            }

            if ($leaveType->max_carry_forward) {
                $carry = min($carry, $leaveType->max_carry_forward);
            }

            if ($leaveType->monthly_accrual) {
                $accrued = min($accrued, $leaveType->accrual_rate * 12);
            }

            if (!$leaveType->is_encashable) {
                $encash = 0;
            }

            $total = $opening + $accrued + $carry + $manual;

            if ($leaveType->max_days_per_year !== null) {
                $total = min($total, $leaveType->max_days_per_year);
            }

            $balance->update([
                'opening_balance'   => $opening,
                'accrued_leaves'    => $accrued,
                'carry_forwarded'   => $carry,
                'manual_adjustment' => $manual,
                'encashed_leaves'   => $encash,
                'remaining_leaves'  => $total - $balance->used_leaves,
            ]);
        }

        return redirect()
            ->route('leave-balances.index')
            ->with('success', 'Leave balances updated successfully.');
    }

   
    public function destroy(Request $request, $userId)
    {
        $year = $request->get('year', now()->year);

        LeaveBalance::where('user_id', $userId)
            ->where('year', $year)
            ->delete();

        return back()->with('success', 'Leave balances deleted successfully.');
    }
}
*/