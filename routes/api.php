<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrabajadorController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\VentaDetalleController;

// Ruta trabajadores
Route::get('/trabajadores', [TrabajadorController::class, 'index']);
Route::get('/trabajadores/{id}', [TrabajadorController::class, 'show']);
Route::post('/trabajadores', [TrabajadorController::class, 'store']);
Route::put('/trabajadores/{id}', [TrabajadorController::class, 'update']);
Route::delete('/trabajadores/{id}', [TrabajadorController::class, 'destroy']);


// Ruta para obtener todos los detalles de ventas
Route::get('ventas/detalles', [VentaDetalleController::class, 'indexAll']);
// Rutas para Ventas
Route::apiResource('ventas', VentaController::class);

// Rutas para detalles de ventas asociados a una venta específica
Route::prefix('ventas/{venta_id}/detalles')->group(function () {
    Route::get('/', [VentaDetalleController::class, 'index']); // Obtener detalles de una venta
    Route::post('/', [VentaDetalleController::class, 'store']); // Crear nuevo detalle para una venta
    Route::get('{id}', [VentaDetalleController::class, 'show']); // Obtener un detalle específico
    Route::put('{id}', [VentaDetalleController::class, 'update']); // Actualizar un detalle específico
    Route::delete('{id}', [VentaDetalleController::class, 'destroy']); // Eliminar un detalle específico
});


// Ruta para obtener un detalle específico sin verificar la venta
Route::get('ventas/detalles/{id}', [VentaDetalleController::class, 'showById']);
