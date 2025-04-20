<?php

use App\Models\Apartamento;
use App\Models\Condominio;
use App\Models\Morador;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::get('/condominios/{condominio}/moradores', function ($condominioId) {
    return Morador::where('condominio_id', $condominioId)
                 ->get(['id', 'nome']);
});

Route::get('/condominios/{condominio}/moradores', function ($condominioId) {
    return Morador::where('condominio_id', $condominioId)
                 ->get(['id', 'nome']);
});
Route::get('/condominios/{condominio}/apartamentos-vagos', function (Condominio $condominio) {
    return $condominio->apartamentos()
                     ->whereNull('morador_id')
                     ->get(['id', 'numero']);
});
