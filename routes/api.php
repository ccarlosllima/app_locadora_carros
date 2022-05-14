<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/', function () {
    return ['nome' => 'carlos lima'];
});

Route::ApiResource('cliente','App\Http\Controllers\ClienteController');
Route::ApiResource('carro','App\Http\Controllers\CarroController');
Route::ApiResource('locacao','App\Http\Controllers\LocacaoController');
Route::ApiResource('marca','App\Http\Controllers\MarcaController');
Route::ApiResource('modelo','App\Http\Controllers\ModeloController');