<?php

namespace App\Models;

use App\Enums\TicketStatus;
use App\Models\TicketReply;
use App\Enums\GeneralPriority;
use Spatie\MediaLibrary\HasMedia;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Traits\BelongsToTenant;
use App\Enums\UserType;

class Ticket extends Model implements HasMedia
{
    use HasFactory, InteractsWithMedia, BelongsToTenant;

    protected $fillable = [
        'tk_id', 'tenant_id', 'subject','created_by','user_id',
        'description','status','priority','endDate'
    ];

    protected $casts = [
        'priority' => GeneralPriority::class,
        'status'   => TicketStatus::class,
    ];

    public function user()
    {
        if (activeRole() === UserType::SUPERADMIN->value) {
           return $this->belongsTo(User::class, 'user_id')->withoutGlobalScopes(); 
        }
        return $this->belongsTo(User::class, 'user_id');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function replies()
    {
        return $this->hasMany(TicketReply::class);
    }
}
