<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\LoginController;
use Modules\Auth\Http\Controllers\Password\CodeCheckController;
use Modules\Auth\Http\Controllers\Password\ForgotPasswordController;
use Modules\Auth\Http\Controllers\Password\ResetPasswordController;
use Modules\Auth\Http\Controllers\RegisterController;

Route::group(['middleware' => 'guest'], static function ($router) {
    $router->post('register', RegisterController::class)->name('auth.register');

    $router->post('login', LoginController::class)->name('auth.login');

    // Password
    $router->post('password/email', ForgotPasswordController::class)->name('auth.forgot_password');
    $router->post('password/code/check', CodeCheckController::class);
    $router->post('password/reset', ResetPasswordController::class);
});
