<?php

namespace App\Traits;

use App\Models\Scopes\TenantScope;

trait BelongsToTenant
{
    protected static function bootBelongsToTenant()
    {
        static::addGlobalScope(new TenantScope);

        static::creating(function ($model) {

            $tenant = app()->bound('tenant')
                ? app('tenant')
                : null;

            if ($tenant && empty($model->tenant_id)) {
                $model->tenant_id = $tenant->id;
            }
        });
    }
}