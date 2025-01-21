<?php
use App\Http\Controllers\Almacen\AlmacenController;
use App\Http\Controllers\Categoria\CategoriaController;
use App\Http\Controllers\Compra\CompraController;
use App\Http\Controllers\Producto\ProductoController;
use App\Http\Controllers\Proveedor\ProveedorController;
use App\Http\Controllers\RegistrarController;
use App\Http\Controllers\Rol\RolController;
use App\Http\Controllers\Sucursal\SucursalController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\Consulta\consultaController;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\Medico\MedicoController;
use App\Http\Controllers\Persona\PersonaController;
use App\Http\Controllers\Venta\VentaController;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;


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
})->name('login');

Route::get('/dashboard', function () {
    return view('dashboard.index');
});


Route::resource('usuarios', AuthController::class)->parameters(['usuarios' => 'usuario']);
Route::post('/usuarios/register', [AuthController::class, 'register'])->name('usuarios.register');
Route::patch('/usuarios/{usuario}/actualizar-estado',[AuthController::class, 'actualizarEstado'])->name('usuarios.actualizarEstado');
Route::patch('usuarios/{id}', [AuthController::class, 'update'])->name('usuarios.update');
Route::resource('roles', RolController::class)->parameters(['roles' => 'rol']);
//Route::resource('roles', RolController::class)->parameters(['roles' => 'rol']);

Route::post('roles/{rol}/estado', [RolController::class, 'changeStatus'])->name('roles.changeStatus');
Route::resource('categorias', CategoriaController::class)->parameters(['categorias' => 'categoria']);
Route::resource('sucursales', SucursalController::class)->parameters(['sucursales' => 'sucursal']);
Route::resource('productos', ProductoController::class)->parameters(['productos' => 'producto']);
Route::resource('proveedores', ProveedorController::class)->parameters(['proveedores' => 'proveedor']);
Route::resource('compras', CompraController::class)->parameters(['compras' => 'compra']);
Route::resource('ventas', VentaController::class)->parameters(['ventas' => 'venta']);
Route::resource('almacenes', AlmacenController::class)->parameters(['almacenes' => 'almacen']);
Route::resource('personas', PersonaController::class)->parameters(['personas' => 'persona']);
Route::resource('medicos', MedicoController::class)->parameters(['medicos' => 'medico']);
Route::resource('consultas', consultaController::class)->parameters(['consultas' => 'consulta']);

Route::get('/productos/sucursal/{id}', [VentaController::class, 'productosPorSucursal']);
Route::get('ventas/productos/{idSucursal}', [VentaController::class, 'obtenerProductosPorSucursal'])->name('ventas.productos');
Route::get('/almacen/productos/{idSucursal}', [AlmacenController::class, 'getProductosPorSucursal']);

Route::get('/dashboard', [Dashboard::class, 'index'])->name('dashboard.index');
Route::get('/dashboard/filtrarVentas', [Dashboard::class, 'filtrarVentas'])->name('dashboard.filtrarVentas');



