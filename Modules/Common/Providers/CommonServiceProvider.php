<?php

namespace Modules\Common\Providers;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;
use Modules\Common\Console\Commands\MakeModule;
use Modules\Common\Responses\JsonResponse;

class CommonServiceProvider extends ServiceProvider
{
    /**
     * Register files.
     *
     * @return void
     */
    public function register()
    {
        $this->bindJsonResponseFacade();
        $this->registerCommands();
    }

    public function boot()
    {
        $this->routeMacro();
    }

    /**
     * Bind json response facade.
     *
     * @return void
     */
    private function bindJsonResponseFacade()
    {
        app()->bind('json-response', function ($app) {
            return new JsonResponse();
        });
    }

    /**
     * Register commands.
     *
     * @return void
     */
    private function registerCommands(): void
    {
        $this->commands(MakeModule::class);
    }

    /**
     * Add macro-ability to route.
     *
     * @return void
     */
    private function routeMacro(): void
    {
        Route::macro('apiRoute', function (string $route) {
            $this->middleware('api')
                ->prefix('api/' . config('app.version'))
                ->group($route);
        });
    }
}
