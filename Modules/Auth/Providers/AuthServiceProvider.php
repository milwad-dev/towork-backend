<?php

namespace Modules\Auth\Providers;

use Modules\Auth\Console\PruneAuthCodes;
use Modules\Common\Providers\ModuleServiceProvider;

class AuthServiceProvider extends ModuleServiceProvider
{
    protected string $module = 'Auth';

    protected function bootModule(): void
    {
        if ($this->app->runningInConsole()) {
            $this->commands([PruneAuthCodes::class]);
        }
    }
}
