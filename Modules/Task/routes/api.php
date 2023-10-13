<?php

use Illuminate\Support\Facades\Route;
use Modules\Task\Http\Controllers\TaskController;

/*
 * Task routes.
 */
Route::group(['middleware' => 'auth:sanctum'], static function ($router) {
    $router->apiResource('tasks', TaskController::class);
});

