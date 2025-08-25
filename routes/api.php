<?php

use App\Http\Controllers\Almacen\AlmacenController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\bitacora\bitacoraController;
use App\Http\Controllers\Categoria\CategoriaController;
use App\Http\Controllers\Compra\CompraController;
use App\Http\Controllers\Consulta\consultaController;
use App\Http\Controllers\devoluciones\devolucionesController;
use App\Http\Controllers\Especialidades\especialidadesController;
use App\Http\Controllers\Inventario\InventarioController;
use App\Http\Controllers\Lote\LoteController;
use App\Http\Controllers\Medico\MedicoController;
use App\Http\Controllers\Persona\PersonaController;
use App\Http\Controllers\Producto\ProductoController;
use App\Http\Controllers\Proveedor\ProveedorController;
use App\Http\Controllers\Reportes\ReporteVentasController;
use App\Http\Controllers\Requisicion\RequisicionController;
use App\Http\Controllers\Rol\RolController;
use App\Http\Controllers\solicitud\solicitudController;
use App\Http\Controllers\Sucursal\SucursalController;
use App\Http\Controllers\traslado\trasladoController;
use App\Http\Controllers\Usuario\UsuarioController;
use App\Http\Controllers\Venta\VentaController;
use App\Models\Encuestas;

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
    //Route::post('register', 'App\Http\Controllers\UsuarioController@register');
    
});

Route::get('/municipios/{departamento}', function ($departamento) {
    return \App\Models\Municipio::where('departamento_id', $departamento)->orderBy('nombre')->get();
});



Route::post('/roles/{id}/cambiar-estado', [RolController::class, 'cambiarEstado']);
Route::post('/usuario/{id}/cambiar-estado', [UsuarioController::class, 'cambiarEstado']);
Route::post('/categoria/{id}/cambiar-estado', [CategoriaController::class, 'cambiarEstado']);
Route::post('/proveedor/{id}/cambiar-estado', [ProveedorController::class, 'cambiarEstado']);
Route::post('/sucursal/{id}/cambiar-estado', [SucursalController::class, 'cambiarEstado']);
Route::post('/producto/{id}/cambiar-estado', [ProductoController::class, 'cambiarEstado']);
Route::post('/almacen/{id}/cambiar-estado', [AlmacenController::class, 'cambiarEstado']);
Route::post('/persona/{id}/cambiar-estado', [PersonaController::class, 'cambiarEstado']);
Route::post('/medico/{id}/cambiar-estado', [MedicoController::class, 'cambiarEstado']);
Route::post('/consulta/{id}/cambiar-estado', [consultaController::class, 'cambiarEstado']);
Route::post('/traslado/{id}/cambiar-estado', [trasladoController::class, 'cambiarEstado']);
Route::post('/solicitud/{id}/cambiar-estado', [solicitudController::class, 'cambiarEstado']);


Route::middleware(['jwt.auth'])->group(function () {
    //api para productos
    Route::get('productos', [ProductoController::class, 'indexApi']);
    Route::post('productos/insertar', [ProductoController::class, 'storeApi']);
    Route::get('productos/{id}', [ProductoController::class, 'show']);
    Route::put('productos/{id}/actualizar', [ProductoController::class, 'updateApi']);
    

    //api para usuarios
    Route::get('usuarios', [UsuarioController::class, 'indexApi']);
    Route::post('registrar', [UsuarioController::class, 'registerApi']);
    Route::get('usuarios/{id}', [UsuarioController::class, 'show']);
    Route::put('usuarios/{id}/actualizar', [UsuarioController::class, 'updateApi']);

    //api para ventas
    Route::get('ventas', [VentaController::class, 'indexApi']);
    Route::post('ventas/insertar', [VentaController::class, 'storeApi']);

    //api para personas
    Route::get('personas', [PersonaController::class, 'indexApi']);
    Route::post('personas/insertar', [PersonaController::class, 'storeApi']);
    Route::get('personas/{id}', [PersonaController::class, 'showApi']);
    Route::put('personas/{id}/actualizar', [PersonaController::class, 'updateApi']);

    //api de especialidades
    Route::get('especialidades', [EspecialidadesController::class, 'indexApi']);
    Route::post('especialidades/insertar', [EspecialidadesController::class, 'storeApi']);
    Route::get('especialidades/{id}', [EspecialidadesController::class, 'show']);
    Route::put('especialidades/{id}/actualizar', [EspecialidadesController::class, 'updateApi']);

    //api para medicos
    Route::get('medicos', [MedicoController::class, 'indexApi']);
    Route::post('medicos/insertar', [MedicoController::class, 'storeApi']);
    Route::get('medicos/{id}', [MedicoController::class, 'showApi']);
    Route::put('medicos/{id}/actualizar', [MedicoController::class, 'updateApi']);

    //api consultas
    Route::get('consultas', [consultaController::class, 'indexApi']);
    Route::post('consultas/insertar', [consultaController::class, 'storeApi']);
    Route::get('consultas/{id}', [consultaController::class, 'show']);
    Route::put('consultas/{id}/actualizar', [consultaController::class, 'updateApi']);

    //api requisiciones
    Route::get('requisiciones', [RequisicionController::class, 'indexApi']);
    Route::post('requisiciones/insertar', [RequisicionController::class, 'storeApi']);
    Route::get('requisiciones/{id}', [RequisicionController::class, 'showApi']);
    Route::put('requisiciones/{id}/actualizar', [RequisicionController::class, 'updateApi']);

    //api traslados
    Route::get("traslados", [trasladoController::class, 'indexApi']);
    Route::post("traslados/insertar", [trasladoController::class, 'storeApi']);
    Route::get("traslados/{id}", [trasladoController::class, 'show']);
    Route::patch("traslados/{id}/actualizar", [trasladoController::class, 'updateApi']);

    //api bitacora 
    Route::get("bitacora", [BitacoraController::class, 'indexApi']);
    Route::get("bitacora/{id}", [BitacoraController::class, 'show']);

    //api para categoria
    Route::get("categorias", [CategoriaController::class, 'indexApi']);
    Route::post("categorias/insertar", [CategoriaController::class, 'storeApi']);
    Route::get("categorias/{id}", [CategoriaController::class, 'show']);
    Route::put("categorias/{id}/actualizar", [CategoriaController::class, 'updateApi']);

    //api para los proveedores
    Route::get("proveedores", [ProveedorController::class, 'indexApi']);
    Route::post("proveedores/insertar", [ProveedorController::class, 'storeApi']);
    Route::get("proveedores/{id}", [ProveedorController::class, 'show']);
    Route::put("proveedores/{id}/actualizar", [ProveedorController::class, 'updateApi']);

    //api para los roles
    Route::get("roles", [RolController::class, 'indexApi']);
    Route::post("roles/insertar", [RolController::class, 'storeApi']);
    Route::get("roles/{id}", [RolController::class, 'show']);
    Route::put("roles/{id}/actualizar", [RolController::class, 'updateApi']);

    //api sucursales
    Route::get("sucursales", [SucursalController::class, 'indexApi']);
    Route::post("sucursales/insertar", [SucursalController::class, 'storeApi']);
    Route::get("sucursales/{id}", [SucursalController::class, 'show']);
    Route::put("sucursales/{id}/actualizar", [SucursalController::class, 'updateApi']);

});