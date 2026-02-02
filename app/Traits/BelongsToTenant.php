<?php

namespace App\Traits;
use App\Models\Scopes\TenantScope;
use Illuminate\Database\Eloquent\Model;

trait BelongsToTenant
{
    protected static function booted ()
    {
        static::addGlobalScope(new TenantScope);
        
        static::creating(function ($model) {

            $tenant = app()->bound('tenant') ? app('tenant') : null;

            if ($tenant && ! app()->bound('creating_tenant')) {
                $model->tenant_id = $tenant->id;
            }

        });
    }
}
