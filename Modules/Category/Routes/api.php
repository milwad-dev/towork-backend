<?php

use Illuminate\Support\Facades\Route;
use Modules\Category\Http\Controllers\CategoryController;

/*
 * User routes.
 */

Route::group(['middleware' => 'auth:sanctum'], static function ($router) {
    $router->apiResource('categories', CategoryController::class);
});
