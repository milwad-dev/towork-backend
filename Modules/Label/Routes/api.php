<?php

use Illuminate\Support\Facades\Route;
use Modules\Label\Http\Controllers\LabelController;

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('labels', LabelController::class);
});
