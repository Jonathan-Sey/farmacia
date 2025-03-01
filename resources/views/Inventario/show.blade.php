@extends('template')

@section('titulo', 'Detalle de Lotes en Inventario')

@section('contenido')
<div class="card">
    <h2 class="text-xl font-bold mb-4">Lotes Originales</h2>
    <div class="overflow-x-auto">
        <table class="table table-md table-pin-rows table-pin-cols">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Número de Lote</th>
                    <th>Producto</th>
                    <th>Cantidad Original</th>
                    <th>Fecha de Vencimiento</th>
                </tr>
            </thead>
            <tbody>
                @php $iteracion = 0; @endphp
                @foreach($lotesOriginales as $lote)
                @php $iteracion++; @endphp
                <tr>
                    <th>{{ $iteracion }}</th>
                    <td>{{ $lote->numero_lote }}</td>
                    <td>{{ $lote->producto->nombre }}</td>
                    <td>{{ $lote->cantidad }}</td>
                    <td>{{ $lote->fecha_vencimiento }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <h2 class="text-xl font-bold mt-8 mb-4">Lotes Disponibles en Inventario</h2>
    <div class="overflow-x-auto">
        <table class="table table-md table-pin-rows table-pin-cols">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Número de Lote</th>
                    <th>Producto</th>
                    <th>Sucursal</th>
                    <th>Cantidad Disponible</th>
                    <th>Fecha de Vencimiento</th>
                </tr>
            </thead>
            <tbody>
                @php $iteracion = 0; @endphp
                @foreach($lotesDisponibles as $inventario)
                @php $iteracion++; @endphp
                <tr>
                    <th>{{ $iteracion }}</th>
                    <td>{{ $inventario->lote->numero_lote }}</td>
                    <td>{{ $inventario->producto->nombre }}</td>
                    <td>{{ $inventario->sucursal->ubicacion }}</td>
                    <td>{{ $inventario->cantidad }}</td>
                    <td>{{ $inventario->lote->fecha_vencimiento }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
