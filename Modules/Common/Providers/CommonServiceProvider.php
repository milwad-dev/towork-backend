<?php

namespace Modules\Common\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Common\Console\Commands\MakeModule;
use Modules\Common\Contracts\JsonResponseInterface;
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
        $this->registerCommands();
        $this->bindJsonResponseFacade();
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
}
