<?php

namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Vite;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;
use LaravelLang\Routes\Events\LocaleHasBeenSetEvent;
use App\Extensions\CustomDatabaseSessionHandler;
use Spatie\Menu\Laravel\Menu;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Blade;


class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Gate::before(function ($user, $ability) {
            return $user->hasRole('Super Admin') ? true : null;
        });
        Event::listen(static function (LocaleHasBeenSetEvent $event) {
            $lang = $event->locale->code;
            Log::info('Locale set to: ' . $lang);
        });

        \App\Models\Tenant::observe(\App\Observers\TenantObserver::class);

        Menu::macro('canForActiveRole', function ($permission, $item) {
            if (! Auth::check()) {
                return $this;
            }

            $user = Auth::user();
            $role = activeRole();

            if (! $role) {
                return $this;
            }

            // Check permission ONLY for active role
            $hasPermission = $user
                ->roles()
                ->where('name', $role)
                ->first()
                ?->hasPermissionTo($permission);

            if ($hasPermission) {
                $this->add($item);
            }

            return $this;
        });

        Menu::macro('canAnyForActiveRole', function (array $permissions, $callback) {
            if (! Auth::check()) {
                return $this;
            }

            $role = activeRole();

            if (! $role) {
                return $this;
            }

            $roleModel = Auth::user()
                ->roles()
                ->where('name', $role)
                ->first();

            if (! $roleModel) {
                return $this;
            }

            foreach ($permissions as $permission) {
                if ($roleModel->hasPermissionTo($permission)) {
                    $callback($this);
                    break;
                }
            }

            return $this;
        });

        Blade::if('activeCan', function ($permission) {
            return activeRoleCan($permission);
        });

        Blade::if('activeAnyCan', function (array $permissions) {
        foreach ($permissions as $permission) {
            if (activeRoleCan($permission)) {
                return true;
            }
        }
            return false;
        });

        Blade::if('activeRole', function ($role) {
            return activeRole() === $role;
        });

        Blade::if('activeRoleIn', function (array $roles) {
            return in_array(activeRole(), $roles);
        });
    }
}
