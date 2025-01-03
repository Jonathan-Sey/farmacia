@extends('template')

@section('titulo','Productos')

@push('css')

@endpush

@section('contenido')
    <a href="{{ route('productos.create') }}">
        <button class="btn btn-success text-white font-bold uppercase">
            Crear
        </button>
    </a>

    <h1>Lista de Productos</h1>

    <x-data-table>
        <x-slot name="thead">
            <thead class="bg-blue-950 text-white rounded-lg">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider" >Código</th>
                    <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider" >Nombre</th>
                    <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider" >Precio</th>
                    <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider" >Estado</th>
                    <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider" >Categoría</th>
                </tr>
            </thead>
        </x-slot>

        <x-slot name="tbody">
            <tbody>
                @foreach ($productos as $producto)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{$producto->codigo}}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{$producto->nombre}}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{$producto->precio_venta}}</td>
                    <td class="px-6 py-4 whitespace-nowrap">
                        <a href="#" class="estado" data-id="{{ $producto->id}}" data-estado="{{$producto->estado}}">
                            @if ($producto->estado == 1)
                                <span class="text-green-500 font-bold">Activo</span>
                            @else
                                <span class="text-red-500 font-bold">Inactivo</span>
                            @endif
                        </a>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap">{{$producto->categoria->nombre}}</td>
                </tr>
                @endforeach
            </tbody>
        </x-slot>
    </x-data-table>
@endsection

@push('js')


<script>
    $(document).ready(function() {
        $('#example').DataTable({
            responsive: true,
            language: {
                search: "Buscar:",
                lengthMenu: "Mostrar _MENU_ registros",
                info: "Mostrando _START_ a _END_ de _TOTAL_ registros",
                paginate: {
                    previous: "Anterior",
                    next: "Siguiente",
                },
            },
            drawCallback: function() {
                // Esperar un momento para asegurarse de que los botones se hayan cargado
                setTimeout(function() {
                    // Seleccionar los botones de paginación y agregar clases de DaisyUI
                    $('a.paginate_button').addClass('btn btn-sm btn-primary mx-1'); // Todos los botones
                    $('a.paginate_button.current').removeClass('btn-gray-800').addClass('btn btn-sm btn-primary'); // Resaltar la página actual
                }, 100); // Espera 100 ms antes de aplicar las clases
            },
        });
    });
</script>
@endpush
