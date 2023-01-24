<?php

use Illuminate\Support\Facades\Route;
use Modules\Auth\Http\Controllers\RegisterController;

Route::group([], static function ($router) {
    // Register
    $router->post('register', [RegisterController::class]);
});
