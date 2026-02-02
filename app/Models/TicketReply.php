<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use App\Traits\BelongsToTenant;

class TicketReply extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, BelongsToTenant;

    protected $fillable = [
        'ticket_id', 'tenant_id', 'reply_id','message','created_by','is_read'
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class, 'ticket_id');
    }

    public function reply()
    {
        return $this->belongsTo(TicketReply::class, 'reply_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class,'created_by');
    }

    
}
