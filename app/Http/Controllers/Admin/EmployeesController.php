<?php

namespace App\Http\Controllers\Admin;

use App\DataTables\EmployeeDataTable;
use App\Enums\UserType;
use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Designation;
use App\Models\Company;
use App\Models\EmployeeDetail;
use App\Models\User;
use Chatify\Facades\ChatifyMessenger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use App\Mail\OnboardingMail;
use App\Mail\OnboardingApprovedMail;
use App\Models\OnboardingInvitation;
use Illuminate\Support\Facades\Mail;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Role;
use App\Traits\GenerateEmployeeCode;
use App\Traits\uploadFile;

use App\Models\LeaveType;
use App\Models\LeaveBalance;
use App\Models\Shift;
use App\Models\EmployeeShift;
use Carbon\Carbon;

class EmployeesController extends Controller
{
    use GenerateEmployeeCode, uploadFile;
    
    /**
     * Display a listing of the resource.
     */

    public function __construct() 
    {
        $this->tenant = app('tenant');
    }

    public function index(Request $request)
    {
        $pageTitle = __("Employees");
        $query = User::with('shift')->where('type', UserType::EMPLOYEE);

        if(activeRole() === UserType::TL->value) {
            $query->where('reporting_manager', auth()->id());
        }

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->where('is_active', true);
            } elseif ($request->status === 'inactive') {
                $query->where('is_active', false);
            }
        } else {
            $query->where('is_active', true);
        }

        $employees = $query->paginate(12);

        return view('pages.employees.index', compact(
            'pageTitle',
            'employees'
        ));
    }

    /**
     * Display a listing of the resource.
     */
    public function list(EmployeeDataTable $dataTable)
    {
        $pageTitle = __("employees");
        return $dataTable->render('pages.employees.list', compact(
            'pageTitle',
        ));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $departments     = Department::get();
        $designations    = Designation::get();
        $userList        = User::select('id', 'firstname', 'lastname')->get();
        $shifts          = Shift::all();
        
        return view('pages.employees.create', compact(
            'departments',
            'designations',
            'userList',
            'shifts',
        ));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'firstname'     => 'required',
            'middlename'    => 'nullable|string',
            'lastname'      => 'required',
            'email'         => 'required|email|unique:users,email,except,id',
            'shift'         => 'required',
            'password'      => 'required|string|confirmed',
        ]);

        $user = User::create([
            'type'                  => UserType::EMPLOYEE,
            'firstname'             => $request->firstname,
            'middlename'            => $request->middlename,
            'lastname'              => $request->lastname,
            'email'                 => $request->email,
            'username'              => $request->username,
            'address'               => $request->address,
            'country'               => $request->country_name,
            'country_code'          => $request->country_code,
            'dial_code'             => $request->dial_code,
            'phone'                 => $request->phone,
            'reporting_manager'     => $request->reporting_manager ?? null,
            'sub_reporting_manager' => $request->sub_reporting_manager ?? null,
            'created_by'            => auth()->user()->id,
            'is_active'             => !empty($request->status),
            'password'              => Hash::make($request->password)
        ]);
        if ($user) {

            if ($request->hasFile('avatar')) {
                $path      = $this->tenant->id . '/' . $user->id . '/';
                $fileName  = self::upload($request->file('avatar'), $path);

                $user->update([
                    'avatar' => $fileName,
                ]);
            }

            $user->assignRole(UserType::EMPLOYEE);

            $empId = $this->generateEmployeeCode($this->tenant);
            
            EmployeeDetail::create([
                'emp_id' => $empId,
                'user_id' => $user->id,
                'department_id' => $request->department,
                'designation_id' => $request->designation,
            ]);

            EmployeeShift::create([
                'user_id'   => $user->id,
                'shift_id'  => $request->shift,
            ]);
        }
        $notification = notify(__('Employee has been added'));
        return back()->with($notification);
    }

    /**
     * Display the specified resource.
     */
    public function show(string $employee)
    {

        $id         = Crypt::decrypt($employee);
        $user       = User::findOrFail($id);
        $employee   = $user->employeeDetail;
        $roles      = Role::whereNotIn('name', ['Super Admin', 'Admin'])->get();

        $pageTitle = __('Employee Profile');
        return view('pages.employees.show', compact(
            'employee',
            'user',
            'pageTitle',
            'roles'
        ));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $employee)
    {
        $userId         = Crypt::decrypt($employee);
        $employee       = User::findOrFail($userId);
        $departments    = Department::get();
        $designations   = Designation::get();
        $userList       = User::select('id', 'firstname', 'lastname')->get();
        $shifts         = Shift::all();

        return view('pages.employees.edit', compact(
            'departments',
            'designations',
            'employee',
            'userList',
            'shifts',
        ));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $employee)
    {
        $request->validate([
            'firstname' => 'required',
            'lastname'  => 'required',
            'password'  => 'nullable|string|confirmed',
            'shift'     => 'required',
            'email' => [
                'required',
                'email',
                Rule::unique('users', 'email')->ignore($employee->id),
            ],
        ]);
        $user       = $employee;

        $fileName  = $user->avatar;
        if ($request->hasFile('avatar')) {
            $path      = $this->tenant->id . '/' . $user->id . '/';
            $fileName  = self::upload($request->file('avatar'), $path, $user->avatar ?? '');
        }

        $user->update([
            'firstname'             => $request->firstname ?? $user->firstname,
            'middlename'            => $request->middlename ?? $user->middlename,
            'lastname'              => $request->lastname ?? $user->lastname,
            'email'                 => $request->email ?? $user->email,
            'username'              => $request->username ?? $user->username,
            'address'               => $request->address ?? $user->address,
            'country'               => $request->country_name ?? $user->country,
            'country_code'          => $request->country_code ?? $user->country_code,
            'dial_code'             => $request->dial_code ?? $user->dial_code,
            'phone'                 => $request->phone ?? $user->phone,
            'avatar'                => $fileName,
            'reporting_manager'     => $request->reporting_manager ?? null,
            'sub_reporting_manager' => $request->sub_reporting_manager ?? null,
            'is_active'             => !empty($request->status),
            'password'              => !empty($request->password) ? Hash::make($request->password) : $user->password
        ]);
        if (!empty($user)) {
            if(!$user->hasRole(UserType::EMPLOYEE)){
                $user->assignRole(UserType::EMPLOYEE);
            }
            
            EmployeeDetail::updateOrCreate([
                'user_id' => $user->id,
            ], [
                'user_id'           => $user->id,
                'department_id'     => $request->department,
                'designation_id'    => $request->designation,
            ]);

            // EmployeeShift::create([
            //     'user_id'   => $user->id,
            //     'shift_id'  => $request->shift,
            // ]);
            EmployeeShift::updateOrCreate(
                ['user_id' => $user->id],
                ['shift_id' => $request->shift]
            );
        }
        $notification = notify(__("Employee has been updated"));
        return back()->with($notification);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $employee)
    {
        if ($employee->avatar) {
            $path      = $this->tenant->id . '/' . $employee->id . '/';
            self::delete($employee->avatar, $path);
        }
        $employee->delete();
        $notification = notify(__("Employee has been deleted"));
        return back()->with($notification);
    }

