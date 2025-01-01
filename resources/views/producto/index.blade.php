@extends('template')

@section('titulo','Productos')

@section('contenido')
        <a href="{{route('productos.create')}}">
            <button class="btn btn-success text-white font-bold uppercase">
                crear
            </button>
        </a>

        <h1>Lista de Productos</h1>
        <table class="w-full">
            <thead>
                <tr class="bg-slate-300  ">
                    <th class="p-3 ">Código</th>
                    <th>Nombre</th>
                    <th>Precio</th>
                    <th>Estado</th>
                    <th>Categoría</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($productos as $producto)
                <tr class="bg-white text-center">
                    <td>{{ $producto->codigo }}</td>
                    <td>{{ $producto->nombre }}</td>
                    <td>{{ $producto->precio_venta }}</td>
                    <a href="#" class="estado" data-id="{{ $producto->id}}" data-estado="{{$producto->estado}}">
                        @if ($producto->estado == 1)
                            <td>
                                <span class="text-green-500 font-bold" >Ativo</span>
                            </td>
                            @else
                            <td>
                                <span class="text-red-500 font-bold" >Inactivo</span>
                            </td>
                        @endif
                    </a>
                    <td>{{ $producto->categoria->nombre }}</td>

                </tr>
                @endforeach
            </tbody>
        </table>

@endsection
