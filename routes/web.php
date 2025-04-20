<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CondominioController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\MoradorController;
use App\Http\Controllers\ApartamentoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Rotas de autenticação
Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login']);
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['auth'])->group(function () {
    Route::get('/home', [HomeController::class, 'index'])->name('home');
    
    // Rotas de Admin
    Route::resource('condominios', CondominioController::class);
    Route::resource('users', UserController::class);
    Route::prefix('sindicos')->group(function() {
        Route::post('{user}/vincular', [UserController::class, 'vincularCondominio'])
            ->name('sindicos.vincular');
            
        Route::delete('{user}/desvincular', [UserController::class, 'desvincularCondominio'])
            ->name('sindicos.desvincular');
            
        Route::post('{user}/toggle-active', [UserController::class, 'toggleActive'])
            ->name('sindicos.toggle-active');
    });
    // Rotas para Moradores (Síndico e Admin)
    Route::resource('moradores', MoradorController::class)->parameters(['moradores'=> 'morador'])->except(['show']);
    Route::post('/moradores/{morador}/expulsar', [MoradorController::class, 'expulsar'])
     ->name('moradores.expulsar')
     ->middleware('auth');
    
    // Rotas para Apartamentos
    Route::resource('apartamentos', ApartamentoController::class);
    Route::delete('/apartamentos/{apartamento}/desvincular', [ApartamentoController::class, 'desvincularMorador'])
         ->name('apartamentos.desvincular');
});