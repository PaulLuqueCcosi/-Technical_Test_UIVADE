<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrabajadorController;
use App\Http\Controllers\VentaController;
use App\Http\Controllers\VentaDetalleController;

Route::get('/trabajadores', [TrabajadorController::class, 'index']);
Route::get('/trabajadores/{id}', [TrabajadorController::class, 'show']);
Route::post('/trabajadores', [TrabajadorController::class, 'store']);
Route::put('/trabajadores/{id}', [TrabajadorController::class, 'update']);
Route::delete('/trabajadores/{id}', [TrabajadorController::class, 'destroy']);


// Routes Ventasa
// Rutas para Ventas
Route::apiResource('ventas', VentaController::class);

// // Rutas para Detalles de Ventas
// Route::prefix('ventas/{venta_id}/detalles')->group(function () {
//     Route::get('/', [VentaDetalleController::class, 'index']); // Obtener detalles de una venta
//     Route::post('/', [VentaDetalleController::class, 'store']); // Crear nuevo detalle para una venta
//     Route::get('{id}', [VentaDetalleController::class, 'show']); // Obtener un detalle específico
//     Route::put('{id}', [VentaDetalleController::class, 'update']); // Actualizar un detalle específico
//     Route::delete('{id}', [VentaDetalleController::class, 'destroy']); // Eliminar un detalle específico
// });
