<?php

use Illuminate\Support\Facades\Route;
use Modules\User\Http\Controllers\ProfileController;

Route::middleware('auth:sanctum')->prefix('me')->name('me.')->controller(ProfileController::class)->group(function () {
    Route::get('/', 'show')->name('show');
    Route::put('/', 'update')->name('update');
    Route::put('password', 'updatePassword')->name('password');
    Route::delete('/', 'destroy')->name('destroy');
});
