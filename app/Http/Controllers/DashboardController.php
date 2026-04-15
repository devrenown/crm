<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Ticket;
use App\Models\Tenant;
use App\Models\Asset;
use App\Models\Plan;
use App\Models\Attendance;
use App\Enums\UserType;
use App\Helpers\AppMenu;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Modules\Sales\Models\Expense;
use Modules\Sales\Models\Invoice;
use LaravelLang\LocaleList\Locale;
use Modules\Sales\Models\Estimate;
use Modules\Accounting\Models\Budget;
use Modules\Project\Models\Task;
use App\Http\Controllers\BaseController;
use Spatie\Permission\PermissionRegistrar;
use App\Enums\TenantStatus;
use App\Services\CurrencyService;
use Illuminate\Support\Facades\DB;

class DashboardController extends BaseController
{
    public $companySettings;

    public function __construct () 
    {
        $this->companySettings = app(\App\Settings\CompanySettings::class);
    }

    public function front (Request $request) 
    {
        $currencyCode = session('currency', 'INR');
        $rate = CurrencyService::getRate('INR', $currencyCode);

        $currency = DB::table('currencies')->where('code', $currencyCode)->first();

        $plans = Plan::where('status', 1)->get()->map(function ($plan) use ($rate) {
            $plan->display_price = round($plan->price * $rate, 2);
            return $plan;
        });

        if ($request->getHost() === env('PRIMARY_HOST', 'renownsystem.com')) {
            return view("pages.front.index", ['plans' => $plans, 'currency' => $currency]);
        }

        return redirect()->route('tenant.login');
    }

