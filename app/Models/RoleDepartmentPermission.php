<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class RoleDepartmentPermission extends Model
{
    use HasFactory, BelongsToTenant;

    protected $table = 'role_department_permissions';

    protected $fillable = [
        'tenant_id',
        'role_id',
        'visibility_type',   // 'all_departments' or 'assigned_departments'
    ];

    protected $casts = [
        'visibility_type' => 'string',
    ];

    /* ------------------------------------------------------------------
     *  Relationships
     * ------------------------------------------------------------------ */

    public function role()
    {
        return $this->belongsTo(\Spatie\Permission\Models\Role::class, 'role_id');
    }

    public function departmentAssignments()
    {
        return $this->hasMany(RoleDepartmentAssignment::class, 'role_id', 'role_id')
                    ->where('tenant_id', $this->tenant_id);
    }

    /* ------------------------------------------------------------------
     *  Scopes
     * ------------------------------------------------------------------ */

    public function scopeForTenant($query, int $tenantId)
    {
        return $query->where('tenant_id', $tenantId);
    }

    public function scopeForRole($query, int $roleId)
    {
        return $query->where('role_id', $roleId);
    }

    /* ------------------------------------------------------------------
     *  Helpers
     * ------------------------------------------------------------------ */

    /**
     * Check if a given role (by ID) can see a specific department.
     * Returns true if:
     *   - No permission row exists (default: allow all)
     *   - visibility_type = 'all_departments'
     *   - visibility_type = 'assigned_departments' AND department is in assignments
     */
    public static function canRoleSeeDepartment(int $roleId, int $departmentId, int $tenantId): bool
    {
        $perm = static::where('role_id', $roleId)
                       ->where('tenant_id', $tenantId)
                       ->first();

        // No restriction record => allow all (backward compatible)
        if (!$perm) {
            return true;
        }

        if ($perm->visibility_type === 'all_departments') {
            return true;
        }

        // Check assigned departments
        return RoleDepartmentAssignment::where('role_id', $roleId)
            ->where('department_id', $departmentId)
            ->where('tenant_id', $tenantId)
            ->exists();
    }

    /**
     * Get allowed department IDs for a role.
     * Returns null if 'all_departments' or no restriction,
     * otherwise returns array of department IDs.
     */
    public static function getAllowedDepartmentIds(int $roleId, int $tenantId): ?array
    {
        $perm = static::where('role_id', $roleId)
                       ->where('tenant_id', $tenantId)
                       ->first();

        if (!$perm || $perm->visibility_type === 'all_departments') {
            return null; // null = all departments allowed
        }

        return RoleDepartmentAssignment::where('role_id', $roleId)
            ->where('tenant_id', $tenantId)
            ->pluck('department_id')
            ->toArray();
    }
}
