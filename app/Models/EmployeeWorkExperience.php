<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class EmployeeWorkExperience extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'employee_detail_id',
        'tenant_id',
        'company',
        'location',
        'position',
        'person_name',
        'person_contact',
        'employee_id',
        'leaving_reason',
        'offer_letter',
        'appointment_letter',
        'experience_letter',
        'relieving_letter',
        'increment_letter',
        'salary_slip',
        'bank_statement',
        'start_date',
        'end_date',
        'file',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date'
    ];

    public function getdateDifferenceAttribute()
    {
        if ($this->start_date) {
            return $this->end_date->diff($this->start_date);
        }

    }

    public function employee()
    {
        return $this->belongsTo(EmployeeDetail::class, 'employee_detail_id');
    }
}
