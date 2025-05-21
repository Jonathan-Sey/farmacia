@extends('template')
@section('titulo', 'Farmacias')

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.0/css/buttons.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css">
@endpush

@section('contenido')
    {{-- Botón para crear nueva sucursal --}}
    <a href="{{ route('sucursales.create') }}">
        <button class="btn btn-success text-white font-bold uppercase">
            Crear
        </button>
    </a>
    <div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-2 mb-8 mt-10 ">
        @foreach ($sucursales as $sucursal)
            <div class="w-auto h-auto py-8 bg-white rounded-md shadow-lg grid grid-cols-1 justify-center align-middle items-center text-center xl:grid xl:grid-cols-2">
                    <div class="flex flex-col items-center gap-3 p-2">
                        <div>
                            @if ($sucursal->imagen)
                            <img src="{{ asset('uploads/' . $sucursal->imagen) }}" alt="{{ $sucursal->nombre }}" class="w-60 h-auto object-cover rounded-xl">
                            @else
                                <span class="text-gray-500">Sin imagen</span>
                            @endif
                        </div>
                        <div class="">
                            @if ($sucursal->estado == 1)
                            <span class="text-green-500 font-bold">Activo</span>
                            @else
                                <span class="text-red-500 font-bold">Inactivo</span>
                            @endif
                        </div>
                    </div>
                    <div class="flex flex-col text-left justify-start">
                        <div class="px-6 w-auto break-words md:text-lg lg:text-xl">
                            <p class="uppercase text-lg font-bold text-black">{{$sucursal->nombre}}</p>
                            <p class="text-lg text-black"><i class="fa-solid fa-circle-user"></i> {{$sucursal->encargado}}</p>
                            <p class="text-lg text-black"><i class="fa-solid fa-location-dot"></i> {{$sucursal->ubicacion}}</p>
                            <p class="text-lg text-black"><i class="fa-solid fa-phone"></i> {{$sucursal->telefono}}</p>
                            <p class="text-lg text-black"><i class="fa-solid fa-envelope"></i> {{$sucursal->email}}</p>


                        </div>
                        <div class="lg:grid lg:grid-cols-2 lg:mt-4 lg:gap-2 px-5 lg:justify-between ">
                            <div class="m-1">
                                {{-- Botón Editar --}}
                                <form action="{{ route('sucursales.edit', ['sucursal' => $sucursal->id]) }}" method="GET">
                                    @csrf
                                    <button type="submit" class="btn btn-primary font-bold uppercase btn-sm w-full">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                </form>

                            </div>

                            <div class="m-1">
                                    {{-- Botón Cambiar estado --}}
                                <button type="button" class="btn w-full btn-warning font-bold uppercase cambiar-estado-btn btn-sm" data-id="{{ $sucursal->id }}" data-estado="{{ $sucursal->estado }}" data-info="{{ $sucursal->nombre }}">
                                    <i class="fas fa-sync-alt"></i>
                                </button>
                            </div>

                        </div>
                    </div>

            </div>
            @endforeach
    </div>

    {{-- Paginación con DaisyUI --}}
    <div class="join flex justify-center mt-5">
        {{-- si estamos en la primera pagina  --}}
        @if ($sucursales->onFirstPage())
            <button class="join-item btn btn-disabled">«</button>
        @else
            <a href="{{ $sucursales->previousPageUrl() }}" class="join-item btn">«</a>
        @endif
        {{-- # paginas --}}
        <span class="join-item btn">Página {{ $sucursales->currentPage() }} de {{ $sucursales->lastPage() }}</span>

        {{-- si hay mas paginas --}}
        @if ($sucursales->hasMorePages())
            <a href="{{ $sucursales->nextPageUrl() }}" class="join-item btn">»</a>
        @else
            <button class="join-item btn btn-disabled">»</button>
        @endif
    </div>


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
                 paginate: {
                     first: `<i class="fa-solid fa-backward"></i>`,
                     previous: `<i class="fa-solid fa-caret-left">`,
                     next: `<i class="fa-solid fa-caret-right"></i>`,
                     last: `<i class="fa-solid fa-forward"></i>`
                 }
            },
        layout: {
                topStart: {

                    buttons: [
                        {
                            extend: 'collection',
                        text: 'Export',
                        buttons: ['copy', 'pdf', 'excel', 'print']
                        },
                        'colvis'
                    ]
                }
            },
            columnDefs: [
                { responsivePriority: 3, targets: 0 },
                { responsivePriority: 1, targets: 1 },
                { responsivePriority: 2, targets: 7 },
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
                Toast.fire({ icon: "success",
                title: "{{ session('success') }}"
                });
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
                            url: '/sucursal/' + Id + '/cambiar-estado',
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
