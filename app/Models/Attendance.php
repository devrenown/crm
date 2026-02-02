<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class Attendance extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'user_id', 'tenant_id', 'startDate','endDate', 'location', 'platform',
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

    public function timestamps(){
        return $this->hasMany(AttendanceTimestamp::class);
    }

}
