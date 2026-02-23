<?php

namespace App\Models;

use App\Enums\CalendarColors;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToTenant;

class Holiday extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'name', 'tenant_id', 'startDate','endDate','description','is_annual','color' 
    ];
    
    // protected $casts = [
    //     'color' => CalendarColors::class,
    // ];
}   
