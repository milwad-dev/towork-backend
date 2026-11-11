<?php

use Illuminate\Support\Facades\Route;

Route::get('/', fn () => response()->json([
    'name'    => config('app.name'),
    'version' => config('app.release'),
    'api'     => url('api/'.config('app.version')),
    'docs'    => url(config('request-docs.url', 'request-docs')),
]));
