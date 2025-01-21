<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Categoria\CategoriaController;

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

Route::group([
    'middleware' => 'api',
    'prefix' => 'auth'
], function ($router) {
    Route::post('login', 'App\Http\Controllers\AuthController@login');
    Route::post('logout', 'App\Http\Controllers\AuthController@logout');
    Route::post('refresh', 'App\Http\Controllers\AuthController@refresh');
    Route::post('me', 'App\Http\Controllers\AuthController@me');
    Route::post('register', 'App\Http\Controllers\AuthController@register');
});

//  Route::group(['middleware' => ['auth:api', 'role:2'], 'prefix' => 'api'], function() {
//      Route::resource('categorias', CategoriaController::class)->parameters(['categorias' => 'categoria'])->names([ 'index' => 'api.categorias.index', 'create' => 'api.categorias.create', 'store' => 'api.categorias.store', 'show' => 'api.categorias.show', 'edit' => 'api.categorias.edit', 'update' => 'api.categorias.update', 'destroy' => 'api.categorias.destroy', ]);
//  });

