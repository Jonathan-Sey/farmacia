<?php

namespace App\Http\Controllers\Reportes;

use App\Http\Controllers\Controller;
use App\Models\Compra;
use App\Models\FichaMedica;
use App\Models\Inventario;
use App\Models\Persona;
use App\Models\Producto;
use App\Models\ReporteKardex;
use App\Models\Sucursal;
use App\Models\User;
use App\Models\Venta;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReporteVentasController extends Controller
{
    public function index()
    {

        //  dd(auth()->user());
        // pruebas con fechas del año actual
        $año = date('Y');
        $mes = date('m');

        $NumeroVentas = Venta::count();
        $nombreSucursales = Sucursal::all();


        // datos de ventas, esto para la tabla
        $ventas = Venta::with([
            'persona:id,nombre',
            'productos:id,nombre',
            'sucursal:id,nombre,ubicacion',
        ])->latest()->get();

        // fitrar fechas por el campo fecha_venta
        $ventasFiltro = Venta::with([
            'persona:id,nombre',
            'productos:id,nombre',
            'sucursal:id,nombre,ubicacion',
        ])->whereYear('fecha_venta', $año) //filtro por año
            ->whereMonth('fecha_venta', $mes) // filto pod mes
            ->latest()
            ->get();

        // generar los dias del mes
        $numDias = cal_days_in_month(CAL_GREGORIAN, $mes, $año); // total de dias de ese mes
        $diasMes =  range(1, $numDias); //cantidad de dias de ese mes

        $ventasPorDia = [];
        $totalGeneral = 0;

        foreach ($diasMes as $dia) {
            $ventasDelDia = $ventasFiltro->filter(function ($venta) use ($año, $mes, $dia) {
                return \Carbon\Carbon::parse($venta->fecha_venta)->format('Y-m-d') === "$año-" . str_pad($mes, 2, '0', STR_PAD_LEFT) . "-" . str_pad($dia, 2, '0', STR_PAD_LEFT);
            });
            $totalDia = $ventasDelDia->sum('total');
            $ventasPorDia[] = $totalDia;
            $totalGeneral += $totalDia;
        }

        //return $productos;

        return view('reportes.ventas', compact('NumeroVentas', 'ventas', 'ventasFiltro', 'ventasPorDia', 'totalGeneral', 'diasMes', 'año', 'mes', 'nombreSucursales'));
    }

    public function create()

    {
        $reporte = ReporteKardex::with([
            'producto',

            'usuario'
        ])->get();
        return view('reportes.Kardex', compact('reporte'));
    }

    // Filtrar por fecha
    public function filtrarPorFecha()
    {

        return view('reportes.venstasFecha');
    }


    public function generateReport(Request $request)
    {
        $query = DB::table('venta as v')
            ->join('detalle_venta as dv', 'dv.id_venta', '=', 'v.id')
            ->join('producto as p', 'dv.id_producto', '=', 'p.id')
            ->join('sucursal as s', 'v.id_sucursal', '=', 's.id')
            ->join('users as u', 'v.id_usuario', '=', 'u.id')
            ->join('persona as per', 'v.id_persona', '=', 'per.id')
            ->select(
                'v.id as venta_id',
                'v.fecha_venta',
                'v.impuesto',
                'dv.id as detalle_id',
                'dv.id_producto',
                'p.nombre as nombre_producto',
                'dv.cantidad',
                'dv.precio',
                DB::raw('(dv.cantidad * dv.precio) as subtotal'),
                'v.id_sucursal',
                's.nombre as nombre_sucursal',
                'v.id_usuario',
                'u.name as nombre_usuario',
                'v.id_persona',
                'per.nombre as nombre_persona',
                'v.estado as estado_venta',
                'dv.estado as estado_detalle'
            );

        // Filtrar por día específico
        if ($request->has('fecha')) {
            $query->whereDate('v.fecha_venta', $request->fecha);
        }

        // Filtrar por mes y año
        if ($request->has('mes')) {
            $query->whereMonth('v.fecha_venta', date('m', strtotime($request->mes)))
                ->whereYear('v.fecha_venta', date('Y', strtotime($request->mes)));
        }

        // Filtrar por año
        if ($request->has('año')) {
            $query->whereYear('v.fecha_venta', $request->año);
        }

        // Filtrar por rango de fechas
        if ($request->has('fechaInicio') && $request->has('fechaFin')) {
            $query->whereBetween('v.fecha_venta', [$request->fechaInicio, $request->fechaFin]);
        }

        // Ordenar por fecha de venta descendente
        $ventas = $query->orderBy('v.fecha_venta', 'DESC')->get();

        return response()->json($ventas);
    }

    // Filtrar por sucursal
    public function filtrarPorSucursal()
    {
        $sucursales = Sucursal::all();
        return view('reportes.ventasSucursal', compact('sucursales'));
    }

    public function generateReportSucursal(Request $request)
    {
        $query = DB::table('venta as v')
            ->join('detalle_venta as dv', 'dv.id_venta', '=', 'v.id')
            ->join('producto as p', 'dv.id_producto', '=', 'p.id')
            ->join('sucursal as s', 'v.id_sucursal', '=', 's.id')
            ->join('users as u', 'v.id_usuario', '=', 'u.id')
            ->join('persona as per', 'v.id_persona', '=', 'per.id')
            ->select(
                'v.id as venta_id',
                'v.fecha_venta',
                'v.impuesto',
                'dv.id as detalle_id',
                'dv.id_producto',
                'p.nombre as nombre_producto',
                'dv.cantidad',
                'dv.precio',
                DB::raw('(dv.cantidad * dv.precio) as subtotal'),
                'v.id_sucursal',
                's.nombre as nombre_sucursal',
                'v.id_usuario',
                'u.name as nombre_usuario',
                'v.id_persona',
                'per.nombre as nombre_persona',
                'v.estado as estado_venta',
                'dv.estado as estado_detalle'
            );

        // Filtrar por día específico
        /*  if ($request->has('fecha')) {
            $query->whereDate('v.fecha_venta', $request->fecha);
        }

        // Filtrar por mes y año
        if ($request->has('mes')) {
            $query->whereMonth('v.fecha_venta', date('m', strtotime($request->mes)))
                ->whereYear('v.fecha_venta', date('Y', strtotime($request->mes)));
        }

        // Filtrar por año
        if ($request->has('año')) {
            $query->whereYear('v.fecha_venta', $request->año);
        }

          // Ordenar por fecha de venta descendente
        $ventas = $query->orderBy('v.fecha_venta', 'DESC')->get();*/

        // Filtrar por rango de fechas
        if ($request->has('fechaInicio') && $request->has('fechaFin')) {
            $query->whereBetween('v.fecha_venta', [$request->fechaInicio, $request->fechaFin]);
        }



        if ($request->has('sucursal')) {
            $query->where('v.id_sucursal', $request->sucursal);
        }

        $ventas = $query->orderBy('v.fecha_venta', 'DESC')->get();

        return response()->json($ventas);
    }

    //filtrar por usuario

    public function filtrarPorUsuario()
    {
        $usuarios = User::all();
        return view('reportes.ventasUsuarios', compact('usuarios'));
    }

    public function generateReportUsuario(Request $request)
    {
        $query = DB::table('venta as v')
            ->join('detalle_venta as dv', 'dv.id_venta', '=', 'v.id')
            ->join('producto as p', 'dv.id_producto', '=', 'p.id')
            ->join('sucursal as s', 'v.id_sucursal', '=', 's.id')
            ->join('users as u', 'v.id_usuario', '=', 'u.id')
            ->join('persona as per', 'v.id_persona', '=', 'per.id')
            ->select(
                'v.id as venta_id',
                'v.fecha_venta',
                'v.impuesto',
                'dv.id as detalle_id',
                'dv.id_producto',
                'p.nombre as nombre_producto',
                'dv.cantidad',
                'dv.precio',
                DB::raw('(dv.cantidad * dv.precio) as subtotal'),
                'v.id_sucursal',
                's.nombre as nombre_sucursal',
                'v.id_usuario',
                'u.name as nombre_usuario',
                'v.id_persona',
                'per.nombre as nombre_persona',
                'v.estado as estado_venta',
                'dv.estado as estado_detalle'
            );

              if ($request->has('fechaInicio') && $request->has('fechaFin')) {
            $query->whereBetween('v.fecha_venta', [$request->fechaInicio, $request->fechaFin]);
        }

        if ($request->has('usuario')) {
            $query->where('v.id_usuario', $request->usuario);
        }

        $ventas = $query->orderBy('v.fecha_venta', 'DESC')->get();

        return response()->json($ventas);
    }

    public function filtrarProducto()
    {
        $sucursales = Sucursal::all();
        $productos = Inventario::with([
            'producto:id,nombre,precio_venta',
            'bodega:id,nombre,ubicacion'
        ])->get();
        return view('reportes.reporteProducto', compact('productos', 'sucursales'));
    }

    public function generateReportProducto(Request $request)
    {
        $sucursalId = $request->get('sucursal_id');
        $semana = $request->get('semana');

        $query = DB::table('almacen')
            ->join('producto', 'almacen.id_producto', '=', 'producto.id')
            ->join('sucursal', 'almacen.id_sucursal', '=', 'sucursal.id')

            ->select(
                'sucursal.nombre as sucursal',
                'producto.nombre as producto',

                DB::raw('WEEK(almacen.created_at, 3) as semana'),
                DB::raw('SUM(almacen.cantidad) as cantidad_total'),
                DB::raw('SUM(almacen.cantidad * producto.precio_venta) as valor_total_producto')
            );

        if ($sucursalId) {
            $query->where('almacen.id_sucursal', $sucursalId);
        }

        if ($semana) {
            $query->whereRaw('WEEK(almacen.created_at, 3) = ?', [$semana]);
        }

        $resultado = $query
            ->groupBy('sucursal.nombre', 'producto.nombre', DB::raw('WEEK(almacen.created_at, 3)'))
            ->orderBy('semana')
            ->get();

        return response()->json($resultado);
    }

    // public function filtrarPacientes()
    // {
    //     $personas = FichaMedica::with(['detalleMedico.usuario', 'persona'])->get();
    //     return view('reportes.pacientes', compact('personas'));
    // }
    public function filtrarPacientes()
    {
        $fichasAgrupadas  = Persona::with(['fichasMedicas.detalleMedico.usuario'])->get();
        return view('reportes.pacientes', compact('fichasAgrupadas'));
    }



}
