<?php

use Illuminate\Support\Facades\Route;
use Modules\Task\Http\Controllers\TaskController;
use Modules\Task\Http\Controllers\TaskMarkAsDoneController;

/*
 * Task routes.
 */
Route::group(['middleware' => 'auth:sanctum'], static function ($router) {
    $router->patch('tasks/{task}/markAsDone', TaskMarkAsDoneController::class);
    $router->apiResource('tasks', TaskController::class);
});
