<?php

use Illuminate\Support\Facades\Route;
use Modules\Dashboard\Http\Controllers\StatsController;

Route::middleware('auth:sanctum')->get('stats', StatsController::class)->name('stats');
