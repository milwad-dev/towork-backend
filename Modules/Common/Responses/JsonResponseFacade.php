<?php

namespace Modules\Common\Responses;

use Illuminate\Support\Facades\Facade;

/**
 * @method static successResponse(array|string|int $data, array $headers = [], int $options = 0)
 * @method static forbiddenResponse(array|string|int $data, array $headers = [], int $options = 0)
 * @method static noContentResponse(array|string|int $data, array $headers = [], int $options = 0)
 */
class JsonResponseFacade extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'json-response';
    }
}
