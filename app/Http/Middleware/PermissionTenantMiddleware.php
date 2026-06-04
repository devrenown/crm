<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Spatie\Permission\PermissionRegistrar;

class PermissionTenantMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {

            setPermissionsTeamId(auth()->user()->tenant_id);

            app(PermissionRegistrar::class)
                ->forgetCachedPermissions();
        }

        return $next($request);
    }
}