<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrabajadorController;

Route::get('/trabajadores', [TrabajadorController::class, 'index']);
Route::get('/trabajadores/{id}', [TrabajadorController::class, 'show']);
Route::post('/trabajadores', [TrabajadorController::class, 'store']);
Route::put('/trabajadores/{id}', [TrabajadorController::class, 'update']);
Route::delete('/trabajadores/{id}', [TrabajadorController::class, 'destroy']);


// Routes Ventasa

// Listar todas las ventas
Route::get('/ventas', function () {
    return response()->json(['message' => 'Listar todas las ventas']);
});

// Obtener detalles de una venta
Route::get('/ventas/{id}', function ($id) {
    return response()->json(['message' => 'Obtener detalles de la venta', 'id' => $id]);
});

// Listar detalles de una venta
Route::get('/ventas/{id}/detalles', function ($id) {
    return response()->json(['message' => 'Listar detalles de la venta', 'id' => $id]);
});

// Crear una nueva venta
Route::post('/ventas', function () {
    return response()->json(['message' => 'Crear una nueva venta']);
});

// Actualizar una venta
Route::put('/ventas/{id}', function ($id) {
    return response()->json(['message' => 'Actualizar una venta', 'id' => $id]);
});

// Eliminar (inactivar) una venta
Route::delete('/ventas/{id}', function ($id) {
    return response()->json(['message' => 'Eliminar (inactivar) una venta', 'id' => $id]);
});

// Crear un nuevo detalle de venta
Route::post('/ventas/{id}/detalles', function ($id) {
    return response()->json(['message' => 'Crear un nuevo detalle de venta', 'id' => $id]);
});

// Listar todos los detalles de venta
Route::get('/ventas/detalles', function () {
    return response()->json(['message' => 'Listar todos los detalles de venta']);
});
