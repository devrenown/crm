<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class ChatMessage extends Model implements HasMedia
{
    use HasFactory, BelongsToTenant, InteractsWithMedia;

    protected $fillable = [
        'user_id', 'tenant_id', 'from_id','receiver_id','body','type',
        'is_read',
    ]; 

    public function fromUser()
    {
        return $this->belongsTo(User::class, 'from_id');
    }


    public function sender()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function receiver()
    {
        return $this->belongsTo(User::class, 'receiver_id');
    }
}
