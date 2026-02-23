<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class Subscription extends Model
{
    use HasFactory, BelongsToTenant;
    
    protected $table        = 'subscriptions';
    protected $primaryKey   = 'id';
    
    protected $fillable     = ['tenant_id', 'plan_id', 'start_date', 'end_date', 'status', 'razorpay_payment_id', 'razorpay_payment_payload'];

    public function plan ()
    {
        return $this->belongsTo(Plan::class, 'plan_id', 'id');
    }

    protected $casts = [
        'razorpay_payment_payload' => 'array',
    ];
}
