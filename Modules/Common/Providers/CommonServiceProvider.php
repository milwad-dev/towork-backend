<?php

namespace Modules\Common\Providers;

use Illuminate\Database\Eloquent\Factories\Factory;
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
        $this->registerFactories();
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
     * Load factories.
     *
     * @return void
     */
    private function registerFactories()
    {
        Factory::guessFactoryNamesUsing(static function (string $modelName) {
            return 'Modules\Common\Database\\Factories\\'.class_basename($modelName).'Factory';
        });
    }
}
