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
use App\Http\Controllers\bitacora\bitacoraController;
use App\Http\Controllers\Usuario\UsuarioController;
use App\Http\Controllers\Consulta\consultaController;
use App\Http\Controllers\Dashboard;
use App\Http\Controllers\Inventario\InventarioController;
use App\Http\Controllers\Lote\LoteController;
use App\Http\Controllers\Medico\MedicoController;
use App\Http\Controllers\Persona\PersonaController;
use App\Http\Controllers\Requisicion\RequisicionController;
//use App\Http\Controllers\Traslado\TrasladoController;
use App\Http\Controllers\solicitud\solicitudController;

use App\Http\Controllers\traslado\trasladoController;
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

// Route::get('/dashboard', function () {
//     return view('dashboard.index');
// });


//Route::middleware('jwt.web')->group(function () {
    Route::get('/dashboard', [Dashboard::class, 'index'])->name('dashboard.index');
    Route::resource('categorias', CategoriaController::class)->parameters(['categorias' => 'categoria']);
//});
//Route::resource('categorias', CategoriaController::class)->parameters(['categorias' => 'categoria']);

//Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
Route::get('/dashboard/filtrarVentas', [Dashboard::class, 'filtrarVentas'])->name('dashboard.filtrarVentas');
//Route::post('/auth/login', [AuthController::class, 'login']);
Route::get('/dashboard', [Dashboard::class, 'index'])->name('dashboard.index');
Route::resource('consultas', consultaController::class)->parameters(['consultas' => 'consulta']);
Route::resource('medicos', MedicoController::class)->parameters(['medicos' => 'medico']);
//Route::resource('categorias', CategoriaController::class)->parameters(['categorias' => 'categoria']);
Route::resource('usuarios', UsuarioController::class)->parameters(['usuarios' => 'usuario']);
Route::post('/usuarios/register', [UsuarioController::class, 'register'])->name('usuarios.register');
Route::patch('/usuarios/{usuario}/actualizar-estado',[UsuarioController::class, 'actualizarEstado'])->name('usuarios.actualizarEstado');
Route::patch('usuarios/{id}', [UsuarioController::class, 'update'])->name('usuarios.update');
Route::resource('roles', RolController::class)->parameters(['roles' => 'rol']);
Route::post('/roles/{rol}', [RolController::class, 'update'])->name('roles.update');
Route::post('roles/{rol}/estado', [RolController::class, 'changeStatus'])->name('roles.changeStatus');
Route::resource('sucursales', SucursalController::class)->parameters(['sucursales' => 'sucursal']);
Route::resource('productos', ProductoController::class)->parameters(['productos' => 'producto']);
Route::resource('proveedores', ProveedorController::class)->parameters(['proveedores' => 'proveedor']);
Route::resource('compras', CompraController::class)->parameters(['compras' => 'compra']);
Route::resource('ventas', VentaController::class)->parameters(['ventas' => 'venta']);
Route::resource('almacenes', AlmacenController::class)->parameters(['almacenes' => 'almacen']);
Route::resource('personas', PersonaController::class)->parameters(['personas' => 'persona']);
Route::resource('medicos', MedicoController::class)->parameters(['medicos' => 'medico']);
Route::resource('inventario', InventarioController::class)->parameters(['inventario' => 'inventario']);
Route::resource('lotes', LoteController::class)->parameters(['lote' => 'lote']);
Route::resource('requisiciones', RequisicionController::class)->parameters(['requisicion' => 'requisicion']);

Route::get('/productos/sucursal/{id}', [VentaController::class, 'productosPorSucursal']);
Route::get('ventas/productos/{idSucursal}', [VentaController::class, 'obtenerProductosPorSucursal'])->name('ventas.productos');
Route::get('/almacen/productos/{idSucursal}', [AlmacenController::class, 'getProductosPorSucursal']);
Route::get('/get-lotes/{idProducto}/{idSucursal}', [RequisicionController::class, 'getLotes'])->name('get.lotes');
Route::get('/inventario/{idProducto}/{idSucursal}', [InventarioController::class, 'show'])->name('inventario.show');
Route::get("solicitudes/cantidad", [solicitudController::class, 'cantidadDeSolicitudes'])->name('solicitudes.cantidad');
Route::resource('traslado', trasladoController::class)->parameters(['traslado' => 'traslado']);
Route::resource('solicitud', solicitudController::class)->parameters(['solicitud' => 'solicitud']);
//Route::get('/productos/sucursal/{id}', [VentaController::class, 'productosPorSucursal']);
//Route::get('ventas/productos/{idSucursal}', [VentaController::class, 'obtenerProductosPorSucursal'])->name('ventas.productos');
//Route::get('/almacen/productos/{idSucursal}', [AlmacenController::class, 'getProductosPorSucursal']);
Route::get('/productos-por-sucursal/{id_sucursal}', [trasladoController::class, 'obtenerProductos']);
Route::resource('bitacora', bitacoraController::class)->parameters(['bitacora' => 'bitacora']);



// Route::resource('traslados', TrasladoController::class)->parameters(['traslado' => 'traslado']);
// Route::get('/productos/sucursal/{id}', [VentaController::class, 'productosPorSucursal']);
// Route::get('ventas/productos/{idSucursal}', [VentaController::class, 'obtenerProductosPorSucursal'])->name('ventas.productos');
// Route::get('/almacen/productos/{idSucursal}', [AlmacenController::class, 'getProductosPorSucursal']);
// Route::get('/get-lotes/{idProducto}/{idSucursal}', [TrasladoController::class, 'getLotes'])->name('get.lotes');
// Route::get('/inventario/{idProducto}/{idSucursal}', [InventarioController::class, 'show'])->name('inventario.show');

