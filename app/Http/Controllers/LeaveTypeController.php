<?php

namespace App\Http\Controllers;

use App\Models\LeaveType;
use App\Models\LeaveRequest;
use App\Models\LeaveBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class LeaveTypeController extends Controller
{
    /**
     * List Leave Types
     */
    public function index()
    {
        $leaveTypes = LeaveType::where('tenant_id', Auth::user()->tenant_id)->get();
        $rolesMap = Role::pluck('name', 'id')->toArray();

        return view('pages.leaves.leave-type.index', compact('leaveTypes', 'rolesMap'));
    }

    /**
     * Show Create Page
     */
    public function create()
    {
        $roles = Role::all();
        return view('pages.leaves.leave-type.create', compact('roles'));
    }

    /**
     * Store Leave Type
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'                     => 'required|string|max:255',
            'max_days_per_year'        => 'nullable|integer|min:0',
            'max_carry_forward'        => 'nullable|integer|min:0',
            'min_days_notice'          => 'nullable|integer|min:0',
            'max_days_per_application' => 'nullable|integer|min:0',
            'accrual_rate'             => 'nullable|numeric|min:0',
            'requires_l2_approval'     => 'nullable|boolean',
            'l2_roles'                 => 'nullable|array',
            'l2_roles.*'               => 'exists:roles,id',
        ]);

        if ($validator->fails()) {
            return back()
                ->withInput()
                ->with([
                    'message' => $validator->errors()->first(),
                    'alert-type' => 'danger'
                ]);
        }

        LeaveType::create([
            'tenant_id'               => Auth::user()->tenant_id,
            'name'                    => $request->name,
            'description'             => $request->description,
            'is_paid'                 => $request->boolean('is_paid'),
            'max_days_per_year'       => $request->max_days_per_year,
            'carry_forward'           => $request->boolean('carry_forward'),
            'max_carry_forward'       => $request->max_carry_forward,
            'monthly_accrual'         => $request->monthly_accrual ?? 0,
            'accrual_rate'            => $request->accrual_rate,
            'requires_document'       => $request->boolean('requires_document'),
            'min_days_notice'         => $request->min_days_notice,
            'max_days_per_application'=> $request->max_days_per_application,
            'gender'                  => $request->gender ?? 0,
            'is_encashable'           => $request->boolean('is_encashable'),
            'is_active'               => $request->boolean('is_active'),
            'requires_l2_approval'    => $request->boolean('requires_l2_approval'),
            'l2_roles'                => $request->requires_l2_approval ? $request->l2_roles : null,
        ]);

        return redirect()->route('leave-type.index')->with([
            'message' => 'Leave type created successfully.',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Show Edit Page
     */
    public function edit(LeaveType $leaveType)
    {
        $roles = Role::all();
        $selectedRoles = collect($leaveType->l2_roles ?? [])->map(fn($id) => (int)$id)->toArray();

        return view('pages.leaves.leave-type.edit', compact('leaveType', 'roles', 'selectedRoles'));
    }

    /**
     * Update Leave Type and cascade changes
     */
    public function update(Request $request, LeaveType $leaveType)
    {
        $validator = Validator::make($request->all(), [
            'name'                     => 'required|string|max:255',
            'max_days_per_year'        => 'nullable|integer|min:0',
            'max_carry_forward'        => 'nullable|integer|min:0',
            'min_days_notice'          => 'nullable|integer|min:0',
            'max_days_per_application' => 'nullable|integer|min:0',
            'accrual_rate'             => 'nullable|numeric|min:0',
            'requires_l2_approval'     => 'nullable|boolean',
            'l2_roles'                 => 'nullable|array',
            'l2_roles.*'               => 'exists:roles,id',
        ]);

        if ($validator->fails()) {
            return back()
                ->withInput()
                ->with([
                    'message' => $validator->errors()->first(),
                    'alert-type' => 'danger'
                ]);
        }

        DB::transaction(function () use ($request, $leaveType) {
            $original = $leaveType->replicate();

            $leaveType->update([
                'name'                    => $request->name,
                'description'             => $request->description,
                'is_paid'                 => $request->boolean('is_paid'),
                'max_days_per_year'       => $request->max_days_per_year,
                'carry_forward'           => $request->boolean('carry_forward'),
                'max_carry_forward'       => $request->max_carry_forward,
                'monthly_accrual'         => $request->monthly_accrual ?? 0,
                'accrual_rate'            => $request->accrual_rate,
                'requires_document'       => $request->boolean('requires_document'),
                'min_days_notice'         => $request->min_days_notice,
                'max_days_per_application'=> $request->max_days_per_application,
                'gender'                  => $request->gender ?? 0,
                'is_encashable'           => $request->boolean('is_encashable'),
                'is_active'               => $request->boolean('is_active'),
                'requires_l2_approval'    => $request->boolean('requires_l2_approval'),
                'l2_roles'                => $request->requires_l2_approval ? $request->l2_roles : null,
            ]);

            // Cascade changes to LeaveBalances
            $balances = LeaveBalance::where('leave_type_id', $leaveType->id)->get();
            foreach ($balances as $balance) {
                $balance->update([
                    'max_leaves' => $leaveType->max_days_per_year,
                    'accrual_rate' => $leaveType->accrual_rate ?? 0,
                ]);
            }

            // Cascade changes to Pending LeaveRequests
            $pendingLeaves = LeaveRequest::where('leave_type_id', $leaveType->id)
                ->whereIn('status', ['Pending'])
                ->get();

            foreach ($pendingLeaves as $leave) {
                $leave->approval_stage = $leaveType->requires_l2_approval ? 'L1' : 'FINAL';
                if ($leaveType->max_days_per_application && $leave->days > $leaveType->max_days_per_application) {
                    $leave->days = $leaveType->max_days_per_application;
                }
                $leave->save();
            }
        });

        return redirect()->route('leave-type.index')->with([
            'message' => 'Leave type updated successfully and related balances/requests have been updated.',
            'alert-type' => 'success'
        ]);
    }

    /**
     * Delete Leave Type
     */
    public function destroy(LeaveType $leaveType)
    {
        DB::transaction(function () use ($leaveType) {
            LeaveRequest::where('leave_type_id', $leaveType->id)
                ->where('status', 'Pending')
                ->delete();

            LeaveBalance::where('leave_type_id', $leaveType->id)
                ->delete();

            $leaveType->delete();
        });

        return redirect()->route('leave-type.index')->with([
            'message' => 'Leave type and related balances/requests deleted successfully.',
            'alert-type' => 'success'
        ]);
    }
}

