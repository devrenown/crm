<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class CustomMedia extends Media
{
    protected static function booted()
    {
        static::creating(function ($media) {
            if (app()->bound('tenant') && app('tenant')) {
                $media->tenant_id = app('tenant')->id;
            }
        });
        
        static::addGlobalScope('tenant', function (Builder $query) {
            if (app()->bound('tenant') && app('tenant')) {
                $query->where('tenant_id', app('tenant')->id);
            }
        });
    }
}
