<?php

use Illuminate\Support\Facades\Route;
use Modules\RolePermission\Http\Controllers\RoleController;

/*
 * Category routes.
 */

Route::group(['middleware' => 'auth:sanctum'], static function ($router) {
    $router->apiResource('roles', RoleController::class);
});
