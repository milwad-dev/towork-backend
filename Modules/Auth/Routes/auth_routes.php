<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\LoginController;
use Modules\Auth\Http\Controllers\RegisterController;

Route::group([], static function ($router) {
    $router->post('register', RegisterController::class);

    $router->post('login', LoginController::class);
});
