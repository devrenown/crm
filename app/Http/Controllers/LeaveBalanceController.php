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
     * List users who have leave balances
     */
  public function index(Request $request)
{
    $tenantId = auth()->user()->tenant_id;

    $year = $request->year ?? now()->year;

    /*
    |------------------------------------
    | STEP 1: Get unique user-year pairs
    |------------------------------------
    */
    $baseQuery = LeaveBalance::where('tenant_id', $tenantId)
        ->where('year', $year)
        ->select('user_id', 'year')
        ->groupBy('user_id', 'year');

    /*
    | SEARCH (join via user)
    */
    if ($request->search) {
        $search = $request->search;

        $baseQuery->whereHas('user', function ($q) use ($search) {
            $q->where('firstname', 'like', "%$search%")
              ->orWhere('lastname', 'like', "%$search%");
        });
    }

    $rows = $baseQuery->paginate(10)->appends($request->all());

    /*
    |------------------------------------
    | STEP 2: Load full data properly
    |------------------------------------
    */
    $rows->getCollection()->transform(function ($row) use ($tenantId, $year) {

        $user = \App\Models\User::with('employeeDetail')
            ->find($row->user_id);

        $row->user = $user;

        $row->leave_types_count = \App\Models\LeaveBalance::where('user_id', $row->user_id)
            ->where('year', $year)
            ->count();

        return $row;
    });

    return view('pages.leaves.leave-balances.index', [
        'leaveBalances' => $rows,
        'year' => $year
    ]);
}

    /**
     * Show create form
     */
    public function create()
    {
        return view('pages.leaves.leave-balances.create', [

            'employees' => User::where(
                    'tenant_id',
                    auth()->user()->tenant_id
                )
                ->whereHas('roles', function ($q) {
                    $q->where('name', 'Employee');
                })
                ->where('is_active', 1)
                ->orderBy('firstname')
                ->get(),

            'leaveTypes' => LeaveType::where(
                    'tenant_id',
                    auth()->user()->tenant_id
                )
                ->where('is_active', 1)
                ->orderBy('name')
                ->get(),

            'year' => now()->year,
        ]);
    }

    /**
     * Store leave balances
     */
    public function store(Request $request)
    {
        $request->validate([
            'user_id'         => 'required|exists:users,id',
            'year'            => 'required|integer',
            'leave_types'     => 'required|array',
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
            ->with([
                'message' => 'Leave balances created successfully.',
                'alert-type' => 'success',
            ]);
    }

    /**
     * Edit leave balances
     */
    public function edit(Request $request, $userId)
    {
        $year = $request->get('year', now()->year);

        $employee = User::where(
                'tenant_id',
                auth()->user()->tenant_id
            )
            ->findOrFail($userId);

        $balances = LeaveBalance::where(
                'tenant_id',
                auth()->user()->tenant_id
            )
            ->where('user_id', $userId)
            ->where('year', $year)
            ->get()
            ->keyBy('leave_type_id');

        $leaveTypes = LeaveType::where(
                'tenant_id',
                auth()->user()->tenant_id
            )
            ->where('is_active', 1)
            ->orderBy('name')
            ->get();

        return view(
            'pages.leaves.leave-balances.edit',
            compact(
                'employee',
                'balances',
                'leaveTypes',
                'year'
            )
        );
    }

    /**
     * Update leave balances
     */
    public function update(Request $request, $userId)
    {
        $request->validate([
            'leave_types'     => 'required|array',
            'leave_types.*.*' => 'nullable|numeric|min:0',
        ]);

        $tenantId = auth()->user()->tenant_id;

        $year = $request->get('year', now()->year);

        $user = User::where('tenant_id', $tenantId)
            ->findOrFail($userId);

        $leaveTypes = LeaveType::where(
                'tenant_id',
                $tenantId
            )
            ->where('is_active', 1)
            ->get()
            ->keyBy('id');

        DB::transaction(function () use (
            $request,
            $leaveTypes,
            $tenantId,
            $user,
            $userId,
            $year
        ) {

            foreach ($request->leave_types as $leaveTypeId => $data) {

                $leaveType = $leaveTypes->get($leaveTypeId);

                if (!$leaveType) {
                    continue;
                }

                /*
                |--------------------------------------------------------------------------
                | Gender Restriction
                |--------------------------------------------------------------------------
                | 0 = all
                | 1 = male
                | 2 = female
                | 3 = other
                */
                if (
                    $leaveType->gender != 0 &&
                    $leaveType->gender != $user->gender
                ) {
                    continue;
                }

                $balance = LeaveBalance::firstOrCreate(
                    [
                        'tenant_id'     => $tenantId,
                        'user_id'       => $userId,
                        'leave_type_id' => $leaveTypeId,
                        'year'          => $year,
                    ],
                    [
                        'opening_balance'  => 0,
                        'accrued_leaves'   => 0,
                        'carry_forwarded'  => 0,
                        'used_leaves'      => 0,
                        'encashed_leaves'  => 0,
                        'manual_adjustment'=> 0,
                        'remaining_leaves' => 0,
                    ]
                );

                $opening = (float) (
                    $data['opening_balance']
                    ?? $balance->opening_balance
                );

                $accrued = (float) (
                    $data['accrued_leaves']
                    ?? $balance->accrued_leaves
                );

                $carry = (float) (
                    $data['carry_forwarded']
                    ?? $balance->carry_forwarded
                );

                $manual = (float) (
                    $data['manual_adjustment']
                    ?? $balance->manual_adjustment
                );

                $encash = (float) (
                    $data['encashed_leaves']
                    ?? $balance->encashed_leaves
                );

                /*
                |--------------------------------------------------------------------------
                | Carry Forward Rules
                |--------------------------------------------------------------------------
                */
                if (!$leaveType->carry_forward) {
                    $carry = 0;
                }

                if (
                    $leaveType->max_carry_forward &&
                    $carry > $leaveType->max_carry_forward
                ) {
                    $carry = $leaveType->max_carry_forward;
                }

                /*
                |--------------------------------------------------------------------------
                | Monthly Accrual Rules
                |--------------------------------------------------------------------------
                */
                if ($leaveType->monthly_accrual) {

                    $maxAccrual =
                        $leaveType->accrual_rate > 0
                            ? $leaveType->accrual_rate * 12
                            : $leaveType->max_days_per_year;

                    if ($maxAccrual) {
                        $accrued = min($accrued, $maxAccrual);
                    }
                }

                /*
                |--------------------------------------------------------------------------
                | Encashment Rules
                |--------------------------------------------------------------------------
                */
                if (!$leaveType->is_encashable) {
                    $encash = 0;
                }

                /*
                |--------------------------------------------------------------------------
                | Total Leaves
                |--------------------------------------------------------------------------
                */
                $total =
                    $opening +
                    $accrued +
                    $carry +
                    $manual;

                /*
                |--------------------------------------------------------------------------
                | Respect Yearly Limit
                |--------------------------------------------------------------------------
                */
                if ($leaveType->max_days_per_year !== null) {

                    $total = min(
                        $total,
                        $leaveType->max_days_per_year
                    );
                }

                /*
                |--------------------------------------------------------------------------
                | Remaining Leaves
                |--------------------------------------------------------------------------
                */
                $remaining = max(
                    0,
                    $total -
                    $balance->used_leaves -
                    $encash
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
        });

        return redirect()
            ->route('leave-balances.index')
            ->with([
                'message' => 'Leave balances updated successfully.',
                'alert-type' => 'success',
            ]);
    }

    /**
     * Delete leave balances
     */
    public function destroy(Request $request, $userId)
    {
        $year = $request->get('year', now()->year);

        LeaveBalance::where(
                'tenant_id',
                auth()->user()->tenant_id
            )
            ->where('user_id', $userId)
            ->where('year', $year)
            ->delete();

        return back()->with([
            'message' => 'Leave balances deleted successfully.',
            'alert-type' => 'success',
        ]);
    }
}