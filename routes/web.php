<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('Login.login');
});
Route::get('/crear-cuenta', function () {
    return view('auth.registrar');
});
Route::get('/rol', function () {
    return view('rol');
});

Route::get('/categorias',function(){
    return view('categorias.index');
});
