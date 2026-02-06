<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\campaign\PersonController;

Route::prefix('auth')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
});

Route::prefix('setup')->group(function () {});

Route::prefix('campaign')->group(function () {
    Route::get('/hierarchy', [PersonController::class, 'getHierarchy']);
    Route::post('/location', [PersonController::class, 'storeLocation']);
    Route::post('/sector', [PersonController::class, 'storeSector']);
    Route::post('/family', [PersonController::class, 'storeFamily']);
    Route::post('/person', [PersonController::class, 'storePerson']);

    Route::put('/location/{id}', [PersonController::class, 'updateLocation']);
    Route::put('/sector/{id}', [PersonController::class, 'updateSector']);
    Route::put('/family/{id}', [PersonController::class, 'updateFamily']);
    Route::put('/person/{id}', [PersonController::class, 'updatePerson']);
});
