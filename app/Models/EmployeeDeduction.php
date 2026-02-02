<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class EmployeeDeduction extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'employee_detail_id', 'tenant_id', 'name','amount',
    ];

    public function employee()
    {
        return $this->belongsTo(EmployeeDetail::class, 'employee_detail_id');
    }
}
