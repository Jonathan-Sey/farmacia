<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Consulta\consultaController;
use App\Http\Controllers\solicitud\solicitudController;
use App\Http\Controllers\Usuario\UsuarioController;
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
//Rutas que nesecitan authenticacion
 Route::group([
     'middleware' => ['api', 'auth:api'],
     'prefix' => 'auth'
 ], function ($router) {
     Route::post('logout', [AuthController::class, 'logout'])->name('logout');
     Route::post('refresh', 'App\Http\Controllers\AuthController@refresh');
     Route::post('me', 'App\Http\Controllers\AuthController@me');
     //Route::resource('consultas', consultaController::class)->parameters(['consultas' => 'consulta']);
 });



//Rutas que son publicas
Route::group([
    'middleware' => ['api'],
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', 'App\Http\Controllers\AuthController@login');
    Route::post('register', 'App\Http\Controllers\UsuarioController@register');
});

Route::get('/municipios/{departamento}', function ($departamento) {
    return \App\Models\Municipio::where('departamento_id', $departamento)->orderBy('nombre')->get();
});

