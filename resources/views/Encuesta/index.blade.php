@extends('template')

@section('titulo','Encuestas Médicas')

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.0/css/buttons.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css">
<style>
    .btn-responder {
        background-color: #4CAF50;
        color: white;
        padding: 5px 10px;
        border-radius: 4px;
        transition: background-color 0.3s;
    }
    .btn-responder:hover {
        background-color: #45a049;
    }
    .btn-ver-respuestas {
        background-color: #2196F3;
        color: white;
        padding: 5px 10px;
        border-radius: 4px;
        transition: background-color 0.3s;
    }
    .btn-ver-respuestas:hover {
        background-color: #0b7dda;
    }
</style>
@endpush

@section('contenido')

    <a href="{{ route('encuestas.create') }}">
        <button class="btn btn-success text-white font-bold uppercase mb-4">Crear Encuesta</button>
    </a>


    <div class="bg-white rounded-lg shadow overflow-hidden">
        <x-data-table>
            <x-slot name="thead">
                <thead class="text-white font-bold">
                    <tr class="bg-slate-600">
                        <th class="px-6 py-3 text-left">ID</th>
                        <th class="px-6 py-3 text-left">Título</th>
                        <th class="px-6 py-3 text-left">Médico</th>
                        <th class="px-6 py-3 text-left">Descripción</th>
                        <th class="px-6 py-3 text-center">Estado</th>
                        <th class="px-6 py-3 text-center">Acciones</th>
                    </tr>
                </thead>
            </x-slot>

            <x-slot name="tbody">
                <tbody>
                    @foreach($encuestas as $encuesta)
                    <tr class="border-b hover:bg-gray-50">
                        <td class="px-6 py-4">{{ $encuesta->id }}</td>
                        <td class="px-6 py-4 font-medium">{{ $encuesta->titulo }}</td>
                        <td class="px-6 py-4">{{ $encuesta->medico->usuario->name }}</td>
                        <td class="px-6 py-4">{{ Str::limit($encuesta->descripcion, 50) }}</td>
                        <td class="px-6 py-4 text-center">
                            <span class="{{ $encuesta->activa ? 'text-green-600' : 'text-red-600' }} font-bold">
                                {{ $encuesta->activa ? 'Activo' : 'Inactivo' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <div class="flex justify-center space-x-2">

                                <a href="{{ route('encuestas.responder', $encuesta) }}"
                                   class="btn-responder"
                                   title="Responder encuesta">
                                   {{-- Responder --}}
                                   <i class="fa-solid fa-house-medical-circle-check"></i>
                                </a>

                                <a href="{{ route('encuestas.respuestas', $encuesta) }}"
                                   class="btn-ver-respuestas"
                                   title="Ver respuestas">
                                    <i class="fas fa-chart-bar"></i>
                                </a>

                                <a href="{{ route('encuestas.edit', $encuesta) }}"
                                   class="text-yellow-600 hover:text-yellow-900"
                                   title="Editar">
                                    <i class="fas fa-edit"></i>
                                </a>

                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </x-slot>
        </x-data-table>
    </div>
@endsection

@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.datatables.net/responsive/3.0.3/js/dataTables.responsive.js"></script>
<script src="https://cdn.datatables.net/responsive/3.0.3/js/responsive.bootstrap5.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.0/js/dataTables.buttons.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.print.min.js"></script>
<script src="https://cdn.datatables.net/buttons/3.2.0/js/buttons.colVis.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>

<script>
    $(document).ready(function() {
        $('#example').DataTable({
            responsive: true,
            order: [[0, 'desc']],
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
                            text: 'Exportar',
                            buttons: ['copy', 'pdf', 'excel', 'print']
                        },
                        'colvis'
                    ]
                }
            },
            columnDefs: [
                { responsivePriority: 1, targets: 0 },
                { responsivePriority: 2, targets: 1 },
                { responsivePriority: 3, targets: -1 },
            ],
            drawCallback: function() {
                setTimeout(function() {
                    $('a.paginate_button').addClass('btn btn-sm btn-primary mx-1');
                    $('a.paginate_button.current').removeClass('btn-gray-800').addClass('btn btn-sm btn-primary');
                }, 100);
            },
        });
    });

    // Cambiar estado de la encuesta
    document.addEventListener('DOMContentLoaded', function() {
        const cambiarEstadoBtns = document.querySelectorAll('.cambiar-estado-btn');

        cambiarEstadoBtns.forEach(btn => {
            btn.addEventListener('click', function() {
                const encuestaId = this.dataset.id;
                const estadoActual = this.dataset.estado;
                const nuevoEstado = estadoActual == 1 ? 0 : 1;
                const encuestaNombre = this.dataset.nombre;

                Swal.fire({
                    title: '¿Cambiar estado?',
                    text: `¿Deseas ${nuevoEstado == 1 ? 'activar' : 'desactivar'} la encuesta "${encuestaNombre}"?`,
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, cambiar',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/encuestas/${encuestaId}/cambiar-estado`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                                'Content-Type': 'application/json',
                                'Accept': 'application/json'
                            },
                            body: JSON.stringify({
                                estado: nuevoEstado
                            })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire(
                                    '¡Cambiado!',
                                    `El estado de la encuesta ha sido ${nuevoEstado == 1 ? 'activado' : 'desactivado'}.`,
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            }
                        })
                        .catch(error => {
                            Swal.fire(
                                'Error',
                                'Ocurrió un error al cambiar el estado.',
                                'error'
                            );
                        });
                    }
                });
            });
        });
    });
</script>

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
@endpush