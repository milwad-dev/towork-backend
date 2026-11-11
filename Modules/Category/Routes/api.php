<?php

use Illuminate\Support\Facades\Route;
use Modules\Category\Http\Controllers\CategoryController;

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('categories', CategoryController::class);
});
