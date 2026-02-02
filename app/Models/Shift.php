<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;
use App\Models\EmployeeShift;

class Shift extends Model
{
    use HasFactory, BelongsToTenant;

    protected $table = 'shifts';

    protected $fillable = ['tenant_id', 'name', 'start_time', 'end_time', 'break_minutes', 'grace_minutes', 'status'];


    public function employeeShifts()
    {
        return $this->hasMany(EmployeeShift::class);
    }

    public function employees()
    {
        return $this->hasManyThrough(
            User::class,
            EmployeeShift::class,
            'shift_id',   // FK on employee_shifts
            'id',         // FK on users
            'id',         // local key shifts.id
            'user_id'     // local key employee_shifts.user_id
        );
    }
}