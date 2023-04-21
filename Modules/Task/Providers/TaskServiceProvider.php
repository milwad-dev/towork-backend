<?php

namespace Modules\Task\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class TaskServiceProvider extends ServiceProvider
{
    public $namespace = 'Modules\Task\Http\Controllers';

    public function register()
    {
        $this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $this->loadViewsFrom(__DIR__ . '/../Resources/Views/', 'Task');
        Route::middleware('web')->namespace($this->namespace)->group(__DIR__ . '/../routes/Task_routes.php');
    }
}
