<?php

namespace Modules\Project\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Project\Database\Factories\TaskBoardFactory;
use App\Traits\BelongsToTenant;

class TaskBoard extends Model
{
    use HasFactory, BelongsToTenant;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'tenant_id','name','color','priority','created_by'
    ];

    public function createdBy(){
        return $this->belongsTo(User::class, 'created_by');
    }


    public function tasks()
    {
        return $this->hasMany(Task::class);
    }

}
