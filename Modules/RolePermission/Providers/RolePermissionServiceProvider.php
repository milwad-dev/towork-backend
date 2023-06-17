<?php

namespace Modules\RolePermission\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class RolePermissionServiceProvider extends ServiceProvider
{
    public $namespace = 'Modules\RolePermission\Http\Controllers';

    public function register()
    {
        $this->loadMigrationsFrom(__DIR__.'/../Database/Migrations');
        $this->loadViewsFrom(__DIR__.'/../Resources/Views/', 'RolePermission');
        Route::middleware('web')
            ->namespace($this->namespace)
            ->group(__DIR__.'/../Routes/api.php');
    }
}
