<?php

namespace App\Models;

use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToTenant;

class AttendanceTimestamp extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'user_id', 'tenant_id', 'attendance_id','project_id','startTime','endTime','location',
        'billable','ip','note'
    ];

    protected $casts = [
        'startTime' => 'datetime:H:i:s',
        'endTime' => 'datetime:H:i:s',
    ];

    // public function getTotalHoursAttribute()
    // {
    //     return !empty($this->endTime) ? $this->endTime->diff($this->startTime)->hour: now()->diff($this->startTime)->hour;
    // }

    // public function getTotalMinutesAttribute()
    // {
    //     $end = $this->endTime ?? now();
    //     $totalMinutes = $this->startTime->diffInMinutes($end);

    //     $minutes = $totalMinutes % 60;
    //     return $minutes;
    // }

    public function getTotalHoursAttribute()
    {
        if (empty($this->endTime)) {
            return 0; // Not punched out yet
        }

        return $this->endTime->diff($this->startTime)->h;
    }

    public function getTotalMinutesAttribute()
    {
        if (empty($this->endTime)) {
            return 0; // Not punched out yet
        }

        $totalMinutes = $this->startTime->diffInMinutes($this->endTime);
        $minutes = $totalMinutes % 60;

        return $minutes;
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function attendance()
    {
        return $this->belongsTo(Attendance::class, 'attendance_id');
    }

    public function project(){
        return $this->belongsTo(\Modules\Project\Models\Project::class, 'project_id');
    }
}
