@extends('template')

@section('titulo', 'Lotes')

@section('contenido')
<div class="card">
    <div class="overflow-x-auto">
        {{-- <table class="table table-xs table-pin-rows table-pin-cols"> --}}
           <table class="table  table-md table-pin-rows table-pin-cols">
            <thead>
                <tr>
                    <th></th>
                    <th>#Lote</th>
                    <th>Producto</th>
                    <th>Sucursal</th>
                    <th>Cantidad</th>
                    <th>Fecha de Vencimiento</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @php $iteracion = 0; @endphp
                @foreach($lotes as $lote)
                @foreach($lote->inventarios as $inventario)
                @php $iteracion ++; @endphp
                <tr>
                    <th>{{ $iteracion }}</th>
                    <td class="bg-white" >{{ $lote->numero_lote }}</td>
                    <td class="bg-white" >{{ $lote->producto->nombre }}</td>
                    <td class="bg-white" >{{ $inventario->sucursal->ubicacion }}</td>
                    <td class="bg-white" >{{ $lote->cantidad }}</td>
                    <td class="bg-white" >{{ $lote->fecha_vencimiento }}</td>
                    <th>{{ $iteracion }}</th>
                </tr>
                @endforeach
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
