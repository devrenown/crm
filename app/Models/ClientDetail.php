<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\BelongsToTenant;

class ClientDetail extends Model
{
    use HasFactory, BelongsToTenant;

    protected $fillable = [
        'user_id', 'tenant_id', 'clt_id','billing_address','post_address'
    ];

    public function user(){
        return $this->belongsTo(User::class, 'user_id');
    }


}
