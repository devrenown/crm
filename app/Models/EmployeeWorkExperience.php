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
        'offer_letter_mime',
        'offer_remarks',
        'offer_status',
        'appointment_letter',
        'appointment_letter_mime',
        'appointment_remarks',
        'appointment_status',
        'experience_letter',
        'experience_letter_mime',
        'experience_remarks',
        'experience_status',
        'relieving_letter',
        'relieving_letter_mime',
        'relieving_remarks',
        'relieving_status',
        'increment_letter',
        'increment_letter_mime',
        'increment_remarks',
        'increment_status',
        'salary_slip',
        'salary_slip_mime',
        'salary_remarks',
        'salary_status',
        'bank_statement',
        'bank_statement_mime',
        'bank_remarks',
        'bank_status',
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
