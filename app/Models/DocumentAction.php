<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class DocumentAction extends Model
{
    use HasFactory;
    
    protected $table = 'document_actions';
    protected $primaryKey = 'id';

    protected $fillable = ['user_id', 'action_by', 'documentable_type', 'documentable_id', 'document_type', 'status', 'remark', 'action_at'];

    public function documentable()
    {
        return $this->morphTo();
    }

    public function actionBy () 
    {
        return $this->belongsTo(User::class, 'action_by');
    }
}
