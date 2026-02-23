<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Admin\ChatController;
use App\Http\Controllers\AllowancesController;
use App\Http\Controllers\ConferenceController;
use App\Http\Controllers\DeductionsController;
use App\Http\Controllers\Admin\UsersController;
use App\Http\Controllers\UserProfileController;
use App\Http\Controllers\Admin\AssetsController;
use App\Http\Controllers\Admin\ChatAppController;
use App\Http\Controllers\Admin\ClientsController;
use App\Http\Controllers\Admin\TicketsController;
use App\Http\Controllers\Admin\HolidaysController;
use App\Http\Controllers\Admin\PayrollsController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\EmployeesController;
use App\Http\Controllers\Admin\TenantController;
use App\Http\Controllers\Admin\FamilyInfoController;
use App\Http\Controllers\Admin\AttendancesController;
use App\Http\Controllers\Admin\DepartmentsController;
use App\Http\Controllers\Admin\DesignationsController;
use App\Http\Controllers\Admin\EmployeeDetailsController;
use App\Http\Controllers\Admin\ReportingManagersController;
use App\Http\Controllers\Admin\SubscriptionController;
use App\Http\Controllers\Employee\OnboardController;
use App\Http\Controllers\Employee\WorkReportController;
use App\Http\Controllers\FrontController;
use App\Http\Controllers\EmployeeAttendanceController;
use App\Http\Controllers\Leaves\{
    LeaveIndexController,
    LeaveCreateController,
    LeaveViewController,
    LeaveApprovalController,
    LeaveDeleteController
};
use App\Http\Controllers\LeaveTypeController;
use App\Http\Controllers\LeaveBalanceController;
use App\Http\Controllers\RazorpayPaymentController;
use App\Http\Controllers\ShiftManagementController;

use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Cache;


Route::get('/', [DashboardController::class, 'front'])->name('front');
Route::get('onboarding/verify/{user_id}/{code}', [OnboardController::class, 'verifyOnboarding'])->name('onboard.verify');

// Route::get('demo-request', [FrontController::class, 'demoRequest'])->name('demo.request');
Route::post('save-demo-request', [FrontController::class, 'saveRequest'])->name('save.demo-request');
Route::get('privacy-policy', [FrontController::class, 'privacyPolicyView'])->name('privacy-policy');
Route::get('terms_&_conditions', [FrontController::class, 'termsConditionsView'])->name('terms-conditions');
Route::post('contact', [FrontController::class, 'saveContact'])->name('save.contact');



// Cache routes

Route::get('/route-clear', function() {
    Artisan::call('route:clear');
    return 'route cleared';
});
Route::get('/view-clear', function() {
    Artisan::call('view:clear');
    return 'view cleared';
});
Route::get('/cache-clear', function() {
    Artisan::call('cache:clear');
    return 'cache cleared';
});
Route::get('/config-clear', function() {
    Artisan::call('config:clear');
    return 'config cleared';
});

