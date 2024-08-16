<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProductController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->middleware('throttle:10,1');
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');

// Rutas protegidas con autenticación Sanctum
Route::middleware('auth:sanctum')->group(function () {
    
    // Obtener listado de productos
    Route::get('/products', [ProductController::class, 'index']);

    // Agregar un nuevo producto
    Route::post('/products', [ProductController::class, 'store']);

    // Modificar un producto existente
    Route::put('/products/{id}', [ProductController::class, 'update']);

    // Eliminar un producto
    Route::delete('/products/{id}', [ProductController::class, 'destroy']);
});