/*
  public function approveOnboarding(Request $request)
    {
        $user = User::findOrFail($request->user_id);

        try {

            //  Mark onboarding complete
            $user->update(['is_onboarding_complete' => 1]);

            $employee = EmployeeDetail::where('user_id', $user->id)->firstOrFail();

            $joinDate = Carbon::parse($employee->date_joined);
            $year     = $joinDate->year;

            
            $leaveTypes = LeaveType::where('is_active', 1)
                ->where('is_paid', 1)
                ->where('monthly_accrual', 0)
                ->where('max_days_per_year', '>', 0)
                ->where(function ($q) use ($user) {
                    if ($user->gender === 'male') {
                        $q->whereIn('gender', [0, 1]);
                    } elseif ($user->gender === 'female') {
                        $q->whereIn('gender', [0, 2]);
                    } else {
                        $q->where('gender', 0);
                    }
                })
                ->get();

            foreach ($leaveTypes as $type) {

                //  Default full grant
                $grant = $type->max_days_per_year;

                
                if ($type->max_days_per_year >= 12) {
                    $monthsRemaining = 12 - $joinDate->month + 1;
                    $grant = round(
                        ($type->max_days_per_year / 12) * $monthsRemaining,
                        2
                    );
                }

                LeaveBalance::updateOrCreate(
                    [
                        'tenant_id'     => $user->tenant_id,
                        'user_id'       => $user->id,
                        'leave_type_id' => $type->id,
                        'year'          => $year,
                    ],
                    [
                        'opening_balance'   => $grant,
                        'accrued_leaves'    => 0,
                        'carry_forwarded'   => 0,
                        'used_leaves'       => 0,
                        'encashed_leaves'   => 0,
                        'remaining_leaves'  => $grant,
                        'last_accrued_month'=> null,
                    ]
                );
            }

            Mail::to($user->email)->send(new OnboardingApprovedMail($user));

        } catch (\Throwable $e) {
            report($e);
            return back()->withErrors('Onboarding approval failed');
        }

        return response()->json([
            'status' => 'success',
            'msg'    => "Employee onboarding approved successfully",
        ]);
    }
*/


    public function approveOnboarding (Request $request) 
    {

        $user = User::findOrFail($request->user_id);

        if(!$user) {
            $notification = notify(__("Employee not found !"));
            return back()->with($notification);
        }

        try {
            $user->update(['is_onboarding_complete' => 1]);
            Mail::to($user->email)->send(new OnboardingApprovedMail($user));
        }catch(\Exception $e) {
            return response()->json(['error' => 'Failed to send email', 'details' => $e->getMessage()], 500);
        }

        return response()->json([
            'status' => 'success',
            'msg'    => "Employee's Onboarding Approved",
        ]);

    }

    public function sendOnboardingInvitation (Request $request)
    {
        $userId         = $request->user_id;
        $user           = User::findOrFail($userId);
        $code           = Str::upper(Str::random(16));
        $onboarding_url = route('onboard.verify', ['user_id' => encrypt($userId), 'code' => encrypt($code)]);

       try {
           $mail = Mail::to($user->email)->send(new OnboardingMail($user, $onboarding_url));

            OnboardingInvitation::updateOrCreate(
                ['id' => $request->invitation_id],
                [
                    'user_id'           => $userId,
                    'verification_code' => $code,
                    'expired_at'        => now()->addDays(5), 
                    'is_sent'           => 1,
                ]
            );

            return response()->json(['msg' => 'Onboarding email sent successfully.']);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to send email', 'details' => $e->getMessage()], 500);
        }
    }

    public function assignRoles(Request $request)
    {
        $request->validate([
            'user_id' => 'required',
            'roles' => 'nullable|array',
            'roles.*' => 'exists:roles,name'
        ]);

        $userId = $request->user_id;
        $user   = User::find($userId);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User Not Found!'
            ]);
        }

        $roles = $request->roles ?? [];

        $user->syncRoles($roles);

        return response()->json([
            'success' => true,
            'message' => 'Roles assigned successfully'
        ]);
    }

}
