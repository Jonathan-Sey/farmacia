@extends('template')
@section('titulo','Panel')



@push('css')

@endpush

@section('contenido')
    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
        <a href="{{ route('productos.index') }}">
            <div class="w-auto h-40 bg-slate-50 rounded-md shadow-lg grid grid-cols-2 justify-center align-middle items-center text-center sm:grid-cols-1 lg:grid-cols-1 lg:p-2 sm:p-3  ">
                    <div class="flex flex-col items-center">
                        <i class='bx bxs-package text-7xl lg:text-6xl md:text-6xl sm:text-6xl' ></i>
                        <p class="uppercase text-lg font-bold">Productos</p>
                    </div>
                    <div class="uppercase font-bold sm:text-5xl lg:text-6xl xl:text-7xl">
                        <p class="text-5xl sm:text-3xl font-bold lg:text-3xl">{{$productos}}</p>
                    </div>
            </div>
        </a>
        <a href="{{ route('sucursales.index') }}">
            <div class="w-auto h-40 bg-slate-50 rounded-md shadow-lg grid grid-cols-2 justify-center align-middle items-center text-center sm:grid-cols-1 lg:grid-cols-1 lg:p-2  sm:p-3 ">
                    <div class="flex flex-col items-center">
                        <i class='bx bxs-building text-7xl lg:text-6xl  sm:text-6xl' ></i>
                        <p class="uppercase text-lg font-bold">Sucursales</p>
                    </div>
                    <div class="uppercase font-bold sm:text-5xl lg:text-6xl xl:text-7xl">
                        <p class="text-5xl sm:text-3xl font-bold lg:text-3xl">{{$sucursales}}</p>
                    </div>
            </div>
        </a>
        <a href="{{ route('compras.index') }}">
            <div class="w-auto h-40 bg-slate-50 rounded-md shadow-lg grid grid-cols-2 justify-center align-middle items-center text-center sm:grid-cols-1 lg:grid-cols-1 lg:p-2  sm:p-3 ">
                    <div class="flex flex-col items-center">
                        <i class='fa-solid fa-cart-shopping text-7xl lg:text-6xl sm:text-6xl' ></i>
                        <p class="uppercase text-lg font-bold">Compras</p>
                    </div>
                    <div class="uppercase font-bold sm:text-5xl lg:text-6xl xl:text-7xl">
                        <p class="text-5xl sm:text-3xl font-bold lg:text-3xl">{{$compras}}</p>
                    </div>
            </div>
        </a>
        <a href="{{ route('ventas.index') }}">
            <div class="w-auto h-40 bg-slate-50 rounded-md shadow-lg grid grid-cols-2 justify-center align-middle items-center text-center sm:grid-cols-1 lg:grid-cols-1 lg:p-2 sm:p-3">
                    <div class="flex flex-col items-center">
                        <i class='fa-solid fa-bag-shopping text-7xl lg:text-6xl sm:text-6xl' ></i>
                        <p class="uppercase text-lg font-bold">Ventas</p>
                    </div>
                    <div class="uppercase font-bold sm:text-5xl lg:text-6xl xl:text-7xl">
                        <p class="text-5xl sm:text-3xl font-bold lg:text-3xl">{{$NumeroVentas}}</p>
                    </div>
            </div>
        </a>
        <a href="{{ route('productos.index') }}">
            <div class="w-auto h-40 bg-slate-50 rounded-md shadow-lg grid grid-cols-2 justify-center align-middle items-center text-center sm:grid-cols-1 lg:grid-cols-1 lg:p-2 sm:p-3">
                    <div class="flex flex-col items-center">
                        <i class='fa-solid fa-heart-circle-check text-7xl lg:text-6xl sm:text-6xl' ></i>

                        <p class="uppercase text-lg font-bold">Servicios variados</p>
                    </div>
                    <div class="uppercase font-bold sm:text-5xl lg:text-6xl xl:text-7xl">
                        <p class="text-5xl sm:text-3xl font-bold lg:text-3xl">{{$servicios}} </p>
                    </div>
            </div>

        </a>
        <a href="{{ route('medicos.index') }}">
            <div class="w-auto h-40 bg-slate-50 rounded-md shadow-lg grid grid-cols-2 justify-center align-middle items-center text-center sm:grid-cols-1 lg:grid-cols-1 lg:p-2 sm:p-3">
                    <div class="flex flex-col items-center">
                        <i class='fa-solid fa-user-nurse text-7xl lg:text-6xl sm:text-6xl' ></i>

                        <p class="uppercase text-lg font-bold">Medicos</p>
                    </div>
                    <div class="uppercase font-bold sm:text-5xl lg:text-6xl xl:text-7xl">
                        <p class="text-5xl sm:text-3xl font-bold lg:text-3xl">{{$medicos}} </p>
                    </div>
            </div>

        </a>
    </div>


    <div class="grid gap-5 sm:grid-cols-1 lg:grid-cols-2 items-start mb-8">
        <div class=" max-h-[400px] overflow-x-auto bg-white p-2 rounded-lg shadow-lg text-center">
            <h2 class="text-2xl m-2 font-bold">Ultimas ventas</h2>
            <table class="table table-xs table-pin-rows table-pin-cols min-w-full sm:min-w-[400px]">
                <thead>
                    <tr>
                        <th>#</th>
                        <td>Cliente</td>
                        <td>Producto</td>
                        <td>Sucursal</td>
                        <td>Total</td>
                        <td>Ver</td>
                        <th>#</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($ventas as $venta)
                    <tr>
                        <th>{{ $loop->iteration }}</th>
                        <td>{{ $venta->persona->nombre }}</td>
                        <td>
                            @foreach($venta->productos as $producto)
                            {{ $producto->nombre }}@if(!$loop->last),
                            @endif
                            @endforeach
                        </td>
                        <td>{{ $venta->sucursal->nombre }} - {{ $venta->sucursal->ubicacion }}</td>
                        <td>{{ $venta->total }}</td>
                        <td><a href="{{ route('ventas.show', $venta->id) }}"><i class="fa-solid fa-eye"></i></a></td>
                        <th>{{ $loop->iteration }}</th>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div class="max-h-[800px] overflow-x-auto bg-white p-2 rounded-lg shadow-lg text-center">
            <h2 class="text-2xl m-2">Productos mas vendidos</h2>
            <div id="productosVendidos">

            </div>
        </div>
    </div>

    <div class="grid gap-5 grid-cols-1 items-start mb-8">
        <div class="max-h-[800px] overflow-x-auto bg-white p-2 rounded-lg shadow-lg text-center">
            <div class="flex flex-row gap-3 justify-between p-2">
                <h2 class="text-2xl m-2 font-bold">Ventas por mes</h2>
                <input type="month">
            </div>
            <div id="ventasMes">

            </div>
        </div>
    </div>


