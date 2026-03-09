<?php

namespace App\Providers;

use App\Helper\Helper;
use App\Models\Admin;
use App\Models\Authorization;
use App\Models\AuthorizationType;
use App\Models\Module;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Http\Request;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\RateLimiter;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (env('APP_ENV') !== 'local') {
            URL::forceScheme('https');
        }

        RateLimiter::for('global', function (Request $request) {
            return Limit::perMinute(60)->by($request->user()?->id ?: $request->ip());
        });

        Gate::define('access', function (Admin $admin, string $access) {
            $authorizationType = AuthorizationType::where('name', $access)->first();
            if (!isset($authorizationType)) return false;

            $moduleRoute = Helper::getModuleRoute();
            $module = Module::where('route', $moduleRoute)->first();
            if (!isset($module)) return false;

            $isAuthorized = Authorization::where('role_id', $admin->role_id)->where('authorization_type_id', $authorizationType->id)->where('module_id', $module->id)->exists();
            return $isAuthorized;
        });

        Paginator::useBootstrapFive();
    }
}
