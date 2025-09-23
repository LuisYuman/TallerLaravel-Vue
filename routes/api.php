<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\UsuarioController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\TareaController;
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
// Rutas públicas (login, register)
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/login', [AuthController::class, 'loginForm']); // Para testing desde navegador
Route::get('/listUsers', [UsuarioController::class, 'index']);

// Rutas protegidas por Sanctum
Route::middleware('auth:sanctum')->group(function () {
    // Usuarios
    Route::prefix('usuarios')->group(function () {
        
        Route::post('/addUser', [UsuarioController::class, 'store']);
        Route::get('/addUser', [UsuarioController::class, 'createUserForm']); // Para testing desde navegador
        Route::get('/getUser/{id}', [UsuarioController::class, 'show']);
        Route::put('/updateUser/{id}', [UsuarioController::class, 'update']);
        Route::delete('/deleteUser/{id}', [UsuarioController::class, 'destroy']);
    });

    // Logout
    Route::post('/logout', [AuthController::class, 'logout']);

    // Tareas
    Route::prefix('tareas')->group(function () {
        Route::get('/', [TareaController::class, 'index']);
        Route::post('/', [TareaController::class, 'store']);
        Route::get('/usuarios', [TareaController::class, 'getUsers']); // Para obtener usuarios para el selector
        Route::get('/report-pendientes', [TareaController::class, 'downloadPendingReport']); // Descargar reporte Excel
        Route::get('/{id}', [TareaController::class, 'show']);
        Route::put('/{id}', [TareaController::class, 'update']);
        Route::delete('/{id}', [TareaController::class, 'destroy']);
    });
});
