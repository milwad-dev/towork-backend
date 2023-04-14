<?php

namespace Modules\Common\Console\Commands\Traits\MakeModuleTraits;

trait RouteTrait
{
    public function getRouteBodyData()
    {
        return "<?php

use Illuminate\Support\Facades\Route;

Route::group([], function (\$router) {

});
        ";
    }
}
