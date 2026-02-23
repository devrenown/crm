<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToTenant;
use App\Models\User;
use App\Enums\UserType;

class LeaveType extends Model
{
    use HasFactory, BelongsToTenant, SoftDeletes;

    protected $table = 'leave_types';
    protected $primaryKey = 'id';

    protected $fillable = [
        'tenant_id',
        'name',
        'description',

        // Leave rules
        'is_paid',
        'is_active',
        'max_days_per_year',
        'carry_forward',
        'max_carry_forward',
        'monthly_accrual',
        'accrual_rate',
        'requires_document',
        'min_days_notice',
        'max_days_per_application',
        'gender',
        'is_encashable',

        // Approval config
        'requires_l2_approval',
        'l2_roles',
    ];

    protected $casts = [
        // Booleans
        'is_paid'               => 'boolean',
        'is_active'             => 'boolean',
        'carry_forward'         => 'boolean',
        'monthly_accrual'       => 'boolean',
        'requires_document'     => 'boolean',
        'is_encashable'         => 'boolean',
        'requires_l2_approval'  => 'boolean',

        // Numbers
        'max_days_per_year'        => 'integer',
        'max_carry_forward'        => 'integer',
        'min_days_notice'          => 'integer',
        'max_days_per_application' => 'integer',
        'gender'                   => 'integer',
        'accrual_rate'             => 'float',

        
        'l2_roles' => 'array',
    ];

    /*
    |--------------------------------------------------------------------------
    | Relationships
    |--------------------------------------------------------------------------
    */

    public function leaveRequests()
    {
        return $this->hasMany(LeaveRequest::class);
    }

    /*
    |--------------------------------------------------------------------------
    | Approval Helpers (Business Logic)
    |--------------------------------------------------------------------------
    */

    /**
     * Is L2 approval required?
     */
    public function needsL2Approval(): bool
    {
        return $this->requires_l2_approval === true;
    }

}