/*
namespace App\Http\Controllers;

use App\Models\LeaveType;
use App\Models\LeaveRequest;
use App\Models\LeaveBalance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;

class LeaveTypeController extends Controller
{
    
    public function index()
    {
        $leaveTypes = LeaveType::where('tenant_id', Auth::user()->tenant_id)->get();
        $rolesMap = Role::pluck('name', 'id')->toArray();

        return view('pages.leaves.leave-type.index', compact('leaveTypes', 'rolesMap'));
    }

    
    public function create()
    {
        $roles = Role::all();
        return view('pages.leaves.leave-type.create', compact('roles'));
    }

    
    public function store(Request $request)
    {
        $request->validate([
            'name'                     => 'required|string|max:255',
            'max_days_per_year'        => 'nullable|integer|min:0',
            'max_carry_forward'        => 'nullable|integer|min:0',
            'min_days_notice'          => 'nullable|integer|min:0',
            'max_days_per_application' => 'nullable|integer|min:0',
            'accrual_rate'             => 'nullable|numeric|min:0',

            'requires_l2_approval'     => 'nullable|boolean',
            'l2_roles'                 => 'nullable|array',
            'l2_roles.*'               => 'exists:roles,id',
        ]);

        LeaveType::create([
            'tenant_id'               => Auth::user()->tenant_id,
            'name'                    => $request->name,
            'description'             => $request->description,
            'is_paid'                 => $request->boolean('is_paid'),
            'max_days_per_year'       => $request->max_days_per_year,
            'carry_forward'           => $request->boolean('carry_forward'),
            'max_carry_forward'       => $request->max_carry_forward,
            'monthly_accrual'         => $request->monthly_accrual ?? 0,
            'accrual_rate'            => $request->accrual_rate,
            'requires_document'       => $request->boolean('requires_document'),
            'min_days_notice'         => $request->min_days_notice,
            'max_days_per_application'=> $request->max_days_per_application,
            'gender'                  => $request->gender ?? 0,
            'is_encashable'           => $request->boolean('is_encashable'),
            'is_active'               => $request->boolean('is_active'),

            'requires_l2_approval'    => $request->boolean('requires_l2_approval'),
            'l2_roles'                => $request->requires_l2_approval ? $request->l2_roles : null,
        ]);

        return redirect()->route('leave-type.index')
            ->with('success', 'Leave type created successfully.');
    }

   
    public function edit(LeaveType $leaveType)
    {
        $roles = Role::all();
        $selectedRoles = collect($leaveType->l2_roles ?? [])->map(fn($id) => (int)$id)->toArray();

        return view('pages.leaves.leave-type.edit', compact('leaveType', 'roles', 'selectedRoles'));
    }

    
    public function update(Request $request, LeaveType $leaveType)
    {
        $request->validate([
            'name'                     => 'required|string|max:255',
            'max_days_per_year'        => 'nullable|integer|min:0',
            'max_carry_forward'        => 'nullable|integer|min:0',
            'min_days_notice'          => 'nullable|integer|min:0',
            'max_days_per_application' => 'nullable|integer|min:0',
            'accrual_rate'             => 'nullable|numeric|min:0',

            'requires_l2_approval'     => 'nullable|boolean',
            'l2_roles'                 => 'nullable|array',
            'l2_roles.*'               => 'exists:roles,id',
        ]);

        DB::transaction(function () use ($request, $leaveType) {
            $original = $leaveType->replicate(); // keep original for comparison

            $leaveType->update([
                'name'                    => $request->name,
                'description'             => $request->description,
                'is_paid'                 => $request->boolean('is_paid'),
                'max_days_per_year'       => $request->max_days_per_year,
                'carry_forward'           => $request->boolean('carry_forward'),
                'max_carry_forward'       => $request->max_carry_forward,
                'monthly_accrual'         => $request->monthly_accrual ?? 0,
                'accrual_rate'            => $request->accrual_rate,
                'requires_document'       => $request->boolean('requires_document'),
                'min_days_notice'         => $request->min_days_notice,
                'max_days_per_application'=> $request->max_days_per_application,
                'gender'                  => $request->gender ?? 0,
                'is_encashable'           => $request->boolean('is_encashable'),
                'is_active'               => $request->boolean('is_active'),
                'requires_l2_approval'    => $request->boolean('requires_l2_approval'),
                'l2_roles'                => $request->requires_l2_approval ? $request->l2_roles : null,
            ]);

            // =============================
            // Cascade changes to LeaveBalances
            // =============================
            $balances = LeaveBalance::where('leave_type_id', $leaveType->id)->get();
            foreach ($balances as $balance) {
                $balance->update([
                    'max_leaves' => $leaveType->max_days_per_year,
                    'accrual_rate' => $leaveType->accrual_rate ?? 0,
                ]);
            }

            // =============================
            // Cascade changes to Pending LeaveRequests
            // =============================
            $pendingLeaves = LeaveRequest::where('leave_type_id', $leaveType->id)
                ->whereIn('status', ['Pending'])
                ->get();

            foreach ($pendingLeaves as $leave) {
                // Update approval stage if L2 requirement changed
                if ($leaveType->requires_l2_approval) {
                    $leave->approval_stage = 'L1';
                } else {
                    $leave->approval_stage = 'FINAL';
                }

                // Ensure approved_days does not exceed max_days_per_application
                if ($leaveType->max_days_per_application && $leave->days > $leaveType->max_days_per_application) {
                    $leave->days = $leaveType->max_days_per_application;
                }

                $leave->save();
            }
        });

        return redirect()->route('leave-type.index')
            ->with('success', 'Leave type updated successfully and related balances/requests have been updated.');
    }

   
    public function destroy(LeaveType $leaveType)
    {
        DB::transaction(function () use ($leaveType) {
            // Optional: delete all pending leave requests and balances?
            LeaveRequest::where('leave_type_id', $leaveType->id)
                ->where('status', 'Pending')
                ->delete();

            LeaveBalance::where('leave_type_id', $leaveType->id)
                ->delete();

            $leaveType->delete();
        });

        return redirect()->route('leave-type.index')
            ->with('success', 'Leave type and related balances/requests deleted successfully.');
    }
}


//////////////////////////



namespace App\Http\Controllers;

use App\Models\LeaveType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Role;

class LeaveTypeController extends Controller
{
    
    public function index()
    {
        $leaveTypes = LeaveType::where('tenant_id', Auth::user()->tenant_id)->get();

        // Role map for listing (id => name)
        $rolesMap = Role::pluck('name', 'id')->toArray();

        return view('pages.leaves.leave-type.index', compact('leaveTypes', 'rolesMap'));
    }

    
    public function create()
    {
        // Fetch full Role objects for Blade iteration
        $roles = Role::all();

        return view('pages.leaves.leave-type.create', compact('roles'));
    }

   
    public function store(Request $request)
    {
        $request->validate([
            'name'                     => 'required|string|max:255',
            'max_days_per_year'        => 'nullable|integer|min:0',
            'max_carry_forward'        => 'nullable|integer|min:0',
            'min_days_notice'          => 'nullable|integer|min:0',
            'max_days_per_application' => 'nullable|integer|min:0',
            'accrual_rate'             => 'nullable|numeric|min:0',

            // Approval
            'requires_l2_approval'     => 'nullable|boolean',
            'l2_roles'                 => 'nullable|array',
            'l2_roles.*'               => 'exists:roles,id',
        ]);

        LeaveType::create([
            'tenant_id'               => Auth::user()->tenant_id,
            'name'                    => $request->name,
            'description'             => $request->description,

            'is_paid'                 => $request->boolean('is_paid'),
            'max_days_per_year'       => $request->max_days_per_year,
            'carry_forward'           => $request->boolean('carry_forward'),
            'max_carry_forward'       => $request->max_carry_forward,
            'monthly_accrual'         => $request->monthly_accrual ?? 0,
            'accrual_rate'            => $request->accrual_rate,
            'requires_document'       => $request->boolean('requires_document'),
            'min_days_notice'         => $request->min_days_notice,
            'max_days_per_application'=> $request->max_days_per_application,
            'gender'                  => $request->gender ?? 0,
            'is_encashable'           => $request->boolean('is_encashable'),
            'is_active'               => $request->boolean('is_active'),

            // Approval
            'requires_l2_approval'    => $request->boolean('requires_l2_approval'),
            'l2_roles'                => $request->requires_l2_approval
                                        ? $request->l2_roles
                                        : null,
        ]);

        return redirect()
            ->route('leave-type.index')
            ->with('success', 'Leave type created successfully.');
    }

   
    
    public function edit(LeaveType $leaveType)
    {
        $roles = Role::all();
    
        $selectedRoles = collect($leaveType->l2_roles ?? [])
                            ->map(fn ($id) => (int) $id)
                            ->toArray();
    
        return view(
            'pages.leaves.leave-type.edit',
            compact('leaveType', 'roles', 'selectedRoles')
        );
    }

    
    public function update(Request $request, LeaveType $leaveType)
    {
        $request->validate([
            'name'                     => 'required|string|max:255',
            'max_days_per_year'        => 'nullable|integer|min:0',
            'max_carry_forward'        => 'nullable|integer|min:0',
            'min_days_notice'          => 'nullable|integer|min:0',
            'max_days_per_application' => 'nullable|integer|min:0',
            'accrual_rate'             => 'nullable|numeric|min:0',

            // Approval
            'requires_l2_approval'     => 'nullable|boolean',
            'l2_roles'                 => 'nullable|array',
            'l2_roles.*'               => 'exists:roles,id',
        ]);

        $leaveType->update([
            'name'                    => $request->name,
            'description'             => $request->description,

            'is_paid'                 => $request->boolean('is_paid'),
            'max_days_per_year'       => $request->max_days_per_year,
            'carry_forward'           => $request->boolean('carry_forward'),
            'max_carry_forward'       => $request->max_carry_forward,
            'monthly_accrual'         => $request->monthly_accrual ?? 0,
            'accrual_rate'            => $request->accrual_rate,
            'requires_document'       => $request->boolean('requires_document'),
            'min_days_notice'         => $request->min_days_notice,
            'max_days_per_application'=> $request->max_days_per_application,
            'gender'                  => $request->gender ?? 0,
            'is_encashable'           => $request->boolean('is_encashable'),
            'is_active'               => $request->boolean('is_active'),

            // Approval
            'requires_l2_approval'    => $request->boolean('requires_l2_approval'),
            'l2_roles'                => $request->requires_l2_approval
                                        ? $request->l2_roles
                                        : null,
        ]);

        return redirect()
            ->route('leave-type.index')
            ->with('success', 'Leave type updated successfully.');
    }

   
    public function destroy(LeaveType $leaveType)
    {
        $leaveType->delete();

        return redirect()
            ->route('leave-type.index')
            ->with('success', 'Leave type deleted successfully.');
    }
}
*/