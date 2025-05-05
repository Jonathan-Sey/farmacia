@extends('template')

@section('titulo','Productos vencidos')

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.0/css/buttons.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css">
@endpush

@section('contenido')
<x-data-table>
    <x-slot name="thead">
        <thead class="text-white font-bold">
            <tr class="bg-slate-600">
                <th class="px-6 py-3 text-left font-medium uppercase tracking-wider">Código</th>
                <th class="px-6 py-3 text-left font-medium uppercase tracking-wider">Producto</th>
                <th class="px-6 py-3 text-left font-medium uppercase tracking-wider">Imagen</th>
                <th class="px-6 py-3 text-left font-medium uppercase tracking-wider">Sucursal</th>
                <th class="px-6 py-3 text-left font-medium uppercase tracking-wider">Cantidad</th>
                <th class="px-6 py-3 text-left font-medium uppercase tracking-wider">Tipo</th>
               
            </tr>
        </thead>
    </x-slot>

    <x-slot name="tbody">
        <tbody>
            @foreach ($productosVencidos as $almacen)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap text-left">{{ $almacen->producto->codigo }}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-left"> {{ $almacen->producto->nombre }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-left">
                   
                    @if ($almacen->producto->imagen)
                        <div class="mt-2">
                            <img src="{{ asset('uploads/' . $almacen->producto->imagen) }}" alt="{{ $almacen->producto->nombre }}" class="w-16 h-16 object-cover rounded">
                        </div>
                    @else
                        <span class="text-gray-500 block">Sin imagen</span>
                    @endif
                </td>

                <td class="px-6 py-4 whitespace-nowrap text-left">
                    {{ $almacen->sucursal->nombre }}
                </td>

                <td class="px-6 py-4 whitespace-nowrap text-left">
                    @if ($almacen->cantidad <= $almacen->producto->alerta_stock)
                        <span class="text-red-500 font-bold">{{ $almacen->cantidad }}</span>
                    @else
                        <span class="text-green-500 font-bold">{{ $almacen->cantidad }}</span>
                    @endif
                </td>

                <td class="px-6 py-4 whitespace-nowrap text-left">
                    @if ($almacen->producto->tipo == 1)
                        <span class="text-green-500 font-bold">Producto</span>
                    @else
                        <span class="text-red-500 font-bold">Servicio</span>
                    @endif
                </td>

             
            </tr>
            @endforeach
        </tbody>
    </x-slot>
</x-data-table>
@endsection

@push('js')
{{-- Aquí agregarías el JS para cambiar estado si fuera necesario --}}
@endpush
