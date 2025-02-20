@extends('template')
@section('titulo', 'Trasferencia de articulos')

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.0/css/buttons.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css">
@endpush

@section('contenido')


<div class="flex justify-between items-center">
    <div>

        {{-- Botón para crear nueva sucursal --}}
        <a href="{{ route('traslado.create') }}">
            <button class="btn btn-success text-white font-bold uppercase m-2">
                Crear
            </button>
        </a>

        {{-- Botón para crear nueva sucursal --}}
        <a href="{{ route('solicitud.create') }}">
            <button class="btn btn-success text-white font-bold uppercase m-2">
                solicitar producto
            </button>
        </a>
    </div>

    <div class="flex justify-center items-center  bg-gray-100">

    <a href="{{ route('solicitud.index') }}">
         <button class="relative inline-flex items-center p-3 text-sm font-medium text-center text-white bg-blue-700 rounded-lg hover:bg-blue-800 focus:ring-4 focus:outline-none focus:ring-blue-300" aria-label="5 notifications">
            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
            </svg>
            <span class="sr-only">Notifications</span>
            <div class="absolute inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-red-500 border-2 border-white rounded-full -top-2 -right-2">
                {{$cantidadDeSolicitudes}}
            </div>
        </button> 
    </a>
      
    </div>
</div>
<x-data-table>
    <x-slot name="thead">
        <thead class="text-white font-bold">
            <tr class="bg-slate-600">
                <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider">Id</th>
                <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider">sucursal original</th>
                <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider">sucursal de destino</th>
                <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider">producto</th>
                <th scope="col" class="px-6 py-3 text-center font-medium uppercase tracking-wider">cantidad transferida</th>
                <th scope="col" class="px-6 py-3 text-center font-medium uppercase tracking-wider">usuario</th>
                <th scope="col" class="px-6 py-3 text-center font-medium uppercase tracking-wider">Fecha</th>
                <th scope="col" class="px-6 py-3 text-center font-medium uppercase tracking-wider">activo</th>
                <th scope="col" class="px-6 py-3 text-center font-medium uppercase tracking-wider">acciones</th>
            </tr>
        </thead>
    </x-slot>

    <x-slot name="tbody">
        <tbody>
            @foreach ($traslado as $traslado)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">{{ $traslado->id }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $traslado->sucursal1->nombre}}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $traslado->sucursal2->nombre}}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $traslado->producto->nombre }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $traslado->cantidad }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $traslado->user->name }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $traslado->created_at }}</td>

                <td class="px-6 py-4 whitespace-nowrap text-center">
                    <a href="#" class="estado" data-id="{{ $traslado->id }}" data-estado="{{ $traslado->estado }}">
                        @if ($traslado->estado == 1)
                        <span class="text-green-500 font-bold">Activo</span>
                        @else
                        <span class="text-red-500 font-bold">Inactivo</span>
                        @endif
                    </a>
                </td>
                <td class="flex gap-2 justify-center">
                    {{-- Botón Editar --}}
                    <form action="{{ route('traslado.edit', ['traslado' => $traslado->id]) }}" method="GET">
                        @csrf
                        <button type="submit" class="btn btn-primary font-bold uppercase btn-sm">
                            <i class="fas fa-edit"></i>
                        </button>
                    </form>

                    {{-- Botón Eliminar --}}
                    <button type="button" class="btn btn-warning font-bold uppercase eliminar-btn btn-sm" data-id="{{ $traslado->id }}" data-info="{{ $traslado->nombre }}">
                        <i class="fas fa-trash"></i>
                    </button>

                    {{-- Formulario oculto para eliminación --}}
                    <form id="form-eliminar{{ $traslado->id }}" action="{{ route('traslado.destroy', $traslado->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                    </form>
                </td>
            </tr>
            @endforeach
        </tbody>
    </x-slot>

</x-data-table>
@endsection

@push('js')
@push('js')

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script src="https://cdn.datatables.net/responsive/3.0.3/js/dataTables.responsive.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.3/js/responsive.bootstrap5.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.0/js/dataTables.buttons.js"></script>




{{-- botones --}}
<script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.html5.min.js">
    //botones en general
</script>
<script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.print.min.js">
    //imprimir
</script>
<script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.colVis.min.js">
    //fltrar columnas
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js">
    //pdf
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js">
    //copiar
</script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js">
    //excel
</script>

<script src=""></script>

<script>
    $(document).ready(function() {
        $('#example').DataTable({
            responsive: true,
            order: [5, 'desc'],
            language: {
                url: '/js/i18n/Spanish.json',
            },
            layout: {
                topStart: {
                    buttons: ['copy', 'excel', 'pdf', 'print', 'colvis']
                }
            },
            columnDefs: [{
                    responsivePriority: 1,
                    targets: 0
                },
                {
                    responsivePriority: 2,
                    targets: 1
                },
                {
                    responsivePriority: 3,
                    targets: 2
                },
            ],
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

{{-- Alerta de registro exitoso --}}
@if (session('success'))
<script>
    const Toast = Swal.mixin({
        toast: true,
        position: "top-end",
        showConfirmButton: false,
        timer: 1600,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.onmouseenter = Swal.stopTimer;
            toast.onmouseleave = Swal.resumeTimer;
        }
    });
    document.addEventListener('DOMContentLoaded', function() {
        Toast.fire({
            icon: "success",
            title: "{{ session('success') }}"
        });
    });
</script>
@endif

{{-- Cambio de estado --}}
<script>
    $(document).ready(function() {
        $('.estado').click(function(e) {
            e.preventDefault();
            var Id = $(this).data('id');
            var estado = $(this).data('estado');

            $.ajax({
                url: '/sucursales/' + Id,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'PATCH',
                    status: estado == 1 ? 0 : 1
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert('Error al cambiar el estado');
                    }
                }
            });
        });
    });
</script>

{{-- Modal para eliminar  --}}
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const deleteButtons = document.querySelectorAll('.eliminar-btn');

        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const Id = this.getAttribute('data-id');
                const nombre = this.getAttribute('data-info');
                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "¡Deseas eliminar! " + nombre,
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sí, ¡elimínalo!",
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('form-eliminar' + Id).submit();
                    }
                });
            });
        });
    });
</script>
@endpush