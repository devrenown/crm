<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class RoleDepartmentAssignment extends Model
{
    use HasFactory, BelongsToTenant;

    protected $table = 'role_department_assignments';

    protected $fillable = [
        'tenant_id',
        'role_id',
        'department_id',
    ];

    /* ------------------------------------------------------------------
     *  Relationships
     * ------------------------------------------------------------------ */

    public function role()
    {
        return $this->belongsTo(\Spatie\Permission\Models\Role::class, 'role_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class, 'department_id');
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
}
