@extends('template')

@section('titulo', 'Roles')

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.0/css/buttons.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css">


@endpush

@section('contenido')

{{-- Botón para crear nuevo rol --}}
<a href="{{ route('roles.create') }}">
    <button class="btn btn-success text-white font-bold uppercase">
        Crear
    </button>
</a>

<x-data-table>
    <x-slot name="thead">
        <thead class="text-white font-bold">
            <tr class="bg-slate-600">
                <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider">Id</th>
                <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider">Nombre</th>
                <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider">Descripción</th>
                <th scope="col" class="px-6 py-3 text-center font-medium uppercase tracking-wider">Estado</th>
                <th scope="col" class="px-6 py-3 text-center font-medium uppercase tracking-wider">Acciones</th>
            </tr>
        </thead>
    </x-slot>

    <x-slot name="tbody">
        <tbody>
            @foreach ($roles as $rol)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">{{ $rol->id }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $rol->nombre }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $rol->descripcion }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    <a class="estado" data-id="{{ $rol->id }}" data-estado="{{ $rol->estado }}">
                        @if ($rol->estado == 1)
                            <span class="text-green-500 font-bold">Activo</span>
                        @else
                            <span class="text-red-500 font-bold">Inactivo</span>
                        @endif
                    </a>
                </td>
                <td class="flex gap-2 justify-center">
                    {{-- Botón Editar --}}
                    <form action="{{ route('roles.edit', ['rol' => $rol->id]) }}" method="GET">
                        @csrf
                        <button type="submit" class="btn btn-primary font-bold uppercase btn-sm">
                            <i class="fas fa-edit"></i>
                        </button>
                    </form>
                  {{-- Botón Cambiar estado --}}
                    <button type="button" class="btn btn-warning font-bold uppercase cambiar-estado-btn btn-sm" data-id="{{ $rol->id }}" data-estado="{{ $rol->estado }}" data-info="{{ $rol->nombre }}">
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdn.datatables.net/responsive/3.0.3/js/dataTables.responsive.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.3/js/responsive.bootstrap5.js"></script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/dataTables.buttons.js"></script>




    {{-- botones --}}
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.html5.min.js">//botones en general</script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.print.min.js">//imprimir</script>
    <script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.colVis.min.js">//fltrar columnas</script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js">//pdf</script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js">//copiar</script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js">//excel</script>

<script src=""></script>

<script>
$(document).ready(function() {
    $('#example').DataTable({
        responsive: true,
        order: [5,'desc'],
        language: {
            url: '/js/i18n/Spanish.json',
        },
        layout: {
            topStart: {
                    buttons: ['copy', 'excel', 'pdf', 'print', 'colvis']
                }
            },
            columnDefs: [
                { responsivePriority: 1, targets: 0 },
                { responsivePriority: 2, targets: 1 },
                { responsivePriority: 3, targets: 2 },
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

@if (session('success'))
<script>
    //registro exitoso
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
        Toast.fire({ icon: "success", title: "{{ session('success') }}" });
    });
</script>
@endif

{{-- cambio de estado --}}
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const changeStateButtons = document.querySelectorAll('.cambiar-estado-btn');

        changeStateButtons.forEach(button => {
            button.addEventListener('click', function () {
                const Id = this.getAttribute('data-id');
                let estado = this.getAttribute('data-estado'); // Tomamos el estado actual del data-estado
                const nombre = this.getAttribute('data-info'); // Este es informacion

                Swal.fire({
                    title: "¿Estás seguro?",
                    text: "¡Deseas cambiar el estado de " + nombre + "!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Sí, cambiar estado",
                    cancelButtonText: "Cancelar"
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Realizar la solicitud Ajax para cambiar el estado
                        $.ajax({
                            url: '/roles/' + Id + '/cambiar-estado',
                            method: 'POST',
                            data: {
                                _token: '{{ csrf_token() }}',
                                estado: estado == 1 ? 2 : 1, // Cambiar entre activo (1) y inactivo (2)
                            },
                            success: function (response) {
                                if (response.success) {
                                    // Después de cambiar el estado en la base de datos, actualizamos el frontend
                                    estado = estado == 1 ? 2: 1; // Actualizamos la variable de estado
                                    const estadoText = estado == 1 ? 'Activo' : 'Inactivo';
                                    const estadoColor = estado == 1 ? 'text-green-500' : 'text-red-500';

                                    // Actualizamos la columna de estado en el frontend
                                    const estadoElement = $('a[data-id="' + Id + '"]');
                                    estadoElement.html('<span class="' + estadoColor + ' font-bold">' + estadoText + '</span>');
                                    
                                    // Actualizamos el valor del estado en el data-estado para el siguiente clic
                                    estadoElement.data('estado', estado); 

                                    // Recargamos la página después de actualizar el estado
                                    location.reload(); 
                                } else {
                                    alert('Error al cambiar el estado');
                                }
                            },
                            error: function () {
                                alert('Ocurrió un error en la solicitud.');
                            }
                        });
                    }
                });
            });
        });
    });
</script>
@endpush
