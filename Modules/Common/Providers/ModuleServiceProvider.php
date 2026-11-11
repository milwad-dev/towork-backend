<?php

namespace Modules\Common\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

/**
 * Base provider for every module: loads the module's API routes
 * (prefixed with /api/{version}), migrations and views.
 */
abstract class ModuleServiceProvider extends ServiceProvider
{
    /** Module name, used as the view namespace. */
    protected string $module;

    public function boot(): void
    {
        $base = $this->modulePath();

        if (is_dir($base.'/Database/Migrations')) {
            $this->loadMigrationsFrom($base.'/Database/Migrations');
        }

        if (is_dir($base.'/Resources/Views')) {
            $this->loadViewsFrom($base.'/Resources/Views', $this->module);
        }

        if (is_file($base.'/Routes/api.php') && ! $this->app->routesAreCached()) {
            Route::middleware(['api', 'timezone'])
                ->prefix('api/'.config('app.version'))
                ->group($base.'/Routes/api.php');
        }

        $this->bootModule();
    }

    /** Hook for module specific boot logic (policies, commands, ...). */
    protected function bootModule(): void
    {
        //
    }

    protected function modulePath(): string
    {
        return base_path('Modules/'.$this->module);
    }
}
