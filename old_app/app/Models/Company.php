<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class Company extends Model
{
    use HasFactory, BelongsToTenant;

    protected $tableName = "companies";

    protected $primaryKey = "id";

    protected $fillable = ['name', 'tenant_id'];
}
