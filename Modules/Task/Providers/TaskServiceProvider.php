<?php

namespace Modules\Task\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class TaskServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->registerMigrations();
        $this->registerRoutes();
    }

    /**
     * Load route files.
     */
    private function registerRoutes(): void
    {
        Route::middleware('api')
            ->prefix('api/'.config('app.version'))
            ->group(__DIR__.'/../Routes/api.php');
    }

    /**
     * Load migration files.
     */
    private function registerMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
    }
}
