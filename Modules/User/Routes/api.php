<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\UserController;

/*
 * User routes.
 */

Route::group(['prefix' => config('app.version'), 'middleware' => 'auth'], static function ($router) {
    $router->apiResource('users', UserController::class);
});
