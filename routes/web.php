<?php

use App\Http\Controllers\AnimalController;
use App\Http\Controllers\AuthController;
use Illuminate\Support\Facades\Route;

Route::get('/', fn () => redirect()->route('animales.index'));

// Las cuentas. Solo para quien NO tiene sesión: el middleware guest.
Route::middleware('guest')->group(function () {
    Route::get('/registro', [AuthController::class, 'mostrarRegistro'])->name('registro');
    Route::post('/registro', [AuthController::class, 'registrar']);
    Route::get('/login',     [AuthController::class, 'mostrarLogin'])->name('login');
    Route::post('/login',    [AuthController::class, 'entrar']);
});

Route::post('/logout', [AuthController::class, 'salir'])->middleware('auth')->name('logout');

// El refugio. Ahora todo pide sesión.
Route::middleware('auth')->group(function () {
    Route::get('/animales',                    [AnimalController::class, 'index'])->name('animales.index');
    Route::get('/animales/crear',              [AnimalController::class, 'create'])->name('animales.create');
    Route::post('/animales',                   [AnimalController::class, 'store'])->name('animales.store');
    Route::get('/animales/{animal}/editar',    [AnimalController::class, 'edit'])->name('animales.edit');
    Route::put('/animales/{animal}',           [AnimalController::class, 'update'])->name('animales.update');
    Route::patch('/animales/{animal}/adoptar', [AnimalController::class, 'adoptar'])->name('animales.adoptar');
    Route::delete('/animales/{animal}',        [AnimalController::class, 'destroy'])->name('animales.destroy');
});
