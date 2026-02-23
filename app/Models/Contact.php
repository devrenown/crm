<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class Contact extends Model
{
    use HasFactory, BelongsToTenant;

    protected $tableName = 'contacts';

    protected $primaryKey = 'id';

    protected $fillable = ['name', 'tenant_id', 'email', 'phone', 'message', 'status'];
}
