<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DemoRequest extends Model
{
    use HasFactory;

    protected $tableName = "demo_requests";

    protected $primaryKey = "id";

    protected $fillable = ['name', 'organization', 'size', 'email', 'contact', 'additional', 'status'];
}
