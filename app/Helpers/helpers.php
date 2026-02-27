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

if (! function_exists('tz')) {

    function tz($date, $format = 'd M Y H:i')
    {
        if (!$date) return null;

        if (! $date instanceof \Carbon\Carbon) {
            $date = \Carbon\Carbon::parse($date);
        }

        return $date
            ->timezone(app('tenant_timezone'))
            ->format($format);
    }
}

