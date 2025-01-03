<?php

use App\Http\Controllers\Categoria\CategoriaController;
use App\Http\Controllers\Producto\ProductoController;
use App\Http\Controllers\RegistrarController;
use App\Http\Controllers\Rol\RolController;
use App\Http\Controllers\Sucursal\SucursalController;
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



Route::get('/dashboard', function () {
    return view('dashboard.index');
});

Route::get('/Recuperacion_contraseña', function(){
    return view('OlvidoC.olvidoC');
});

Route::get('/index', function(){
    return view('pagina_principal.index');
});



// //modulo rol
// Route::get('/roles', [RolController::class, 'index'] )->name('roles');
// //vista para crear roles
// Route::get('/roles/create', [RolController::class,'create'])->name('roles.create');
// //enviando datos del rol
// Route::post('/roles/create', [RolController::class,'store'])->name('roles.store');

// //vista editar
// Route::get('/roles/{rol}/edit', [RolController::class,'edit'])->name('roles.edit');
// //enviar datos para actuializar rol
// Route::patch('/roles/{rol}/edit', [RolController::class,'update'])->name('roles.update');
// // ruta para eliminar
// Route::delete('/roles/{rol}',[RolController::class,'destroy'])->name('roles.destroy');

// Route::get('/categorias', [CategoriaController::class, 'index'])->name('categorias');

Route::resource('roles', RolController::class)->parameters(['roles' => 'rol']);
Route::resource('categorias', CategoriaController::class)->parameters(['categorias' => 'categoria']);
Route::resource('sucursales', SucursalController::class)->parameters(['sucursales' => 'sucursal']);
Route::resource('productos', ProductoController::class)->parameters(['productos' => 'producto']);
