<?php

namespace App\Models;
use App\Enums\TenantStatus;
use App\Enums\UserType;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Subscription;

class Tenant extends Model
{
    use HasFactory;
    
    protected $tableName = 'tenants';
    
    protected $primaryKey = 'id';
    
    protected $fillable = ['name', 'domain', 'size', 'status'];
    
    protected $casts = [
        'status' => TenantStatus::class,
    ];

    public function adminUser ()
    {
        return $this->hasOne(User::class, 'tenant_id')->withoutGlobalScopes()->where('type', UserType::ADMIN);
    }

    public function users()
    {
        return $this->hasMany(User::class, 'tenant_id', 'id')->withoutGlobalScopes();
    }

    public function employees()
    {
        return $this->hasMany(User::class, 'tenant_id', 'id')->withoutGlobalScopes()->where('type', UserType::EMPLOYEE);
    }

    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'tenant_id', 'id')->orderBy('id', 'desc');
    }

    public function currentSubscription()
    {
        return $this->hasOne(Subscription::class, 'tenant_id', 'id')->orderBy('id', 'desc');
    }

    public function currentSubscriptionWithoutGlobalScope()
    {
        return $this->hasOne(Subscription::class, 'tenant_id', 'id')->withoutGlobalScopes()->orderBy('id', 'desc');
    }

}