@endsection

@push('js')

        {{-- librerias para generar graficas --}}
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://code.highcharts.com/modules/exporting.js"></script>
        <script src="https://code.highcharts.com/modules/accessibility.js"></script>


        <script>
            document.addEventListener('DOMContentLoaded', function () {

            // diseño de la tabla
            Highcharts.chart('ventasMes', {
                chart: {
                    type: 'line'
                },
                title: {
                    text: 'Ventas del mes {{$mes}} del {{$año}}'
                },
                subtitle: {
                    text: 'Total Ventas: Q.{{$totalGeneral}}'
                },
                xAxis: {
                    categories:
                        {{json_encode($diasMes)}}
                    ,
                    accessibility: {
                        description: 'Dias del mes'
                    }
                },
                yAxis: {
                    title: {
                        text: 'Numero de ventas'
                    },
                    labels: {
                        format: '{value}'
                    }
                },
                tooltip: {
                    crosshairs: true,
                    shared: true
                },
                plotOptions: {
                    line: {
                        dataLabels:{
                            enabled:true
                        },
                        marker: {
                            radius: 4,
                            lineColor: '#666666',
                            lineWidth: 1
                        }
                    }
                },
                series: [{
                    name: 'Ventas',
                    marker: {
                        enabled: true,
                        symbol: 'circle'
                    },
                    data: {{json_encode($ventasPorDia)}}

                }]
            });
        });
        </script>

@endpush

