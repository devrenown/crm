<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Services\TenantService;

class SetTenantTimezone
{
    public function handle($request, Closure $next)
    {
        $timezone = config('app.timezone'); // fallback (UTC)

        if (Auth::check()) {
            $tenantTz = TenantService::timezone(Auth::user()->tenant_id);
            if ($tenantTz) {
                $timezone = $tenantTz;
            }
        }

        // ONLY store for display
        app()->instance('tenant_timezone', $timezone);

        return $next($request);
    }
}

// class SetTenantTimezone
// {
//     public function handle($request, Closure $next)
//     {
//         if (Auth::check()) {
//             app()->instance(
//                 'tenant_timezone',
//                 TenantService::timezone(Auth::user()->tenant_id)
//             );
//         }

//         return $next($request);
//     }
// }
