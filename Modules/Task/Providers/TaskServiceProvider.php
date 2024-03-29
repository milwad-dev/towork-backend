<?php

namespace Modules\Task\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class TaskServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->registerMigrations();
        $this->registerRoutes();
    }

    /**
     * Load route files.
     *
     * @return void
     */
    private function registerRoutes()
    {
        Route::middleware('api')
            ->prefix('api/'.config('app.version'))
            ->group(__DIR__.'/../routes/api.php');
    }

    /**
     * Load migration files.
     *
     * @return void
     */
    private function registerMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
    }
}
