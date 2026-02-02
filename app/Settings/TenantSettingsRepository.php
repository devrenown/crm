<?php

namespace App\Settings;

use Illuminate\Database\Eloquent\Builder;
use Spatie\LaravelSettings\SettingsRepositories\DatabaseSettingsRepository;

class TenantSettingsRepository extends DatabaseSettingsRepository
{
    /**
     * MUST be public.
     * This loads tenant-specific settings OR global fallback.
     */
    public function getBuilder(): Builder
    {
        $model = new $this->propertyModel;

        if ($this->connection) {
            $model->setConnection($this->connection);
        }

        if ($this->table) {
            $model->setTable($this->table);
        }

        $builder = $model->newQuery();

        // TENANT LOADING LOGIC
        if (app()->has('tenant') && ($tenant = app('tenant'))) {
            $tenantId = $tenant->id;

            $builder->where(function ($q) use ($tenantId) {
                $q->where('tenant_id', $tenantId)   
                  ->orWhereNull('tenant_id');     
            });
        }

        return $builder;
    }

    /**
     * Save settings for the current tenant ONLY.
     * tenant_id IS REQUIRED.
     */
    public function updatePropertiesPayload(string $group, array $properties): void
    {
        $tenant = app('tenant');

        if (!$tenant) {
            throw new \Exception("Tenant ID is required to save settings.");
        }

        $tenantId = $tenant->id;

        $propertiesInBatch = collect($properties)->map(function ($value, $name) use ($group, $tenantId) {
            return [
                'group'      => $group,
                'name'       => $name,
                'payload'    => json_encode($value),
                'tenant_id'  => $tenantId,
                'updated_at' => now(),
            ];
        })->values()->toArray();

        // UPSERT with tenant_id as part of the unique key
        $this->getBuilder()
            ->where('tenant_id', $tenantId)
            ->where('group', $group)
            ->upsert(
                $propertiesInBatch,
                ['tenant_id', 'group', 'name'],
                ['payload', 'updated_at']
            );
    }
}
