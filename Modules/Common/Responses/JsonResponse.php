<?php

namespace Modules\Common\Responses;

class JsonResponse
{
    /**
     * Return success json response.
     *
     * @param array|string|int|object $data
     * @param array $headers
     * @param int $options
     * @return \Illuminate\Http\JsonResponse
     */
    public function successResponse(array|string|int|object $data, array $headers = [], int $options = 0)
    {
        return response()->json(data: $data, headers: $headers, options: $options);
    }
}
