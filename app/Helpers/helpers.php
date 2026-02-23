<?php

use Illuminate\Support\Facades\Auth;

if (! function_exists('activeRole')) {
    function activeRole()
    {
        if (! Auth::check()) {
            return null;
        }

        return session('active_role')
            ?? Auth::user()->getRoleNames()->first();
    }
}

function activeRoleCan(string $permission): bool
{
    if (!auth()->check()) return false;

    $role = activeRole();
    if (!$role) return false;

    return auth()->user()
        ->roles()
        ->where('name', $role)
        ->first()
        ?->hasPermissionTo($permission) ?? false;
}

