<?php

namespace Modules\Common\Console\Commands\Traits\MakeModuleTraits;

trait ServiceProviderTrait
{
    private function getServiceProviderBodyData(string $argument)
    {
        return "<?php

namespace Modules\\$argument\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class {$argument}ServiceProvider extends ServiceProvider
{
    public $/namespace = 'Modules\\$argument\Http\Controllers';

    public function register()
    {
        $/this->loadMigrationsFrom(__DIR__ . '/../Database/Migrations');
        $/this->loadViewsFrom(__DIR__ . '/../Resources/Views/', '{$argument}');
        Route::middleware('web')->namespace($/this->namespace)->group(__DIR__ . '/../routes/{$argument}_routes.php');
    }
}
";
    }
}
