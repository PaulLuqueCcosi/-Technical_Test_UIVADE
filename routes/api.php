<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\TrabajadorController;

Route::get('/trabajadores', [TrabajadorController::class, 'index']);
Route::post('/trabajadores', [TrabajadorController::class, 'store']);
Route::put('/trabajadores/{id}', [TrabajadorController::class, 'update']);
Route::delete('/trabajadores/{id}', [TrabajadorController::class, 'destroy']);