// All cache clear
Route::get('/all-clear', function() {
    Artisan::call('cache:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('config:clear');
    return 'all cleared';
});

include __DIR__ . '/auth.php';

Route::middleware(['auth'])->group(function () {
    //Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
    // New (allow both GET and POST)
    Route::middleware(['onboarding'])->group(function () {
        Route::match(['get', 'post'], '/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        Route::controller(SubscriptionController::class)->group(function () {
          Route::get('subscriptions', 'index')->name('subscription.index');
          Route::get('upgrade', 'upgrade')->name('subscription.upgrade'); 
        });
    });

    Route::middleware(['onboarding', 'check_plan'])->group(function () {

        Route::controller(UserProfileController::class)->group( function (){
            Route::get('profile',  'index')->name('profile');
            Route::get('profile/edit',  'edit')->name('profile.edit');
            Route::post('profile',  'update');
            Route::post('profile/update-password',  'updatePassword')->name('profile.update-password');
        });

        Route::group(['prefix' => 'apps'], function () {
            Route::get('chat/{contact?}', [ChatAppController::class, 'index'])->name('app.chat');
            Route::delete('delete-chat/{receiver}', [ChatAppController::class, 'destroy'])->name('chat.delete-conversation');
        });

        Route::resource('users', UsersController::class);
        Route::resource('clients', ClientsController::class);
        Route::get('client-list', [ClientsController::class, 'list'])->name('clients.list');

        Route::controller(EmployeeDetailsController::class)->group( function () {
            // Route::get('employee/view-personal-info/{employeeDetail}', 'viewPersonalInfo')->name('employee.view.personal-info');
            Route::get('employee/personal-info/{employeeDetail}',  'personalInfo')->name('employee.personal-info');
            Route::post('employee/personal-info/{employeeDetail}',  'updatePersonalInfo');
            Route::get('employee/identity/{employeeDetail}',  'empIdentity')->name('employee.identity');
            Route::get('employee/emergency-contacts/{employeeDetail}',  'emergencyContacts')->name('employee.emergency-contacts');
            Route::post('employee/emergency-contacts/{employeeDetail}',  'updateEmergencyContacts');
            Route::get('employee/experience/{employeeDetail}',  'workExperience')->name('employee.experience');
            Route::post('employee/experience/{employeeDetail}',  'updateWorkExperience');
            Route::delete('delete-experience/{experience}',  'deleteWorkExperience')->name('employee.experience.delete');
            Route::get('employee/education/{employeeDetail}',  'education')->name('employee.education');
            Route::post('employee/education/{employeeDetail}',  'updateEducation')->name('employee.education.update');
            Route::delete('del-employee-education',  'deleteEducation')->name('employee.education.delete');
            Route::post('employee-salary-setting/{employeeDetail}',  'salarySetting')->name('employee.salary-setting');
        });

        Route::controller(ReportingManagersController::class)->group( function () {
            Route::get('reporting-managers',  'index')->name('reporting-managers');
            Route::get('assign-reporting-manager',  'assignView')->name('reporting-manager.assign.view');
            Route::post('assign-reporting-manager',  'assign')->name('reporting-manager.assign');
            Route::get('edit-reporting-manager/{reporting_manager}',  'editAssign')->name('reporting-manager.edit');
            Route::post('update-reporting-manager',  'updateAssign')->name('reporting-manager.update');
            Route::delete('delete-reporting-manager/{reporting_manager}',  'deleteReportingManager')->name('reporting-manager.delete');
        });

        Route::group(['prefix' => 'payroll'], function () {
            Route::get('items', [PayrollsController::class, 'items'])->name('payroll.items');
            Route::resource('allowances', AllowancesController::class)->except(['show']);
            Route::resource('deductions', DeductionsController::class)->except(['show']);
            Route::resource('payslips', PayrollsController::class);
        });

        Route::resource('departments', DepartmentsController::class)->except(['show']);
        Route::resource('designations', DesignationsController::class)->except(['show']);
        Route::resource('holidays', HolidaysController::class);
        Route::get('holidays-calendar', [HolidaysController::class, 'calendar'])->name('holidays.calendar');
        Route::resource('family-information', FamilyInfoController::class);
        Route::resource('assets-list', AssetsController::class);
        Route::get('backups', fn() => view('pages.backups', ['pageTitle' => __('Backups')]))->name('backups.index');
        Route::get('attendance', [AttendancesController::class, 'index'])->name('attendances.index');
        Route::get('attendance-details/{attendance}', [AttendancesController::class, 'attendanceDetails'])->name('attendance.details');
        Route::get('attendance-history/{employee_id}', [AttendancesController::class, 'attendanceHistory'])->name('attendance.history');

        Route::get('clockout-modal/{timeId?}', [EmployeeAttendanceController::class, 'clockoutModal'])->name('clockout-modal');
        Route::post('clockout', [EmployeeAttendanceController::class, 'clockout'])->name('clockout');
        Route::resource('tickets', TicketsController::class);
        Route::get('assigned-tickets', [TicketsController::class, 'assignedTickets'])->name('assigned-tickets');
        Route::post('assign-ticket', [TicketsController::class, 'assignUser'])->name('ticket.assign-user');

        Route::get('app-logs', fn() => redirect()->to('log-viewer'))->name('app.logs');

        //settings
        Route::prefix('settings')->group(function () {
            
            Route::get('company',  [SettingsController::class, 'index'])->name('settings.index');
            Route::post('company',  [SettingsController::class, 'updateCompany'])->name('settings.company.update');

            Route::get('locale',  [SettingsController::class, 'locale'])->name('settings.locale');
            Route::post('locale',  [SettingsController::class, 'updateLocale'])->name('settings.locale.update');
            Route::get('theme',  [SettingsController::class, 'theme'])->name('settings.theme');
            Route::post('theme',  [SettingsController::class, 'updateTheme'])->name('settings.theme.update');
            Route::get('invoice',  [SettingsController::class, 'invoice'])->name('settings.invoice');
            Route::post('invoice',  [SettingsController::class, 'updateInvoice'])->name('settings.invoice.update');
            Route::get('salary',  [SettingsController::class, 'salary'])->name('settings.salary');
            Route::post('salary',  [SettingsController::class, 'updateSalarySettings'])->name('settings.salary.update');
            Route::get('mail',  [SettingsController::class, 'email'])->name('settings.mail');
            Route::post('mail',  [SettingsController::class, 'updateEmail'])->name('settings.mail.update');
        });

        Route::controller(EmployeesController::class)->group( function() {
            Route::resource('employees', EmployeesController::class);
            Route::get('employees-list',  'list')->name('employees.list');
            Route::post('approve-onboarding',  'approveOnboarding')->name('approve-onboarding');
            Route::post('onboarding-invitation',  'sendOnboardingInvitation')->name('send-onboarding-invitation');
            Route::post('assign-roles', 'assignRoles')->name('employees.assignRoles');
        });

        Route::get('work-reports', [WorkReportController::class, 'index'])->name('work-report.index');
        Route::get('edit-work-reports/{id}', [WorkReportController::class, 'edit'])->name('work-report.edit');
        Route::post('update-work-reports', [WorkReportController::class, 'update'])->name('work-report.update');
        Route::delete('delete-work-reports/{task_id}', [WorkReportController::class, 'delete'])->name('work-report.delete');
        Route::get('employee-work-reports/{emp_id}', [WorkReportController::class, 'employeeTaskListById'])->name('employee.work-report');

        Route::prefix('tenants')->controller(TenantController::class)->name('tenant.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/show/{id}', 'show')->name('show');
            Route::get('/add', 'create')->name('create');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::put('/update', 'update')->name('update');
        });



        Route::get('/leaves', [LeaveIndexController::class, 'index'])->name('leaves.index');
        Route::get('/leaves/create', [LeaveCreateController::class, 'create'])->name('leaves.create');
        Route::post('/leaves', [LeaveCreateController::class, 'store'])->name('leaves.store');
        
        Route::get('/leaves/{leave}', [LeaveViewController::class, 'show'])->name('leaves.show');
        Route::get('/leaves/{leave}/document', [LeaveViewController::class, 'viewDocument'])
            ->name('leaves.document.view')
            ->middleware(['signed']);
        
        Route::get('/leaves/{leave}/edit', [LeaveEditController::class, 'edit'])->name('leaves.edit');
        Route::put('/leaves/{leave}', [LeaveEditController::class, 'update'])->name('leaves.update');
        
        /*APPROVAL FLOW */
        Route::get('/leaves/{leave}/approve', [LeaveApprovalController::class, 'edit'])
            ->name('leaves.approve.edit');
        
        Route::post('/leaves/{leave}/approve', [LeaveApprovalController::class, 'update'])
            ->name('leaves.approve');
        
        Route::post('/leaves/{leave}/reject', [LeaveApprovalController::class, 'update'])
            ->name('leaves.reject');
        
        Route::post('/leaves/{leave}/cancel', [LeaveApprovalController::class, 'update'])
            ->name('leaves.cancel');
        
        Route::delete('/leaves/{leave}', [LeaveDeleteController::class, 'destroy'])
            ->name('leaves.destroy');


        
        //leave type
        Route::resource('leave-type', LeaveTypeController::class);
        Route::resource('leave-balances', LeaveBalanceController::class);

        // =============== Shift management routes =================== //
        Route::get('shift', [ShiftManagementController::class, 'index'])->name('shift.index');
        Route::get('shift-list', [ShiftManagementController::class, 'list'])->name('shift.list');
        Route::get('shift/create', [ShiftManagementController::class, 'create'])->name('shift.create');
        Route::post('shift/store', [ShiftManagementController::class, 'store'])->name('shift.store');
        Route::get('shift/{shift}/edit', [ShiftManagementController::class, 'edit'])->name('shift.edit');
        Route::put('shift/{shift}', [ShiftManagementController::class, 'update'])->name('shift.update');
        Route::delete('shift/{shift}', [ShiftManagementController::class, 'destroy'])->name('shift.destroy');
        Route::get('shift/{shift}/employees', [ShiftManagementController::class, 'employees'])->name('shifts.employees');
        Route::get('shift/{shift}/add-remove-employees', [ShiftManagementController::class, 'addRemoveEmployeeView'])->name('shift.add-remove-employee-view');
        Route::post('shift/add-remove-employees', [ShiftManagementController::class, 'addRemoveEmployee'])->name('shift.add-remove-employee');

        // =============== Switch role route =================== //
        Route::post('/switch-role', [DashboardController::class, 'switchRole'])->name('switch.role');
    });

    Route::any('logout', [AuthController::class, 'logout'])->name('logout');

    Route::prefix('onboarding')->controller(OnboardController::class)->group(function () {
        Route::get('/start/{user_id}/{code}', 'onboardingStart')->name('onboard.start');
        Route::get('/', 'index')->name('onboard');
        Route::post('/accept-terms', 'acceptTermCondition')->name('onboard.accept-terms');
        Route::post('/personal-details', 'savePersonalDetails')->name('onboard.personal-details');
        Route::post('/identity/{user_id?}', 'saveIdentityDetails')->name('onboard.identity');
        Route::post('/delete-identity', 'deleteIdentityId')->name('onboard.deleteIdentityId');
        Route::post('/emp-educations', 'saveEducationalDetails')->name('onboard.educations');
        Route::post('/delete-education', 'deleteEducation')->name('onboard.deleteEducation');
        Route::post('/employements', 'saveEmployementDetails')->name('onboard.employement');
        Route::post('/delete-employement/{employement_id?}', 'deleteEmployement')->name('onboard.deleteEmployement');
        Route::get('/welcome/{user_id?}', 'onboardingWelcome')->name('onboard.welcome');

    });
    


});

// Razorpay routes
Route::get('razorpay-payment', [RazorpayPaymentController::class, 'index'])->name('razorpay.payment.view');
Route::post('razorpay-payment', [RazorpayPaymentController::class, 'store'])->name('razorpay.payment.store');
Route::post('create-order', [RazorpayPaymentController::class, 'createOrder'])->name('razorpay.order.create');

// Change Currency routes
// Route::post('/currency-switch', [FrontController::class, 'switchCurrency'])->name('currency.switch');
Route::post('/currency-switch', function () {
    $currency = request('currency');
    Cache::forget("currency_rate_INR_{$currency}");
    session(['currency' => $currency]);
    return back();
});

// 404 route

Route::fallback(function () {
    return view('404');
});