    public function index()
    {
        $this->data['pageTitle'] = __('Dashboard');
        $user = auth()->user();

        // =================== Event Queries Start ==================== //

        $start = Carbon::today()->format('m-d');
        $end   = Carbon::today()->addDays(7)->format('m-d');

        $today      = Carbon::today();
        $endDate    = Carbon::today()->addDays(7);
        $probationDays = (int)$this->companySettings->probation_period;

        // 1. Define the base query
        $baseQuery = User::where(['users.is_active' => true, 'users.is_onboarding_complete' => 1])->join('employee_details', 'users.id', '=', 'employee_details.user_id')
            ->select('users.id', 'users.firstname', 'users.middlename', 'users.lastname', 'users.avatar', 'employee_details.user_id', 'employee_details.dob', 'employee_details.date_joined', 'users.is_active')
            ->with('employeeDetail');


        $upcomingBirthdays = (clone $baseQuery)
        ->where(function ($q) use ($start, $end) {
            if ($start <= $end) {
                $q->whereRaw("DATE_FORMAT(employee_details.dob, '%m-%d') BETWEEN ? AND ?", [$start, $end]);
            } else {
                $q->whereRaw("DATE_FORMAT(employee_details.dob, '%m-%d') >= ? OR DATE_FORMAT(employee_details.dob, '%m-%d') <= ?", [$start, $end]);
            }
        })
        ->orderByRaw("
            CASE 
                WHEN DATE_FORMAT(employee_details.dob, '%m-%d') >= ? THEN 0 
                ELSE 1 
            END ASC, 
            DATE_FORMAT(employee_details.dob, '%m%d') ASC
        ", [$start])
        ->get();


        $upcomingWorkAnniversaries = (clone $baseQuery)
        ->whereDate('employee_details.date_joined', '<', Carbon::today())
        ->where(function ($q) use ($start, $end) {
            if ($start <= $end) {
                $q->whereRaw("DATE_FORMAT(employee_details.date_joined, '%m-%d') BETWEEN ? AND ?", [$start, $end]);
            } else {
                $q->whereRaw("DATE_FORMAT(employee_details.date_joined, '%m-%d') >= ? OR DATE_FORMAT(employee_details.date_joined, '%m-%d') <= ?", [$start, $end]);
            }
        })
        ->orderByRaw("
            CASE 
                WHEN DATE_FORMAT(employee_details.date_joined, '%m-%d') >= ? THEN 0 
                ELSE 1 
            END ASC, 
            DATE_FORMAT(employee_details.date_joined, '%m%d') ASC
        ", [$start])
        ->get();

        $upcomingProbationCompleted = (clone $baseQuery)
        ->whereNotNull('employee_details.date_joined')
        ->whereRaw(
            "DATE_ADD(employee_details.date_joined, INTERVAL ? DAY) BETWEEN ? AND ?",
            [
                $probationDays,
                $today->toDateString(),
                $endDate->toDateString()
            ]
        )
        ->addSelect(\DB::raw("
            DATE_ADD(employee_details.date_joined, INTERVAL {$probationDays} DAY)
            as probation_end_date
        "))
        ->get();

        $this->data['upcomingBirthdays']            = $upcomingBirthdays;
        $this->data['upcomingWorkAnniversaries']    = $upcomingWorkAnniversaries;
        $this->data['upcomingProbationCompleted']   = $upcomingProbationCompleted;

        // =================== Event Queries End ==================== //

        $myTasks = Task::with('project')
                -> whereHas('followers', function ($query) {
                    $query->where('user_id', auth()->id());
                })
                ->orderBy('priority', 'asc')
                ->get();

        $this->data['myTasks'] = $myTasks;

        if(
            ($user->active_role && $user->active_role === UserType::EMPLOYEE->value)
            || (!$user->active_role && $user->type === UserType::EMPLOYEE->value)
        )
        {
            return view('pages.employees.dashboard',$this->data);
        }

        $projects = null;
        $recentProjects = null;
        if(!empty(module('Project')) && module('Project')->isEnabled()){
            if (activeRole() === UserType::TL->value) {
               $projects = \Modules\Project\Models\Project::where('leader_id', auth()->id())->get(); 
               $recentProjects = \Modules\Project\Models\Project::where('leader_id', auth()->id())->whereMonth('created_at', Carbon::today())->get();
            }else {
               $projects = \Modules\Project\Models\Project::get(); 
               $recentProjects = \Modules\Project\Models\Project::whereMonth('created_at', Carbon::today())->get();
            }
        }
        $clients = User::where(['type' => UserType::CLIENT->value, 'is_active' => true])->get();
        $thisMonthClients = User::where('type', UserType::CLIENT->value)->whereMonth('created_at', Carbon::today())->get();

        if(activeRole() === UserType::TL->value) {
            $employees = User::where(['type' => UserType::EMPLOYEE->value, 'is_active' => true, 'reporting_manager' => auth()->id()])->get();
        }else {
            $employees = User::where(['type' => UserType::EMPLOYEE->value,  'is_active' => true])->get();
        }
        
        $tickets = Ticket::get();

        if(module('Sales') && module('Sales')->isEnabled()){

            $this->data['thisMonthExpenses'] = Expense::whereMonth('created_at', Carbon::now())->sum('amount');
            $this->data['prevMonthExpenses'] = Expense::whereMonth('created_at', Carbon::now()->subMonth())->sum('amount');
            
            $this->data['thisMonthEstimates'] = Estimate::whereMonth('created_at', Carbon::now())->sum('grand_total');
            $this->data['prevMonthEstimates'] = Estimate::whereMonth('created_at', Carbon::now()->subMonth())->sum('grand_total');
            
            $this->data['thisMonthInvoices'] = Invoice::whereMonth('created_at', Carbon::now())->sum('grand_total');
            $this->data['prevMonthInvoices'] = Invoice::whereMonth('created_at', Carbon::now()->subMonth())->sum('grand_total');
            $this->data['invoices'] = Invoice::get();
            
            $this->data['thisMonthInvoiceList'] = Invoice::whereMonth('created_at', Carbon::now())->get();
            $this->data['thisMonthPaidInvoiceList'] = Invoice::whereMonth('created_at', Carbon::now())->where('status', '2')->get();

            $month = 1;
            $expense_collection     = collect();
            $budget_collection      = collect();
            $invoice_collection     = collect();
            $estimates_collection   = collect();

            while ($month <= 12)
            {
                $expense_collection->push(
                   Expense::whereMonth('created_at', $month)->get()
                );
                $budget_collection->push(
                    Budget::whereMonth('created_at', $month)->get()
                );
                $invoice_collection->push(
                    Invoice::whereMonth('created_at', $month)->get()
                );
                $estimates_collection->push(
                    Estimate::whereMonth('created_at', $month)->get()
                );
                $month += 1;
            }
            $this->data['monthly_expense']      = $expense_collection;
            $this->data['budget_collection']    = $budget_collection;
            $this->data['invoice_collection']   = $invoice_collection;
            $this->data['estimates_collection'] = $estimates_collection;
        }

        $budgets = null;

        if(module('Accounting') && module('Accounting')->isEnabled()){
        
            $budgets = Budget::get(); 
        }

        
        //attendances
        if (activeRole() === UserType::TL->value) {
            $absentees = User::where(['type' => UserType::EMPLOYEE->value, 'reporting_manager' => auth()->id(), 'is_active' => true])->whereDoesntHave('attendances', function($query){
                return $query->whereDay('created_at', Carbon::today())->take(1);
            })->get();
        }else {
            $absentees = User::where(['type' => UserType::EMPLOYEE->value, 'is_active' => true])->whereDoesntHave('attendances', function($query){
                return $query->whereDay('created_at', Carbon::today())->take(1);
            })->get();
        }

        $pQuery = User::where(['type' => UserType::EMPLOYEE->value, 'is_active' => true])
        ->with(['attendances' => function ($q) {
            $q->whereDate('created_at', Carbon::today())
              ->orderBy('created_at')
              ->limit(1);
        }])
        ->whereHas('attendances', function ($q) {
            $q->whereDate('created_at', Carbon::today());
        });

        if (activeRole() === UserType::TL->value) {
            $pQuery->where('reporting_manager', auth()->id());
        }

        $persents = $pQuery->get();

        $query = User::where([
            'type' => UserType::EMPLOYEE->value,
            'is_active' => true
        ])
        ->with([
            'employeeDetail.department',
            'firstAttendanceToday',
            'lastAttendanceToday'
        ])
        ->withCount([
            'attendances as today_attendance_count' => function ($q) {
                $q->whereDate('created_at', today());
            }
        ]);

        if (activeRole() === UserType::TL->value) {
            $query->where('reporting_manager', auth()->id());
        }

        $employeesAttendance = $query->get();


        // dd($employeesAttendance);
        
        $presentCount   = $employeesAttendance->where('today_attendance_count', '>', 0)->count();
        $absentCount    = $employeesAttendance->where('today_attendance_count', 0)->count();

        $allInvoiceCount    = Invoice::count();
        $allAssetCount      = Asset::count();
        $allUsersCount      = User::count();
        $totalActiveUser    = User::where('is_active', true)->count();

        $this->data['presentCount']         = $presentCount;
        $this->data['absentCount']          = $absentCount;
        $this->data['employeesAttendance']  = $employeesAttendance;
        $this->data['allInvoiceCount']      = $allInvoiceCount;
        $this->data['allAssetCount']        = $allAssetCount;
        $this->data['allUsersCount']        = $allUsersCount;
        $this->data['totalActiveUser']      = $totalActiveUser;

        if (activeRole() === UserType::TL->value) {
            $this->data['thisMonthTotalEmployees'] = User::where(['type' => UserType::EMPLOYEE->value, 'reporting_manager' => auth()->id()])->whereMonth('created_at', Carbon::now())->count() ?? 0;
            $this->data['prevMonthTotalEmployees'] = User::where(['type' => UserType::EMPLOYEE->value, 'reporting_manager' => auth()->id()])->whereMonth('created_at', Carbon::now()->subMonth(1))->count() ?? 0;
        }else {
            $this->data['thisMonthTotalEmployees'] = User::where('type', UserType::EMPLOYEE->value)->whereMonth('created_at', Carbon::now())->count() ?? 0;
            $this->data['prevMonthTotalEmployees'] = User::where('type', UserType::EMPLOYEE->value)->whereMonth('created_at', Carbon::now()->subMonth(1))->count() ?? 0;
        }

        

        $tasks = Task::with(['createdBy', 'followers', 'project'])
                ->latest()
                ->limit(10)
                ->get();

        $this->data['clients']                      = (!empty($clients) && $clients->count() > 0) ? $clients: null;
        $this->data['thisMonthClients']             = $thisMonthClients;
        $this->data['employees']                    = (!empty($employees) && $employees->count() > 0) ? $employees: null;
        $this->data['tickets']                      = (!empty($tickets) && $tickets->count() > 0) ? $tickets: null;
        $this->data['tasks']                        = $tasks;
        $this->data['projects']                     = $projects;
        $this->data['recentProjects']               = $recentProjects;
        $this->data['ReportingManagerList']         = User::reportingManagerList();

        // =================== Super Admin Queries ==================== //
        $tenants        = Tenant::all();
        $activeTenants  = Tenant::where('status', TenantStatus::ACTIVE)->count();
        $monthlyRevenue = DB::table('subscriptions')->whereMonth('subscriptions.start_date', Carbon::now())->join('plans', 'subscriptions.plan_id', '=', 'plans.id')->sum('plans.price');
        $totalRevenue   = DB::table('subscriptions')->join('plans', 'subscriptions.plan_id', '=', 'plans.id')->sum('plans.price');
        $totalUsers     = DB::table('users')->count();

        $platformFinance = collect([
            ['month' => 'Jan', 'revenue' => 120000, 'expense' => 45000],
            ['month' => 'Feb', 'revenue' => 135000, 'expense' => 52000],
            ['month' => 'Mar', 'revenue' => 160000, 'expense' => 60000],
            ['month' => 'Apr', 'revenue' => 175000, 'expense' => 65000],
            ['month' => 'May', 'revenue' => 190000, 'expense' => 72000],
            ['month' => 'Jun', 'revenue' => 210000, 'expense' => 85000],
            ['month' => 'Jul', 'revenue' => 230000, 'expense' => 90000],
            ['month' => 'Aug', 'revenue' => 245000, 'expense' => 98000],
            ['month' => 'Sep', 'revenue' => 265000, 'expense' => 105000],
            ['month' => 'Oct', 'revenue' => 280000, 'expense' => 112000],
            ['month' => 'Nov', 'revenue' => 305000, 'expense' => 120000],
            ['month' => 'Dec', 'revenue' => 330000, 'expense' => 130000],
        ]);

        $subscriptionGrowth = collect([
            ['month' => 'Jan', 'new' => 12, 'upgrade' => 3],
            ['month' => 'Feb', 'new' => 18, 'upgrade' => 5],
            ['month' => 'Mar', 'new' => 25, 'upgrade' => 7],
            ['month' => 'Apr', 'new' => 30, 'upgrade' => 8],
            ['month' => 'May', 'new' => 36, 'upgrade' => 10],
            ['month' => 'Jun', 'new' => 42, 'upgrade' => 12],
            ['month' => 'Jul', 'new' => 48, 'upgrade' => 14],
            ['month' => 'Aug', 'new' => 55, 'upgrade' => 16],
            ['month' => 'Sep', 'new' => 60, 'upgrade' => 18],
            ['month' => 'Oct', 'new' => 68, 'upgrade' => 20],
            ['month' => 'Nov', 'new' => 75, 'upgrade' => 22],
            ['month' => 'Dec', 'new' => 85, 'upgrade' => 25],
        ]);

        $planDistribution = collect([
            ['label' => 'Free Plan', 'value' => 45],
            ['label' => 'Starter Plan', 'value' => 30],
            ['label' => 'Professional Plan', 'value' => 18],
            ['label' => 'Enterprise Plan', 'value' => 7],
        ]);

        $monthlyExpense = collect([
            ['month' => 'Jan', 'amount' => 45000],
            ['month' => 'Feb', 'amount' => 52000],
            ['month' => 'Mar', 'amount' => 60000],
            ['month' => 'Apr', 'amount' => 65000],
            ['month' => 'May', 'amount' => 72000],
            ['month' => 'Jun', 'amount' => 85000],
            ['month' => 'Jul', 'amount' => 90000],
            ['month' => 'Aug', 'amount' => 98000],
            ['month' => 'Sep', 'amount' => 105000],
            ['month' => 'Oct', 'amount' => 112000],
            ['month' => 'Nov', 'amount' => 120000],
            ['month' => 'Dec', 'amount' => 130000],
        ]);

        $recentActivities = collect([
            (object)[
                'tenant_name' => 'Acme Corp',
                'action' => 'Subscribed',
                'plan' => 'Professional',
                'created_at' => now()->subMinutes(10),
            ],
            (object)[
                'tenant_name' => 'TechNova Pvt Ltd',
                'action' => 'Upgraded Plan',
                'plan' => 'Enterprise',
                'created_at' => now()->subHours(2),
            ],
            (object)[
                'tenant_name' => 'BlueOcean Ltd',
                'action' => 'Payment Received',
                'plan' => 'Starter',
                'created_at' => now()->subDay(),
            ],
            (object)[
                'tenant_name' => 'CloudSpark',
                'action' => 'Subscription Renewed',
                'plan' => 'Professional',
                'created_at' => now()->subDays(2),
            ],
        ]);

        $this->data['tenants']              = $tenants;
        $this->data['activeTenants']        = $activeTenants;
        $this->data['monthlyRevenue']       = $monthlyRevenue;
        $this->data['totalUsers']           = $totalUsers;
        $this->data['platformFinance']      = $platformFinance;
        $this->data['subscriptionGrowth']   = $subscriptionGrowth;
        $this->data['planDistribution']     = $planDistribution;
        $this->data['monthlyExpense']       = $monthlyExpense;
        $this->data['recentActivities']     = $recentActivities;
        $this->data['totalRevenue']         = $totalRevenue;


        return view('pages.dashboard', $this->data);
    }

    public function switchRole(Request $request)
    {
        // dd($request->all());
        $request->validate(['role' => 'required|string']);

        $user = auth()->user();
        $role = $request->role;

        if (! $user->hasRole($role)) {
            return back()->with('error', 'You do not have this role.');
        }

        // Store in session
        session(['active_role' => $role]);

        // Optionally persist on user record
        $user->update(['active_role' => $role]);
        app(PermissionRegistrar::class)->forgetCachedPermissions();
        $user->unsetRelation('roles');
        $user->unsetRelation('permissions');
        auth()->setUser($user->fresh());

        $notification = notify("Switched to role: {$role}");
        return redirect('/dashboard')->with($notification);
    }
}
