<?php

namespace Modules\Common\Responses;

use Symfony\Component\HttpFoundation\Response;

class JsonResponse
{
    /**
     * Return success json response.
     *
     * @param array|string|int $data
     * @param array            $headers
     * @param int              $options
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function successResponse(array|string|int $data, array $headers = [], int $options = 0)
    {
        return response()->json(data: $data, headers: $headers, options: $options);
    }

    /**
     * Return forbidden json response.
     *
     * @param array|string|int $data
     * @param array            $headers
     * @param int              $options
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function forbiddenResponse(array|string|int $data, array $headers = [], int $options = 0)
    {
        return response()->json(
            data: $data,
            status: Response::HTTP_FORBIDDEN,
            headers: $headers,
            options: $options
        );
    }

    /**
     * Return no-content json response.
     *
     * @param array|string|int $data
     * @param array            $headers
     * @param int              $options
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function noContentResponse(array|string|int $data, array $headers = [], int $options = 0)
    {
        return response()->json(
            data: $data,
            status: Response::HTTP_NO_CONTENT,
            headers: $headers,
            options: $options
        );
    }
}
