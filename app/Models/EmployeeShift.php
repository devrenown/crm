<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;
use App\Models\Shift;
use App\Models\User;

class EmployeeShift extends Model
{
    use HasFactory, BelongsToTenant;

    protected $table = 'employee_shifts';

    protected $fillable = ['tenant_id', 'user_id', 'shift_id'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function shift()
    {
        return $this->belongsTo(Shift::class);
    }
}
