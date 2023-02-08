<?php

namespace Modules\User\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class UserServiceProvider extends ServiceProvider
{
    /**
     * Register files.
     *
     * @return void
     */
    public function register()
    {
        $this->registerMigrations();
        $this->registerRoutes();
    }

    /**
     * Load migration files.
     *
     * @return void
     */
    private function registerMigrations(): void
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
    }

    /**
     * Load route files.
     *
     * @return void
     */
    private function registerRoutes(): void
    {
        Route::middleware('api')
            ->prefix('api/' . config('app.version'))
            ->group(__DIR__ . '/../Routes/api.php');
    }
}
