<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Route;

class PlanRestriction
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, ?string $permission = null): Response
    {
        $tenant = app('tenant');

        if ($tenant && $tenant->id == 1) {
            return $next($request);
        }

        $plan           = $tenant->currentSubscription->plan;
        $subscription   = $tenant->currentSubscription;

        $currentDate = now();

        $routeName = $request->route()->getName();

        if ($currentDate->greaterThan($subscription->end_date)) {
            $notification = notify('Your plan has been expired. Please upgrade your plan.');
            return redirect()->route('subscription.upgrade')->with($notification);
        }

        $features = json_decode($plan->features, true) ?? [];

        $route = Route::currentRouteName();
        $module = $this->detectModule($route);

        // dd($features['modules'][$module]);

        if ($module && empty($features['modules'][$module])) {

            $notification = notify("Your plan does not allow access to the {$module} module.");
            return back()->with($notification);
        }

        if ($permission && auth()->check()) {
            if (!activeRoleCan($permission)) {
                abort(403, 'Unauthorized');
            }
        }

        return $next($request);
    }

    private function detectModule($route)
    {
        return match (true) {
            str_starts_with($route, "employees.")           => "employees",
            str_starts_with($route, "attendances.")         => "employees",
            str_starts_with($route, "attendance.details")   => "employees",
            str_starts_with($route, "departments.")         => "employees",
            str_starts_with($route, "designations.")        => "employees",
            str_starts_with($route, "holidays.")            => "employees",
            str_starts_with($route, "holidays.calendar")    => "employees",
            str_starts_with($route, "work-report.")         => "employees",
            str_starts_with($route, "employee.work-report") => "employees",

            str_starts_with($route, "reporting-managers")   => "reporting_managers",
            str_starts_with($route, "reporting-manager.")   => "reporting_managers",
            str_starts_with($route, "app.chat")             => "chat",
            str_starts_with($route, "projects.")            => "projects",
            str_starts_with($route, "task-boards.")         => "projects",
            str_starts_with($route, "project-tasks.")       => "projects",
            str_starts_with($route, "clients.")             => "clients",
            str_starts_with($route, "tickets.")             => "tickets",
            str_starts_with($route, "taxes.")               => "sales",
            str_starts_with($route, "expenses.")            => "sales",
            str_starts_with($route, "estimates.")           => "sales",
            str_starts_with($route, "invoices.")            => "sales",
            str_starts_with($route, "assets-list.")         => "assets",
            str_starts_with($route, "payroll.")             => "payroll",
            str_starts_with($route, "allowances.")          => "payroll",
            str_starts_with($route, "deductions.")          => "payroll",
            str_starts_with($route, "payslips.")            => "payroll",
            str_starts_with($route, "accounting.")          => "accounting",
            str_starts_with($route, "budget.categories.")   => "accounting",
            str_starts_with($route, "budget.expense.")      => "accounting",
            str_starts_with($route, "budget.revenue.")      => "accounting",
            str_starts_with($route, "budgets.")             => "accounting",
            str_starts_with($route, "leaves.")              => "leave",
            str_starts_with($route, "roles.")               => "roles",
            str_starts_with($route, "permissions.")         => "permissions",
            str_starts_with($route, "tenant.")              => "Organization",
            default => null,
        };
    }
}
