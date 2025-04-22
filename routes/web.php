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
use App\Http\Controllers\ImagenController;
use App\Http\Controllers\Inventario\InventarioController;
use App\Http\Controllers\Lote\LoteController;
use App\Http\Controllers\Medico\MedicoController;
use App\Http\Controllers\Persona\PersonaController;
use App\Http\Controllers\Reportes\ReporteVentasController;
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

//Rutas para cambio de estado.
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


//Route::post('/login', [AuthController::class, 'login'])->name('auth.login');
Route::post('/logout', [AuthController::class, 'logout'])->name('auth.logout');
Route::get('/dashboard/filtrarVentas', [Dashboard::class, 'filtrarVentas'])->name('dashboard.filtrarVentas');
Route::get('/dashboard/productos-mas-vendidos', [Dashboard::class, 'productosMasVendidos'])->name('dashboard.productosMasVendidos');

//Route::post('/auth/login', [AuthController::class, 'login']);
Route::get('/dashboard', [Dashboard::class, 'index'])->name('dashboard.index');
Route::resource('consultas', consultaController::class)->parameters(['consultas' => 'consulta']);
Route::resource('medicos', MedicoController::class)->parameters(['medicos' => 'medico']);
//Route::resource('categorias', CategoriaController::class)->parameters(['categorias' => 'categoria']);
Route::get('/productos/historico', [ProductoController::class, 'verHistorico'])->name('historico.precios');
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
Route::get('/almacenes/{id}/cambiar-alerta', [AlmacenController::class, 'alertStock'])->name('almacenes.alertStock');
Route::get('/productos/{id}/precio-porcentaje', [ProductoController::class, 'precioPorcentaje'])->name('productos.precio');
Route::patch('/productos/{id}/actualizar-precio', [ProductoController::class, 'actualizarPrecioPorcentaje'])->name('productos.actualizarprecio');
Route::patch('/almacen/{id}/alerta-stock', [AlmacenController::class, 'updateAlertStock'])->name('almacenes.updateAlertStock');
Route::resource('personas', PersonaController::class)->parameters(['personas' => 'persona']);
Route::resource('medicos', MedicoController::class)->parameters(['medicos' => 'medico']);
Route::resource('inventario', InventarioController::class)->parameters(['inventario' => 'inventario']);
Route::resource('lotes', LoteController::class)->parameters(['lote' => 'lote']);
Route::resource('Reporte_ventas', ReporteVentasController::class)->parameters(['Reporte_ventas' => 'Reporte_ventas']);
Route::get('/reporte/ventas/filtrar', [ReporteVentasController::class, 'filtrarPorFecha'])->name('Reporte_ventas.filtrarPorFecha');
Route::resource('requisiciones', RequisicionController::class)->parameters(['requisicion' => 'requisicion']);
Route::get("/ventas-informe", [ReporteVentasController::class, 'generateReport'])->name('ventas.informe');
Route::get('/productos/sucursal/{id}', [VentaController::class, 'productosPorSucursal']);
Route::get('ventas/productos/{idSucursal}', [VentaController::class, 'obtenerProductosPorSucursal'])->name('ventas.productos');
Route::get('/almacen/productos/{idSucursal}', [AlmacenController::class, 'getProductosPorSucursal']);
Route::get('/get-lotes/{idProducto}/{idSucursal}', [RequisicionController::class, 'getLotes'])->name('get.lotes');
Route::get('/inventario/{idProducto}/{idSucursal}', [InventarioController::class, 'show'])->name('inventario.show');
Route::get("solicitudes/cantidad", [solicitudController::class, 'cantidadDeSolicitudes'])->name('solicitudes.cantidad');
Route::get('/productos/stock/{id}/{sucursal}', [VentaController::class, 'obtenerStock']);
Route::post('/personas/from-ventas', [PersonaController::class, 'storeFromVentas'])->name('personas.storeFromVentas');

Route::resource('traslado', trasladoController::class)->parameters(['traslado' => 'traslado']);
Route::resource('solicitud', solicitudController::class)->parameters(['solicitud' => 'solicitud']);
//Route::get('/productos/sucursal/{id}', [VentaController::class, 'productosPorSucursal']);
//Route::get('ventas/productos/{idSucursal}', [VentaController::class, 'obtenerProductosPorSucursal'])->name('ventas.productos');
//Route::get('/almacen/productos/{idSucursal}', [AlmacenController::class, 'getProductosPorSucursal']);
Route::get('/productos-por-sucursal/{id_sucursal}', [trasladoController::class, 'obtenerProductos']);
Route::resource('bitacora', bitacoraController::class)->parameters(['bitacora' => 'bitacora']);

Route::post('/upload-image', [ImagenController::class, 'upload'])->name('upload.image');


// para la restriccion en ventas
Route::get('/personas/{persona}/restricciones', [PersonaController::class, 'obtenerRestricciones'])
     ->name('personas.restricciones');

Route::post('/personas/actualizar-restricciones', [PersonaController::class, 'actualizarRestricciones'])
     ->name('personas.actualizar-restricciones');


// Route::resource('traslados', TrasladoController::class)->parameters(['traslado' => 'traslado']);
// Route::get('/productos/sucursal/{id}', [VentaController::class, 'productosPorSucursal']);
// Route::get('ventas/productos/{idSucursal}', [VentaController::class, 'obtenerProductosPorSucursal'])->name('ventas.productos');
// Route::get('/almacen/productos/{idSucursal}', [AlmacenController::class, 'getProductosPorSucursal']);
// Route::get('/get-lotes/{idProducto}/{idSucursal}', [TrasladoController::class, 'getLotes'])->name('get.lotes');
// Route::get('/inventario/{idProducto}/{idSucursal}', [InventarioController::class, 'show'])->name('inventario.show');

