<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;
    
    protected $tableName = 'plans';
    protected $primaryKey = 'id';
    
    protected $fillable = ['name', 'price', 'features', 'duration', 'country', 'currency', 'currency_symbol'];
}
