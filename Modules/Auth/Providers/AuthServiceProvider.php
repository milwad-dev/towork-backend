<?php

namespace Modules\Auth\Providers;

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
        Route::middleware('web')
            ->namespace('Modules\Auth\Http\Controllers')
            ->group(__DIR__ . '/../Routes/auth_routes.php');
    }
}
