<?php

namespace Modules\Dashboard\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Modules\Common\Http\Controllers\Controller;
use Modules\Dashboard\Services\StatsService;

class StatsController extends Controller
{
    public function __invoke(Request $request, StatsService $stats): JsonResponse
    {
        return response()->json(['data' => $stats->forUser($request->user())]);
    }
}
