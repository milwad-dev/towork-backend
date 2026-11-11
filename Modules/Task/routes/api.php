<?php

use Illuminate\Support\Facades\Route;
use Modules\Task\Http\Controllers\SubtaskController;
use Modules\Task\Http\Controllers\TaskController;

Route::middleware('auth:sanctum')->group(function () {
    Route::post('tasks/reorder', [TaskController::class, 'reorder'])->name('tasks.reorder');
    Route::patch('tasks/{task}/toggle', [TaskController::class, 'toggle'])->name('tasks.toggle');
    Route::apiResource('tasks', TaskController::class);

    Route::post('tasks/{task}/subtasks', [SubtaskController::class, 'store'])->name('subtasks.store');
    Route::patch('subtasks/{subtask}', [SubtaskController::class, 'update'])->name('subtasks.update');
    Route::delete('subtasks/{subtask}', [SubtaskController::class, 'destroy'])->name('subtasks.destroy');
});
