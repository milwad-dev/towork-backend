<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\LoginController;
use Modules\Auth\Http\Controllers\Password\CodeCheckController;
use Modules\Auth\Http\Controllers\Password\ForgotPasswordController;
use Modules\Auth\Http\Controllers\Password\ResetPasswordController;
use Modules\Auth\Http\Controllers\RegisterController;

Route::group([], static function ($router) {
    $router->post('register', RegisterController::class);

    $router->post('login', LoginController::class);

    // Password
    $router->post('password/email', ForgotPasswordController::class);
    $router->post('password/code/check', CodeCheckController::class);
    $router->post('password/reset', ResetPasswordController::class);
});
