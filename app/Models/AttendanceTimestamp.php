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
        // 'startTime' => 'datetime:H:i:s',
        // 'endTime' => 'datetime:H:i:s',
        'startTime' => 'datetime',
        'endTime' => 'datetime',
    ];

    public function getTotalMinutesAttribute(): int
    {
        if (!$this->startTime) return 0;

        $end = $this->endTime ?? now('UTC');

        return $this->startTime->diffInMinutes($end);
    }

    public function getTotalHoursAttribute(): string
    {
        $minutes = $this->total_minutes;

        return sprintf(
            '%02d:%02d',
            intdiv($minutes, 60),
            $minutes % 60
        );
    }

    // public function getTotalHoursAttribute()
    // {
    //     if (empty($this->endTime)) {
    //         return 0; // Not punched out yet
    //     }

    //     return $this->endTime->diff($this->startTime)->h;
    // }

    // public function getTotalMinutesAttribute()
    // {
    //     if (empty($this->endTime)) {
    //         return 0; // Not punched out yet
    //     }

    //     $totalMinutes = $this->startTime->diffInMinutes($this->endTime);
    //     $minutes = $totalMinutes % 60;

    //     return $minutes;
    // }

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
