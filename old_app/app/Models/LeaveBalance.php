<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\BelongsToTenant;

class LeaveBalance extends Model
{
    use HasFactory, BelongsToTenant, SoftDeletes;

    protected $table = "leave_balances";
    protected $primaryKey = 'id';

    protected $fillable = [
        'tenant_id',
        'user_id',
        'leave_type_id',
        'year',
        'opening_balance',
        'accrued_leaves',
        'carry_forwarded',
        'used_leaves',
        'encashed_leaves',
        'manual_adjustment',
        'adjustment_reason',
        'remaining_leaves',
        'last_accrued_month',
	    'carry_forwarded_from_year',
    ];

    protected $casts = [
        'opening_balance'    => 'float',
        'accrued_leaves'     => 'float',
        'carry_forwarded'    => 'float',
        'used_leaves'        => 'float',
        'encashed_leaves'    => 'float',
        'manual_adjustment'  => 'float',
        'remaining_leaves'   => 'float',
        'year'               => 'integer'
    ];

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function leaveType()
    {
        return $this->belongsTo(LeaveType::class);
    }
}
