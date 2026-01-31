<?php

namespace App\Providers;

use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Permission Blade Directives
        Blade::if('haspermission', function ($permission) {
            return auth()->check() && auth()->user()->hasPermission($permission);
        });

        Blade::if('hasanypermission', function ($permissions) {
            if (!is_array($permissions)) {
                $permissions = explode(',', $permissions);
            }
            return auth()->check() && auth()->user()->hasAnyPermission($permissions);
        });

        Blade::if('hasallpermissions', function ($permissions) {
            if (!is_array($permissions)) {
                $permissions = explode(',', $permissions);
            }
            return auth()->check() && auth()->user()->hasAllPermissions($permissions);
        });

        Blade::directive('permission', function ($permission) {
            return "<?php if(auth()->check() && auth()->user()->hasPermission($permission)): ?>";
        });

        Blade::directive('endpermission', function () {
            return "<?php endif; ?>";
        });
    }
}