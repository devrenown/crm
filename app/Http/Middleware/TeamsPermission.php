<?php

namespace App\Http\Middleware;

use Closure;
use Spatie\Permission\PermissionRegistrar;

class TeamsPermission
{
    public function handle($request, Closure $next)
    {
        if (auth()->check()) {

            app(PermissionRegistrar::class)
                ->setPermissionsTeamId(auth()->user()->tenant_id);
        }

        return $next($request);
    }
}