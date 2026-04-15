<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class EmployeeIdentityProof extends Model
{
    use HasFactory, BelongsToTenant;

    protected $tableName = 'employee_identity_proofs';
    protected $primaryKey = 'id';
    protected $foreignKey = 'user_id';
    protected $fillable = ['user_id', 'tenant_id', 'id_name', 'id_type', 'id_number', 'image', 'document_mime', 'remarks', 'status',];
}
