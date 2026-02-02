<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Services\TenantService;
use Carbon\Carbon;
use DateTimeZone;

class SetTenantTimezone
{
    public function handle($request, Closure $next)
    {
        if (Auth::check()) {
            $tenantId = Auth::user()->tenant_id;

            //dd(config('app.timezone'));

            if ($tenantId) {
                $timezone = TenantService::timezone($tenantId);

                // Set Laravel timezone
                config(['app.timezone' => $timezone]);

                // Set PHP & Carbon timezone
                date_default_timezone_set($timezone);
                Carbon::setLocale(config('app.locale'));
            }
        }

        return $next($request);
    }
}
