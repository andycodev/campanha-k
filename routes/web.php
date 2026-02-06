<?php

use Illuminate\Support\Facades\Route;

/* Route::get('/', function () {
    return view('welcome');
}); */

<?php

use Illuminate\Support\Facades\Route;

// Cambiamos la vista por una respuesta JSON directa
Route::get('/', function () {
    return response()->json([
        'app' => 'Campanha-K API',
        'status' => 'Online',
        'environment' => app()->environment(),
        'database_connection' => 'Connected to Neon'
    ]);
});