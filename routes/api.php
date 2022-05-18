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

Route::prefix('v1')->middleware('jwt.auth')->group(function(){
    Route::post('/me','App\Http\Controllers\AuthController@me');
    Route::post('/logout','App\Http\Controllers\AuthController@logout');
    Route::post('/refresh','App\Http\Controllers\AuthController@refresh');
    Route::ApiResource('cliente','App\Http\Controllers\ClienteController');
    Route::ApiResource('carro','App\Http\Controllers\CarroController');
    Route::ApiResource('locacao','App\Http\Controllers\LocacaoController');
    Route::ApiResource('marca','App\Http\Controllers\MarcaController');
    Route::ApiResource('modelo','App\Http\Controllers\ModeloController');
});
Route::post('/login','App\Http\Controllers\AuthController@login');
