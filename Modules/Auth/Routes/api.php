<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\EmailVerificationController;
use Modules\Auth\Http\Controllers\LoginController;
use Modules\Auth\Http\Controllers\LogoutController;
use Modules\Auth\Http\Controllers\PasswordResetController;
use Modules\Auth\Http\Controllers\RegisterController;

Route::prefix('auth')->name('auth.')->group(function () {
    Route::middleware(['guest:sanctum', 'throttle:auth'])->group(function () {
        Route::post('register', RegisterController::class)->name('register');
        Route::post('login', LoginController::class)->name('login');

        Route::controller(PasswordResetController::class)->group(function () {
            Route::post('forgot-password', 'send')->name('password.email');
            Route::post('reset-password/check', 'check')->name('password.check');
            Route::post('reset-password', 'reset')->name('password.reset');
        });
    });

    Route::middleware('auth:sanctum')->group(function () {
        Route::post('logout', LogoutController::class)->name('logout');

        Route::middleware('throttle:auth')->controller(EmailVerificationController::class)->group(function () {
            Route::post('email/verification-notification', 'send')->name('verification.send');
            Route::post('email/verify', 'verify')->name('verification.verify');
        });
    });
});
