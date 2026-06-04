<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use App\Models\LeaveRequest;
use App\Models\LeaveBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LeaveTypeController extends Controller
{
    /**
     * List Leave Types
     */
    public function index()
    {
        $leaveTypes = LeaveType::latest()->get();

       $rolesMap = Role::where('tenant_id', Auth::user()->tenant_id)
        ->pluck('name', 'id')
        ->toArray();

        return view(
            'pages.leaves.leave-type.index',
            compact('leaveTypes', 'rolesMap')
        );
    }

    /**
     * Show Create Page
     */
    public function create()
    {
        $roles = Role::where('tenant_id', Auth::user()->tenant_id)
        ->orderBy('name')
        ->get();

        return view(
            'pages.leaves.leave-type.create',
            compact('roles')
        );
    }

    /**
     * Store Leave Type
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [

            'name'                     => 'required|string|max:255',

            'leave_code'               => 'nullable|string|max:50',

            'description'              => 'nullable|string',

            'max_days_per_year'        => 'nullable|numeric|min:0',

            'max_carry_forward'        => 'nullable|numeric|min:0',

            'min_days_notice'          => 'nullable|integer|min:0',

            'max_days_per_application' => 'nullable|numeric|min:0',

            'accrual_rate'             => 'nullable|numeric|min:0',

            'gender'                   => 'nullable|in:0,1,2,3',

            'requires_l2_approval'     => 'nullable|boolean',

            'l2_roles'                 => 'nullable|array',

            'l2_roles.*'               => 'exists:roles,id',
        ]);

        if ($validator->fails()) {

            return back()
                ->withInput()
                ->with([
                    'message'    => $validator->errors()->first(),
                    'alert-type' => 'danger',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Business Rules Validation
        |--------------------------------------------------------------------------
        */

        if (
            $request->boolean('monthly_accrual') &&
            !$request->filled('accrual_rate')
        ) {

            return back()
                ->withInput()
                ->with([
                    'message' =>
                        'Accrual rate is required for monthly accrual leave type.',
                    'alert-type' => 'danger',
                ]);
        }

        if (
            $request->boolean('carry_forward') &&
            !$request->filled('max_carry_forward')
        ) {

            return back()
                ->withInput()
                ->with([
                    'message' =>
                        'Maximum carry forward is required.',
                    'alert-type' => 'danger',
                ]);
        }

        LeaveType::create([

            'tenant_id'                => Auth::user()->tenant_id,

            'name'                     => $request->name,

            'leave_code'               => $request->leave_code,

            'description'              => $request->description,

            'is_paid'                  => $request->boolean('is_paid'),

            'max_days_per_year'        => $request->max_days_per_year,

            'carry_forward'            => $request->boolean('carry_forward'),

            'max_carry_forward'        => $request->max_carry_forward ?? 0,

            'monthly_accrual'          => $request->boolean('monthly_accrual'),

            'accrual_rate'             => $request->accrual_rate ?? 0,

            'allow_during_probation'   => $request->boolean('allow_during_probation'),

            'requires_document'        => $request->boolean('requires_document'),

            'min_days_notice'          => $request->min_days_notice ?? 0,

            'max_days_per_application' => $request->max_days_per_application,

            'gender'                   => $request->gender ?? 0,

            'is_encashable'            => $request->boolean('is_encashable'),

            'is_active'                => $request->boolean('is_active'),

            'requires_l2_approval'     => $request->boolean('requires_l2_approval'),

            'l2_roles'                 => $request->boolean('requires_l2_approval')
                ? $request->l2_roles
                : null,
        ]);

        return redirect()
            ->route('leave-type.index')
            ->with([
                'message'    => 'Leave type created successfully.',
                'alert-type' => 'success',
            ]);
    }

    /**
     * Show Edit Page
     */
    public function edit(LeaveType $leaveType)
    {
        $roles = Role::where('tenant_id', Auth::user()->tenant_id)
            ->orderBy('name')
            ->get();

        $selectedRoles = collect(
            $leaveType->l2_roles ?? []
        )
        ->map(fn ($id) => (int) $id)
        ->toArray();

        return view(
            'pages.leaves.leave-type.edit',
            compact(
                'leaveType',
                'roles',
                'selectedRoles'
            )
        );
    }

    /**
     * Update Leave Type
     */
    public function update(Request $request, LeaveType $leaveType)
    {
        $validator = Validator::make($request->all(), [

            'name'                     => 'required|string|max:255',

            'leave_code'               => 'nullable|string|max:50',

            'description'              => 'nullable|string',

            'max_days_per_year'        => 'nullable|numeric|min:0',

            'max_carry_forward'        => 'nullable|numeric|min:0',

            'min_days_notice'          => 'nullable|integer|min:0',

            'max_days_per_application' => 'nullable|numeric|min:0',

            'accrual_rate'             => 'nullable|numeric|min:0',

            'gender'                   => 'nullable|in:0,1,2,3',

            'requires_l2_approval'     => 'nullable|boolean',

            'l2_roles'                 => 'nullable|array',

            'l2_roles.*'               => 'exists:roles,id',
        ]);

        if ($validator->fails()) {

            return back()
                ->withInput()
                ->with([
                    'message'    => $validator->errors()->first(),
                    'alert-type' => 'danger',
                ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Business Rules Validation
        |--------------------------------------------------------------------------
        */

        if (
            $request->boolean('monthly_accrual') &&
            !$request->filled('accrual_rate')
        ) {

            return back()
                ->withInput()
                ->with([
                    'message' =>
                        'Accrual rate is required for monthly accrual leave type.',
                    'alert-type' => 'danger',
                ]);
        }

        if (
            $request->boolean('carry_forward') &&
            !$request->filled('max_carry_forward')
        ) {

            return back()
                ->withInput()
                ->with([
                    'message' =>
                        'Maximum carry forward is required.',
                    'alert-type' => 'danger',
                ]);
        }

        DB::transaction(function () use ($request, $leaveType) {

            $leaveType->update([

                'name'                     => $request->name,

                'leave_code'               => $request->leave_code,

                'description'              => $request->description,

                'is_paid'                  => $request->boolean('is_paid'),

                'max_days_per_year'        => $request->max_days_per_year,

                'carry_forward'            => $request->boolean('carry_forward'),

                'max_carry_forward'        => $request->max_carry_forward ?? 0,

                'monthly_accrual'          => $request->boolean('monthly_accrual'),

                'accrual_rate'             => $request->accrual_rate ?? 0,

                'allow_during_probation'   => $request->boolean('allow_during_probation'),

                'requires_document'        => $request->boolean('requires_document'),

                'min_days_notice'          => $request->min_days_notice ?? 0,

                'max_days_per_application' => $request->max_days_per_application,

                'gender'                   => $request->gender ?? 0,

                'is_encashable'            => $request->boolean('is_encashable'),

                'is_active'                => $request->boolean('is_active'),

                'requires_l2_approval'     => $request->boolean('requires_l2_approval'),

                'l2_roles'                 => $request->boolean('requires_l2_approval')
                    ? $request->l2_roles
                    : null,
            ]);

            /*
            |--------------------------------------------------------------------------
            | Update Pending Leave Requests
            |--------------------------------------------------------------------------
            */

            $pendingLeaves = LeaveRequest::where(
                    'leave_type_id',
                    $leaveType->id
                )
                ->where('status', 'Pending')
                ->get();

            foreach ($pendingLeaves as $leave) {

                $leave->approval_stage =
                    $leaveType->requires_l2_approval
                        ? 'L1'
                        : 'FINAL';

                if (
                    $leaveType->max_days_per_application &&
                    $leave->days >
                    $leaveType->max_days_per_application
                ) {

                    $leave->days =
                        $leaveType->max_days_per_application;
                }

                $leave->save();
            }

            /*
            |--------------------------------------------------------------------------
            | Recalculate Leave Balances
            |--------------------------------------------------------------------------
            */

            $balances = LeaveBalance::where(
                    'leave_type_id',
                    $leaveType->id
                )
                ->get();

            foreach ($balances as $balance) {

                /*
                |--------------------------------------------------------------------------
                | Remove Carry Forward If Disabled
                |--------------------------------------------------------------------------
                */

                $carryForwarded = $leaveType->carry_forward
                    ? min(
                        $balance->carry_forwarded,
                        $leaveType->max_carry_forward ?: $balance->carry_forwarded
                    )
                    : 0;

                /*
                |--------------------------------------------------------------------------
                | Limit Accrued Leaves
                |--------------------------------------------------------------------------
                */

                $accruedLeaves = $balance->accrued_leaves;

                if ($leaveType->monthly_accrual) {

                    $maxAccrual =
                        ($leaveType->accrual_rate ?? 0) * 12;

                    $accruedLeaves = min(
                        $accruedLeaves,
                        $maxAccrual
                    );

                } else {

                    $accruedLeaves = 0;
                }

                /*
                |--------------------------------------------------------------------------
                | Remove Encashment If Disabled
                |--------------------------------------------------------------------------
                */

                $encashedLeaves = $leaveType->is_encashable
                    ? $balance->encashed_leaves
                    : 0;

                /*
                |--------------------------------------------------------------------------
                | Recalculate Total
                |--------------------------------------------------------------------------
                */

                $total =
                    $balance->opening_balance +
                    $accruedLeaves +
                    $carryForwarded +
                    $balance->manual_adjustment;

                if (
                    $leaveType->max_days_per_year !== null
                ) {

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
                    $encashedLeaves
                );

                /*
                |--------------------------------------------------------------------------
                | Update Balance
                |--------------------------------------------------------------------------
                */

                $balance->update([

                    'accrued_leaves'   => $accruedLeaves,

                    'carry_forwarded'  => $carryForwarded,

                    'encashed_leaves'  => $encashedLeaves,

                    'remaining_leaves' => $remaining,
                ]);
            }
        });

        return redirect()
            ->route('leave-type.index')
            ->with([
                'message' =>
                    'Leave type updated successfully.',
                'alert-type' => 'success',
            ]);
    }

    /**
     * Delete Leave Type
     */
    public function destroy(LeaveType $leaveType)
    {
        DB::transaction(function () use ($leaveType) {

            LeaveRequest::where(
                    'leave_type_id',
                    $leaveType->id
                )
                ->where('status', 'Pending')
                ->delete();

            LeaveBalance::where(
                'leave_type_id',
                $leaveType->id
            )->delete();

            $leaveType->delete();
        });

        return redirect()
            ->route('leave-type.index')
            ->with([
                'message' =>
                    'Leave type deleted successfully.',
                'alert-type' => 'success',
            ]);
    }
}