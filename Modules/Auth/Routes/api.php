<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\LoginController;
use Modules\Auth\Http\Controllers\Password\CodeCheckController;
use Modules\Auth\Http\Controllers\Password\ForgotPasswordController;
use Modules\Auth\Http\Controllers\Password\ResetPasswordController;
use Modules\Auth\Http\Controllers\RegisterController;
use Modules\Auth\Http\Controllers\Verify\Email\EmailVerifyController;

Route::group(['middleware' => ['guest:sanctum', 'throttle:5,1'], 'as' => 'auth.'], static function ($router) {
    // Register
    $router->post('register', RegisterController::class)->name('register');

    // Login
    $router->post('login', LoginController::class)->name('login');

    // Password
    $router->post('password/email', ForgotPasswordController::class)->name('forgot_password');
    $router->post('password/code/check', CodeCheckController::class)->name('check_code_password');
    $router->post('password/reset', ResetPasswordController::class)->name('reset_password');

    // Verify
    $router->group(['middleware' => 'auth:sanctum', 'withoutMiddleware' => 'guest'], static function ($routerGroup) {
        $routerGroup->post('email/verify/request', [EmailVerifyController::class, 'request'])->name('email.request');
        $routerGroup->post('email/verify', [EmailVerifyController::class, 'verify'])->name('email.verify');
        $routerGroup->post('email/verify/resend', [EmailVerifyController::class, 'resend'])->name('email.verify.resend');
    });
});
