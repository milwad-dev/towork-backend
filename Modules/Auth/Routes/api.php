<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\LoginController;
use Modules\Auth\Http\Controllers\Password\CodeCheckController;
use Modules\Auth\Http\Controllers\Password\ForgotPasswordController;
use Modules\Auth\Http\Controllers\Password\ResetPasswordController;
use Modules\Auth\Http\Controllers\RegisterController;
use Modules\Auth\Http\Controllers\Verify\Email\EmailVerifyController;

Route::group(['middleware' => 'guest:sanctum', 'as' => 'auth.'], static function ($router) {
    // Register
    $router->post('register', RegisterController::class)->name('register');

    // Login
    $router->post('login', LoginController::class)->name('login');

    // Password
    $router->post('password/email', ForgotPasswordController::class)->name('forgot_password');
    $router->post('password/code/check', CodeCheckController::class)->name('check_code_password');
    $router->post('password/reset', ResetPasswordController::class)->name('reset_password');

    $router->post('email/verify', [EmailVerifyController::class, 'verify'])
        ->name('email.verify')
        ->middleware('auth:sanctum')
        ->withoutMiddleware('guest');
    $router->post('email/verify/resend', [EmailVerifyController::class, 'resend'])
        ->name('email.verify.resend')
        ->middleware('auth:sanctum')
        ->withoutMiddleware('guest');
});
