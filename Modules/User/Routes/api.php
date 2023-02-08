<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\UserController;

/*
 * User routes.
 */

Route::group(['middleware' => 'auth'], static function ($router) {
    $router->apiResource('users', UserController::class)->except('store');
});
