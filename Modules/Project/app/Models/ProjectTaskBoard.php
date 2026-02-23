<?php

namespace Modules\Project\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Project\Database\Factories\ProjectTaskBoardFactory;
use App\Traits\BelongsToTenant;

class ProjectTaskBoard extends Model
{
    use HasFactory, BelongsToTenant;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'tenant_id', 'project_id', 'name','color','priority','created_by'
    ];

    public function project()
    {
        return $this->belongsTo(Project::class, 'project_id');
    }

    public function createdBy(){
        return $this->belongsTo(User::class, 'created_by');
    }


    public function tasks()
    {
        return $this->hasMany(Task::class);
    }
}
