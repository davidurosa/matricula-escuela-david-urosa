<?php

use App\Http\Controllers\ComunicadoController;
use App\Http\Controllers\PagoController;
use Illuminate\Support\Facades\Route;
use Livewire\Volt\Volt;

Route::get('/', function () {
    return view('welcome');
})->name('home');

Route::view('dashboard', 'dashboard')
    ->middleware(['auth', 'verified'])
    ->name('dashboard');

Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Volt::route('settings/profile', 'settings.profile')->name('settings.profile');
    Volt::route('settings/password', 'settings.password')->name('settings.password');
    Volt::route('settings/appearance', 'settings.appearance')->name('settings.appearance');
    
    // Rutas para matrículas
    Route::get('/matriculas/create', function () {
        return view('matriculas.create');
    })->name('matriculas.create');
    
    // Rutas para gestión de pagos
    Route::get('/pagos', [PagoController::class, 'index'])->name('pagos.index');
    Route::get('/pagos/{matricula}', [PagoController::class, 'show'])->name('pagos.show');
    
    // Rutas para gestión de comunicados
    Route::get('/comunicados', [ComunicadoController::class, 'index'])->name('comunicados.index');
    Route::get('/comunicados/crear', [ComunicadoController::class, 'create'])->name('comunicados.create');
});

require __DIR__.'/auth.php';
