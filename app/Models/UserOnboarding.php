<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Traits\BelongsToTenant;

class UserOnboarding extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = ['user_id', 'tenant_id', 'type', 'status', 'term_accepted_at', 'invited_at', 'started_at', 'completed_at', 'remarks'];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
