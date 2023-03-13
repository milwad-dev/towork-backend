<?php

namespace Modules\Common\Responses;

use Illuminate\Support\Facades\Facade;

class JsonResponseFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'json-response';
    }
}
