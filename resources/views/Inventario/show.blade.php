@extends('template')

@section('titulo', 'Detalle de Lotes en Inventario')

@section('contenido')
<div class="card">
    <h2 class="text-xl font-bold mb-4">Lotes Originales</h2>
    <div class="overflow-x-auto max-h-[500px]">
        <table class="table table-md table-pin-rows table-pin-cols">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Número de Lote</th>
                    <th>Producto</th>
                    <th>Cantidad Original</th>
                    <th>Precio de compra</th>
                    <th>Fecha de Vencimiento</th>
                </tr>
            </thead>
            <tbody>
                @php $iteracion = 0; @endphp
                @foreach($lotesOriginales as $lote)
                @php
                    $fechaActual = \Carbon\Carbon::now(); // Fecha actual
                        $fechaCaducidad = \Carbon\Carbon::parse($lote->fecha_vencimiento); // Convierte la fecha de caducidad a Carbon
                        $diferenciaDias = $fechaActual->diffInDays($fechaCaducidad, false); // Diferencia en días (negativo si ya caducó)
                @endphp
                @php $iteracion++; @endphp
                <tr>
                    <th>{{ $iteracion }}</th>
                    <td>{{ $lote->numero_lote }}</td>
                    <td>{{ $lote->producto->nombre }}</td>
                    <td>{{ $lote->cantidad }}</td>
                    <td>{{ $lote->precio_compra }}</td>
                    {{-- <td>{{ $lote->fecha_vencimiento }}</td> --}}
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($diferenciaDias < 0)
                            <span class="text-red-500 font-bold">
                                {{ $fechaCaducidad->format('d/m/Y') }}
                                     (Caducado)
                            </span>
                        @elseif($diferenciaDias <= 30)
                            <span class= "text-yellow-500 font-bold">
                                {{ $fechaCaducidad->format('d/m/Y') }}
                                    (Próximo a caducar)
                            </span>
                        @else
                            <span class="text-green-500 font-bold">
                                {{ $fechaCaducidad->format('d/m/Y') }}
                                    (Vigente)
                            </span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <h2 class="text-xl font-bold mt-8 mb-4">Lotes Disponibles en Inventario</h2>
    <div class="overflow-x-auto max-h-[500px]">
        <table class="table table-md table-pin-rows table-pin-cols">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Número de Lote</th>
                    <th>Producto</th>
                    <th>Bodega</th>
                    <th>Cantidad Disponible</th>
                    <th>Precio de compra</th>
                    <th>Fecha de Vencimiento</th>
                </tr>
            </thead>
            <tbody>
                @php $iteracion = 0; @endphp
                @foreach($lotesDisponibles as $inventario)
                @php
                     $fechaActual = \Carbon\Carbon::now(); // Fecha actual
                        $fechaCaducidad = \Carbon\Carbon::parse($inventario->lote->fecha_vencimiento); // Convierte la fecha de caducidad a Carbon
                        $diferenciaDias = $fechaActual->diffInDays($fechaCaducidad, false); // Diferencia en días (negativo si ya caducó)
                @endphp
                @php $iteracion++; @endphp
                <tr>
                    <th>{{ $iteracion }}</th>
                    <td>{{ $inventario->lote->numero_lote }}</td>
                    <td>{{ $inventario->producto->nombre }}</td>
                    <td>{{ $inventario->bodega->nombre }}</td>
                    <td>{{ $inventario->cantidad }}</td>
                    <td>{{ $inventario->lote->precio_compra }}</td>
                    {{-- <td>{{ $inventario->lote->fecha_vencimiento }}</td> --}}
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if($diferenciaDias < 0)
                            <span class="text-red-500 font-bold">
                                {{ $fechaCaducidad->format('d/m/Y') }}
                                     (Caducado)
                            </span>
                        @elseif($diferenciaDias <= 30)
                            <span class= "text-yellow-500 font-bold">
                                {{ $fechaCaducidad->format('d/m/Y') }}
                                    (Próximo a caducar)
                            </span>
                        @else
                            <span class="text-green-500 font-bold">
                                {{ $fechaCaducidad->format('d/m/Y') }}
                                    (Vigente)
                            </span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
