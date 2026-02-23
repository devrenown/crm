<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Enums\TaskStatus;
use Modules\Project\Models\Project;
use App\Models\User;
use App\Traits\BelongsToTenant;

class WorkReport extends Model
{
    use HasFactory, BelongsToTenant;

    protected $tableName = 'work_reports';

    protected $primaryKey = 'id';

    protected $fillable = ['user_id', 'tenant_id', 'project_id', 'title', 'description', 'status'];


    protected $casts = [
        'status' => TaskStatus::class,
    ];

    public function user () 
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function project ()
    {
        return $this->belongsTo(Project::class, 'project_id', 'id');
    }
}
