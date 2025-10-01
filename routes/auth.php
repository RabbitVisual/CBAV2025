<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

/*
|--------------------------------------------------------------------------
| Rotas de Autenticação
|--------------------------------------------------------------------------
|
| Rotas para login e logout do sistema.
|
*/

Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
});

Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');