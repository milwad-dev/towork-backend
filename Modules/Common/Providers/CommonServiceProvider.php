<?php

namespace Modules\Common\Providers;

use Illuminate\Support\ServiceProvider;
use Modules\Common\Console\Commands\MakeModule;

class CommonServiceProvider extends ServiceProvider
{
    /**
     * Register files.
     *
     * @return void
     */
    public function register()
    {
        $this->commands(MakeModule::class);
    }
}
