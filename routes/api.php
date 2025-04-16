<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AcademiaController;
use App\Http\Controllers\Api\CursoController;
use App\Http\Controllers\Api\EstudianteController;
use App\Http\Controllers\Api\MatriculaController;
use App\Http\Controllers\Api\PagoController;
use App\Http\Controllers\Api\ComunicadoController;
use App\Http\Controllers\Api\AuthController;

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

// Ruta para obtener el usuario autenticado
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// Rutas protegidas con autenticación
Route::middleware('auth:sanctum')->group(function () {
    
    // Rutas para Academias
    Route::apiResource('academias', AcademiaController::class);
    
    // Rutas para Cursos
    Route::apiResource('cursos', CursoController::class);
    
    // Rutas para Estudiantes
    Route::apiResource('estudiantes', EstudianteController::class);
    
    // Rutas para Matrículas
    Route::apiResource('matriculas', MatriculaController::class);
    
    // Rutas para Pagos
    Route::apiResource('pagos', PagoController::class);
    Route::get('matriculas/{matricula}/pagos/resumen', [PagoController::class, 'resumenPorMatricula']);
    
    // Rutas para Comunicados
    Route::apiResource('comunicados', ComunicadoController::class)->except(['update']);
    Route::get('padres/{padre}/comunicados', [ComunicadoController::class, 'comunicadosPorPadre']);
});

// Rutas de autenticación (públicas)
Route::post('auth/register', [AuthController::class, 'register']);
Route::post('auth/login', [AuthController::class, 'login']);

// Ruta para cerrar sesión (protegida)
Route::middleware('auth:sanctum')->post('auth/logout', [AuthController::class, 'logout']);

// Otras rutas públicas (si se requieren)
// Route::get('cursos/publicos', [CursoController::class, 'cursosPublicos']);
