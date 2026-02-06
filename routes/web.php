<?php

use Illuminate\Support\Facades\Route;

/* Route::get('/', function () {
    return view('welcome');
}); */

Route::get('/', function () {
    return response()->json([
        'app' => 'Campanha-K API',
        'status' => 'Online',
        'environment' => app()->environment(),
        'database' => 'Connected to Neon'
    ]);
});
