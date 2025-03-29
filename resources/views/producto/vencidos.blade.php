@extends('template')

@section('titulo','Productos vencidos ')

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.0/css/buttons.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css">

@endpush

@section('contenido')
<x-data-table>
        <x-slot name="thead">
            <thead class=" text-white font-bold">
                <tr class="bg-slate-600  ">
                    <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider" >Código</th>
                    <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider" >Producto</th>
                    <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider" >sucursal</th>
                    <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider" >cantidad</th>
                    <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider" >Estado</th>


                </tr>
            </thead>
        </x-slot>

        <x-slot name="tbody">
            <tbody>
                @foreach ($almacenes as $almacen)
                <tr>
                    <td class=" text-left px-6 py-4 whitespace-nowrap">{{$almacen->producto->codigo}}</td>
                    <td class=" text-left px-6 py-4 whitespace-nowrap">{{$almacen->producto->nombre}}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        @if ($almacen->producto->imagen)
                            <img src="{{ asset('uploads/' . $almacen->producto->imagen) }}" alt="{{ $almacen->producto->nombre }}" class="w-16 h-16 object-cover rounded">
                        @else
                            <span class="text-gray-500">Sin imagen</span>
                        @endif
                    </td>
                    <td class=" px-6 py-4 whitespace-nowrap text-center">
                        <a href="#" class="estado">
                            @if ($almacen->producto->tipo == 1)
                                <span class="text-green-500 font-bold">Producto</span>
                            @else
                                <span class="text-red-500 font-bold">Servicio</span>
                            @endif
                        </a>
                    </td>
                    <td class=" text-left px-6 py-4 whitespace-nowrap">{{$almacen->sucursal->nombre}}</td>
                    {{-- muestra la cantidad que hay y usa la cantidad minima que se tiene en el campo alerta_stock en la base de datos para mostrar el alerta de poco stock --}}
                    <td class="text-left px-6 py-4 whitespace-nowrap">
                            @if ($almacen->producto->tipo == 2)
                            <span class="text-green-500 font-bold">{{$almacen->cantidad}}</span>
                            @else
                                <span class="{{ $almacen->cantidad <= $almacen->alerta_stock ? 'text-red-500 font-bold' : 'text-green-500 font-bold' }}">
                                    {{$almacen->cantidad}}
                                    @if ($almacen->cantidad <= $almacen->alerta_stock)
                                        <span class="text-red-400">(Poco stock)</span>
                                    @endif
                                </span>
                            @endif
                    </td>
                    <td class=" px-6 py-4 whitespace-nowrap text-center">
                        <a class="estado" data-id="{{ $almacen->id}}" data-estado="{{$almacen->estado}}">
                            @if ($almacen->estado == 1)
                                <span class="text-green-500 font-bold">Activo</span>
                            @else
                                <span class="text-red-500 font-bold">Inactivo</span>
                            @endif
                        </a>
                    </td>

                    <td class="flex gap-2 justify-center">
                        @if ($almacen->producto->tipo == 2)
                            <form action="{{route('almacenes.edit',['almacen'=>$almacen->id])}}" method="GET">
                                @csrf
                                <button type="submit" class="btn btn-primary font-bold uppercase btn-sm">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </form>
                        @endif

                       {{--  boton para modificar en que cantidad debe lanzar la alerta --}}
                        @if ($almacen->producto->tipo == 1)
                        <a href="{{ route('almacenes.alertStock', $almacen->id) }}" class="btn bg-red-500 font-bold uppercase btn-sm">
                            <i class='bx bx-bell'></i>
                        </a>
                        @endif

                        <button type="button" class="btn btn-warning font-bold uppercase cambiar-estado-btn btn-sm" data-id="{{ $almacen->id }}" data-estado="{{ $almacen->estado }}" data-info="{{ $almacen->nombre }}">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </x-slot>
    </x-data-table>
@endsection

@push('js')

@endpush