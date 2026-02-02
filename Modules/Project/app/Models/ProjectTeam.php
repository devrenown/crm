<?php

namespace Modules\Project\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Modules\Project\Database\Factories\ProjectTeamFactory;
use App\Traits\BelongsToTenant;

class ProjectTeam extends Model
{
    use HasFactory, BelongsToTenant;

    /**
     * The attributes that are mass assignable.
     */
    protected $fillable = [
        'tenant_id','project_id','user_id',
    ];

    public function project(){
        return $this->belongsTo(Project::class,'project_id');
    }

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }

}
