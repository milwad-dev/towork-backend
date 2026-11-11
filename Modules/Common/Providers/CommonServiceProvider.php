<?php

namespace Modules\Common\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Common\Console\Commands\MakeModule;

class CommonServiceProvider extends ServiceProvider
{
    public function boot(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([MakeModule::class]);
        }
    }
}
