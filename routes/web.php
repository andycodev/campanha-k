<?php

use Illuminate\Support\Facades\Route;

// Texto plano, sin JSON ni lógica de DB
Route::get('/', function () {
    return 'Campanha-K API Online';
});
