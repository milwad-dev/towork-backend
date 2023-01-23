<?php

namespace Modules\Auth\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AuthServiceProvider extends ServiceProvider
{
    public $/namespace = 'Modules\Auth\Http\Controllers';

    public function register()
    {
        $/this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $/this->loadViewsFrom(__DIR__ . '/../Resources/Views/', 'Auth');
        Route::middleware('web')->namespace($/this->namespace)->group(__DIR__ . '/../routes/Auth_routes.php');
    }
}
