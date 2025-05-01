@extends('template')

@section('titulo','Médicos')

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.0/css/buttons.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css">
@endpush

@section('contenido')
    <a href="{{ route('medicos.create') }}">
        <button class="btn btn-success text-white font-bold uppercase">Crear</button>
    </a>

    <x-data-table>
        <x-slot name="thead">
            <thead class="text-white font-bold">
                <tr class="bg-slate-600">
                    <th class="px-6 py-3 text-left">Código</th>
                    <th class="px-6 py-3 text-left">Médico</th>
                    <th class="px-6 py-3 text-left">Especialidad</th>
                    <th class="px-6 py-3 text-left">Colegiado</th>
                    <th class="px-6 py-3 text-left">Horarios</th>
                    <th class="px-6 py-3 text-left">Estado</th>
                    <th class="px-6 py-3 text-center">Acciones</th>
                </tr>
            </thead>
        </x-slot>

        <x-slot name="tbody">
            <tbody>
                @foreach ($medicos as $medico)
                <tr>
                    <td class="px-6 py-4">{{$medico->id}}</td>
                    <td class="px-6 py-4">{{$medico->usuario->name}}</td>
                    <td class="px-6 py-4">{{$medico->especialidad}}</td>
                    <td class="px-6 py-4">{{$medico->numero_colegiado}}</td>
                    <td class="px-6 py-4">
                        @if (!empty($medico->horarios))
                            @foreach ($medico->horarios as $horario)
                                @php
                                    $decodedHorario = json_decode($horario->horarios, true);
                                @endphp
                                @if (is_array($decodedHorario))
                                    <div class="bg-white p-2 rounded-md shadow-md border border-gray-200 mb-2 w-64">
                                        <span class="font-bold text-indigo-600 flex items-center">
                                            <i class="fas fa-map-marker-alt text-red-500 mr-2"></i>
                                            {{ $horario->sucursal->nombre ?? 'Sin sucursal' }}
                                        </span>
                                        <table class="w-full mt-1 text-xs">
                                            <thead>
                                                <tr class="border-b">
                                                    <th class="text-left font-semibold">Día</th>
                                                    <th class="text-left font-semibold">Horario</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($decodedHorario as $dia => $horas)
                                                    @if (is_array($horas))
                                                        @foreach ($horas as $rango)
                                                            <tr class="border-b">
                                                                <td class="py-1">{{ ucfirst($dia) }}</td>
                                                                <td class="py-1">{{ $rango }}</td>
                                                            </tr>
                                                        @endforeach
                                                    @else
                                                        <tr class="border-b">
                                                            <td class="py-1">{{ ucfirst($dia) }}</td>
                                                            <td class="py-1">{{ $horas }}</td>
                                                        </tr>
                                                    @endif
                                                @endforeach
                                            </tbody>
                                        </table>
                                    </div>
                                @endif
                            @endforeach
                        @else
                            <span class="text-gray-500">Sin horarios</span>
                        @endif
                    </td>
                    <td class="px-6 py-4 text-center">
                        <a class="estado" data-id="{{ $medico->id }}" data-estado="{{$medico->estado}}">
                            <span class="font-bold {{ $medico->estado == 1 ? 'text-green-500' : 'text-red-500' }}">
                                {{ $medico->estado == 1 ? 'Activo' : 'Inactivo' }}
                            </span>
                        </a>
                    </td>
                    <td class="px-6 py-4 text-center h-full align-middle">
                        {{-- boton editar --}}
                        <div class="flex flex-col justify-center items-center gap-2">
                            <form action="{{ route('medicos.edit', ['medico' => $medico->id]) }}" method="GET">
                                @csrf
                                <button type="submit" class="btn btn-primary btn-sm">
                                    <i class="fas fa-edit"></i>
                                </button>
                            </form>
                            {{-- boton cambio de estado --}}
                            <button type="button" class="btn btn-warning btn-sm cambiar-estado-btn" data-id="{{ $medico->id }}" data-estado="{{ $medico->estado }}" data-info="{{ $medico->usuario->name }}">
                                <i class="fas fa-sync-alt"></i>
                            </button>
                        </div>
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



<script>
    $(document).ready(function() {
        $('#example').DataTable({
            responsive: true,
            order: [0,'desc'],
            language: {
                url: '/js/i18n/Spanish.json',
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
                { responsivePriority: 1, targets: 0 },
                { responsivePriority: 2, targets: 1 },
                { responsivePriority: 3, targets: 6 },
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
                console.log("Evento DOMContentLoaded disparado");
                Toast.fire({ icon: "success",
                title: "{{ session('success')}}"
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
                            url: '/medico/' + Id + '/cambiar-estado',
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
