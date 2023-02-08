<?php

namespace Modules\Auth\Providers;

use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * Register files.
     *
     * @return void
     */
    public function register()
    {
        $this->registerRoutes();
        $this->registerMigrations();
        $this->registerViews();
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
            ->group(__DIR__ . '/../Routes/auth_routes.php');
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
     * Load view files.
     *
     * @return void
     */
    private function registerViews(): void
    {
        $this->loadViewsFrom(__DIR__ . '/../Resources/Views', 'Auth');
    }
}
