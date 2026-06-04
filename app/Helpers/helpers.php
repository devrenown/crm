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


if (!function_exists('canViewHierarchy')) {
    function canViewHierarchy(): bool
    {
        if (!auth()->check()) return false;

        $tenantId = auth()->user()->tenant_id;
        $role = activeRole();
        if (!$role) return false;

        $roleModel = \Spatie\Permission\Models\Role::where('name', $role)
            ->where('tenant_id', $tenantId)
            ->first();

        if (!$roleModel) return false;

        $roleWithPerms = auth()->user()
            ->roles()
            ->where('id', $roleModel->id)
            ->first();

        if (!$roleWithPerms) return false;

        return $roleWithPerms->hasPermissionTo('view-hierarchy');
    }
}

/**
 * Check if the current active role can EDIT/MANAGE the hierarchy.
 * Permission name: edit-hierarchy
 */
if (!function_exists('canEditHierarchy')) {
    function canEditHierarchy(): bool
    {
        if (!auth()->check()) return false;

        $tenantId = auth()->user()->tenant_id;
        $role = activeRole();
        if (!$role) return false;

        $roleModel = \Spatie\Permission\Models\Role::where('name', $role)
            ->where('tenant_id', $tenantId)
            ->first();

        if (!$roleModel) return false;

        $roleWithPerms = auth()->user()
            ->roles()
            ->where('id', $roleModel->id)
            ->first();

        if (!$roleWithPerms) return false;

        return $roleWithPerms->hasPermissionTo('edit-hierarchy');
    }
}

/**
 * Check if the current active role can view their OWN reporting chain.
 */
if (!function_exists('canViewOwnHierarchy')) {
    function canViewOwnHierarchy(): bool
    {
        if (!auth()->check()) return false;

        $tenantId = auth()->user()->tenant_id;
        $role = activeRole();
        if (!$role) return false;

        $roleModel = \Spatie\Permission\Models\Role::where('name', $role)
            ->where('tenant_id', $tenantId)
            ->first();

        if (!$roleModel) return false;

        $roleWithPerms = auth()->user()
            ->roles()
            ->where('id', $roleModel->id)
            ->first();

        if (!$roleWithPerms) return false;

        return $roleWithPerms->hasPermissionTo('view-own-hierarchy');
    }
}

/**
 * Check if current active role can EDIT role-department permissions.
 * Permission name: edit-role-dept-permission
 */
if (!function_exists('canEditRoleDeptPermission')) {
    function canEditRoleDeptPermission(): bool
    {
        if (!auth()->check()) return false;

        $tenantId = auth()->user()->tenant_id;
        $role = activeRole();
        if (!$role) return false;

        $roleModel = \Spatie\Permission\Models\Role::where('name', $role)
            ->where('tenant_id', $tenantId)
            ->first();

        if (!$roleModel) return false;

        $roleWithPerms = auth()->user()
            ->roles()
            ->where('id', $roleModel->id)
            ->first();

        if (!$roleWithPerms) return false;

        return $roleWithPerms->hasPermissionTo('edit-role-dept-permission');
    }
}

/**
 * Get the allowed department IDs for the currently active role.
 * Returns null if all departments are accessible.
 */
if (!function_exists('getAllowedDepartmentsForActiveRole')) {
    function getAllowedDepartmentsForActiveRole(): ?array
    {
        if (!auth()->check()) return null;

        $tenantId = auth()->user()->tenant_id;
        $role = activeRole();
        if (!$role) return null;

        $roleModel = \Spatie\Permission\Models\Role::where('name', $role)
            ->where('tenant_id', $tenantId)
            ->first();

        if (!$roleModel) return null;

        return \App\Models\RoleDepartmentPermission::getAllowedDepartmentIds(
            $roleModel->id,
            $tenantId
        );
    }
}

/**
 * Check if current active role can see a specific department.
 */
if (!function_exists('activeRoleCanSeeDepartment')) {
    function activeRoleCanSeeDepartment(int $departmentId): bool
    {
        if (!auth()->check()) return false;

        $tenantId = auth()->user()->tenant_id;
        $role = activeRole();
        if (!$role) return true;

        $roleModel = \Spatie\Permission\Models\Role::where('name', $role)
            ->where('tenant_id', $tenantId)
            ->first();

        if (!$roleModel) return true;

        return \App\Models\RoleDepartmentPermission::canRoleSeeDepartment(
            $roleModel->id,
            $departmentId,
            $tenantId
        );
    }
}

