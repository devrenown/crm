<?php

namespace App\Observers;

use App\Models\Tenant;
use App\Services\TenantDefaultSettings;

class TenantObserver
{
    public function created(Tenant $tenant)
    {
        // Create default settings for the new tenant
        TenantDefaultSettings::createDefaults($tenant->id);
    }
}
