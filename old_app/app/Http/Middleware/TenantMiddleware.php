<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Tenant;
use App\Enums\TenantStatus;
use App\Services\TenantDefaultSettings;

class TenantMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $host = $request->getHost();
        $tenant = Tenant::where('domain', $host)->first();

        if (!$tenant && $host !== 'dev.renownsystem.com') {
            abort(404, 'No Organization Found !');
        }
        
        app()->instance('tenant', $tenant);

        if ($tenant->status === TenantStatus::INACTIVE || $tenant->status === TenantStatus::SUSPENDED) {

            $icon = $tenant->status === TenantStatus::INACTIVE
                    ? '⚠️'
                    : ($tenant->status === TenantStatus::SUSPENDED
                        ? '🔺'
                        : '');

            $title = $tenant->status === TenantStatus::INACTIVE
                    ? 'Account Inactive'
                    : ($tenant->status === TenantStatus::SUSPENDED
                        ? 'Account Suspended'
                        : '');

            $color = $tenant->status === TenantStatus::INACTIVE
                    ? '#FCE100'
                    : ($tenant->status === TenantStatus::SUSPENDED
                        ? '#F62D51'
                        : '');

            $desc = $tenant->status === TenantStatus::INACTIVE
                    ? 'Your account is not activated.<br>
                            Please contact support or your administrator.'
                    : ($tenant->status === TenantStatus::SUSPENDED
                        ? 'Your account is suspended.<br>
                            Please contact support or your administrator.'
                        : '');

            return response('
                <div style="
                    display: flex;
                    justify-content: center;
                    align-items: center;
                    height: 97vh;
                    font-family: Arial, sans-serif;
                ">
                    <div style="
                        background: #ffffff;
                        padding: 40px 30px;
                        border-radius: 10px;
                        width: 400px;
                        text-align: center;
                        box-shadow: 0 5px 20px rgba(0,0,0,0.15);
                        border-top: 4px solid '. $color .';
                    ">
                        <div style="font-size: 40px; color: #dc3545; margin-bottom: 15px;">
                            '. $icon .'
                        </div>
                        <h2 style="margin-bottom: 10px; color: '. $color .';">
                            '. $title .'
                        </h2>
                        <p style="color: #6c757d; font-size: 16px;">
                            '. $desc .'
                        </p>

                        <small style="display:block; margin-bottom:20px;">
                            Contact Support: 
                            <a href="mailto:info@crm.renownsystem.com" style="color:#007bff;">
                                info@crm.renownsystem.com
                            </a>
                        </small>

                        <a href="https://dev.renownsystem.com/" style="
                            background:#007bff;
                            color:white;
                            padding:10px 20px;
                            border-radius:6px;
                            text-decoration:none;
                            display:inline-block;
                        ">
                            Go to Home
                        </a>
                    </div>
                </div>
            ');
        }

        try {
            $theme = app(\App\Settings\ThemeSettings::class);
            $name = $theme->name; // triggers load
        } catch (\Exception $e) {

            // No settings exist → create defaults
            TenantDefaultSettings::createDefaults(app('tenant')->id);

            // Reload settings
            $theme = app(\App\Settings\ThemeSettings::class);
        }

        // if (auth()->check()) {
        //     setPermissionsTeamId(auth()->user()->tenant_id);
        // }

        return $next($request);
    }
}
