<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class Designation extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'name', 'tenant_id', 'description'
    ];

    public static function list()
    {
        return self::orderBy('name')->get();
    }
}
