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
        $this->bindJsonResponse();
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
     * Bind json response.
     *
     * @return void
     */
    private function bindJsonResponse()
    {
        app()->bind(JsonResponseInterface::class, JsonResponse::class);
    }
}
