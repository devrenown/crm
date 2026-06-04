<?php

namespace App\Services;

use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Spatie\Permission\PermissionRegistrar;

class TenantDefaultPermissions
{
    public static function configure(int $tenantId): void
    {
        // dd($tenantId);
        app(PermissionRegistrar::class)
            ->setPermissionsTeamId($tenantId);

        app(PermissionRegistrar::class)
            ->forgetCachedPermissions();

        self::createRoles($tenantId);

        // Create permissions first
        self::createPermissions($tenantId);

        // Then assign them
        self::assignPermissions($tenantId);

        // assign role to admin user
        self::assignRoleToAdminUser($tenantId);

        app(PermissionRegistrar::class)
            ->forgetCachedPermissions();
    }

    private static function createRoles(int $tenantId): void
    {
        $roles = [
            'Admin',
            'Hr',
            'Manager',
            'Tl',
            'Employee',
            'Accountant',
            'Client',
        ];

        foreach ($roles as $roleName) {
            Role::withoutGlobalScopes()->firstOrCreate([
                'tenant_id'  => $tenantId,
                'name'       => $roleName,
                'guard_name' => 'web',
            ]);
        }
    }

    private static function createPermissions(int $tenantId): void
    {
        $masterPermissions = Permission::withoutGlobalScopes()
            ->where('tenant_id', 1)
            ->get();

        foreach ($masterPermissions as $permission) {
            Permission::withoutGlobalScopes()->firstOrCreate(
                [
                    'tenant_id' => $tenantId,
                    'name'      => $permission->name,
                ],
                [
                    'module'        => $permission->module,
                    'category_name' => $permission->category_name,
                    'guard_name'    => $permission->guard_name,
                ]
            );
        }
    }

    private static function assignPermissions(int $tenantId): void
    {
        $roles = [
            'Admin' => Permission::withoutGlobalScopes()
                ->where('tenant_id', $tenantId)
                ->whereNotIn('module', ['Organization', 'backups'])
                ->whereNotIn('name', ['create-role', 'edit-role', 'delete-role', 'create-permission', 'delete-permission'])
                ->pluck('name')
                ->toArray(),

            'Hr' => [
                'view-assets',
                'create-asset',
                'edit-asset',
                'view-employees',
                'create-employee',
                'edit-employee',
                'show-Employeeprofile',
                'view-attendances',
                'create-attendance',
                'edit-attendance',
                'view-leave',
                'create-leave',
                'edit-leave',
                'view-leave-type',
                'create-leave-type',
                'edit-leave-type',
                'approve-leave',
                'approve-team-leave',
                'approve-all-leave',
                'view-all-leave',
                'view-leave-summary',
                'view-leave-request-summary',
                'view-leave-balance',
                'edit-leave-balance',
                'create-leave-balance',
                'view-payrolls',
                'create-payroll',
                'edit-payroll',
                'view-PayrollAllowances',
                'create-PayrollAllowance',
                'edit-PayrollAllowance',
                'view-PayrollDeductions',
                'create-PayrollDeduction',
                'edit-PayrollDeduction',
                'view-payslips',
                'create-payslip',
                'edit-payslip',
                'view-holidays',
                'create-holiday',
                'edit-holiday',
                'delete-holiday',
                'view-departments',
                'create-department',
                'edit-department',
                'view-designations',
                'create-designation',
                'edit-designation',
                'view-onboarding',
            ],

            'Manager' => [
                'view-employees',
                'edit-employee',
                'view-projects',
                'create-project',
                'edit-project',
                'show-project',
                'view-tasks',
                'create-task',
                'edit-task',
                'show-task',
                'view-taskboards',
                'create-taskboard',
                'edit-taskboard',
                'view-work-tasks',
                'create-work-task',
                'edit-work-task',
                'update-work-task',
                'view-tickets',
                'create-ticket',
                'edit-ticket',
            ],

            'Tl' => [
                'create-attendance',
                'view-attendances',
                'view-employees',
                'edit-employee',
                'view-calendar',
                'view-work-tasks',
                'edit-work-task',
                'view-tasks',
                'create-task',
                'edit-task',
                'delete-task',
                'update-work-task',
                'view-team-leave',
                'view-projects',
                'create-project',
                'edit-project',
                'approve-team-leave',
                'view-leave',
                'edit-leave',
                'view-leave-type',
                'view-team-leave',
                'view-leave-summary',
                'view-leave-request-summary',
                'approve-leave',
                'approve-team-leave',
                'view-tickets',
                'create-ticket',
                'edit-ticket',
            ],

            'Employee' => [
                'create-attendance',
                'view-calendar',
                'view-work-tasks',
                'update-work-task',
                'view-leave',
                'create-leave',
                'edit-leave',
                'delete-leave',
                'view-calendar',
                'view-holidays',
                'view-leave-summary',
                'view-onboarding',
                'view-payslips',
                'view-tasks',
                'view-tickets',
                'create-ticket',
                'edit-ticket',
                'create-work-task',
                'view-work-tasks',
                'view-projects',
                'show-project',
            ],

            'Accountant' => [
                'view-payrolls',
                'create-payroll',
                'edit-payroll',
                'view-payslips',
                'view-estimates',
                'create-estimate',
                'edit-estimate',
                'view-expenses',
                'create-expense',
                'edit-expense',
                'view-invoices',
                'create-invoice',
                'edit-invoice',
                'view-taxs',
                'create-tax',
                'edit-tax',
                'delete-tax',
                'view-assets',
                'create-asset',
                'edit-asset',
                'create-budget',
                'view-budgets',
                'edit-budget',
                'view-budgetCategories',
                'create-budgetCategory',
                'edit-budgetCategory',
                'view-budgetExpenses',
                'create-budgetExpense',
                'edit-budgetExpense',
                'view-budgetRevenues',
                'create-budgetRevenue',
                'edit-budgetRevenue',
            ],

            'Client' => [
                'view-projects',
                'show-project',
                'view-tickets',
                'create-ticket',
                'edit-ticket',
                'delete-ticket',
                'view-invoices',
                'view-estimates',
            ],
        ];

        foreach ($roles as $roleName => $permissions) {

            $role = Role::withoutGlobalScopes()
                ->where('tenant_id', $tenantId)
                ->where('name', $roleName)
                ->first();

            if (! $role) {
                continue;
            }

            $tenantPermissions = Permission::withoutGlobalScopes()
                ->where('tenant_id', $tenantId)
                ->whereIn('name', $permissions)
                ->get();

            $role->syncPermissions($tenantPermissions);
        }

    }

    private static function assignRoleToAdminUser(int $tenantId): void
    {
        $adminUser = User::withoutGlobalScopes()
            ->where('tenant_id', $tenantId)
            ->where('type', 'ADMIN')
            ->first();

        if (! $adminUser) {
            return;
        }

        app(PermissionRegistrar::class)
            ->setPermissionsTeamId($tenantId);

        $adminRole = Role::withoutGlobalScopes()
            ->where('tenant_id', $tenantId)
            ->where('name', 'Admin')
            ->first();

        if ($adminRole) {

            // Prevent duplicate assignments
            if (! $adminUser->hasRole($adminRole)) {
                $adminUser->assignRole($adminRole);
            }

        }

        app(PermissionRegistrar::class)
            ->forgetCachedPermissions();
    }

}