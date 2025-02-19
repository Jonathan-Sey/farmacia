@extends('template')
@section('titulo', 'Solisitud de productos')

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.0/css/buttons.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css">
@endpush

@section('contenido')
    {{-- Botón para crear nueva sucursal --}}
    <a href="{{ route('solicitud.create') }}">
        <button class="btn btn-success text-white font-bold uppercase m-2">
            Crear
        </button>
    </a>


    <x-data-table>
        <x-slot name="thead">
            <thead class="text-white font-bold">
                <tr class="bg-slate-600">
                    <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider">Id</th>
                    <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider">sucursal original</th>
                    <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider">sucursal de destino</th>
                    <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider">producto</th>
                        <th scope="col" class="px-6 py-3 text-center font-medium uppercase tracking-wider">cantidad transferida</th>
                        <th scope="col" class="px-6 py-3 text-center font-medium uppercase tracking-wider">Mensaje</th>
                        <th scope="col" class="px-6 py-3 text-center font-medium uppercase tracking-wider">Fecha</th>
                        <th scope="col" class="px-6 py-3 text-center font-medium uppercase tracking-wider">activo</th>
                        <th scope="col" class="px-6 py-3 text-center font-medium uppercase tracking-wider">confirmar solicitud</th>
                </tr>
            </thead>
        </x-slot>

        <x-slot name="tbody">
            <tbody>
                @foreach ($solicitudes as $solicitud)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $solicitud->id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $solicitud->sucursal1->nombre}}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $solicitud->sucursal2->nombre}}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $solicitud->producto->nombre }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $solicitud->cantidad }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $solicitud->descripcion }}</td>
                    <td class="px-6 py-4 whitespace-nowrap">{{ $solicitud->created_at }}</td>

                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <a href="#" class="estado" data-id="{{ $solicitud->id }}" data-estado="{{ $solicitud->estado }}">
                            @if ($solicitud->estado == 1)
                                <span class="text-green-500 font-bold">Activo</span>
                            @else
                                <span class="text-red-500 font-bold">Inactivo</span>
                            @endif
                        </a>
                    </td>
                    <td class="flex gap-2 justify-center">
                 

                        {{-- Botón Eliminar --}}
                        <button type="button" class="btn btn-success font-bold uppercase eliminar-btn btn-sm" data-id="{{ $solicitud->id }}" data-info="{{ $solicitud->nombre }}">
                        <i class="fa-solid fa-check"></i>
                        </button>

                        {{-- Formulario oculto para eliminación --}}
                        <form id="form-eliminar{{ $solicitud->id }}" action="{{ route('solicitud.destroy', $solicitud->id) }}" method="POST" style="display: none;">
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

{{-- Cambio de estado --}}
<script>
$(document).ready(function(){
    $('.estado').click(function(e){
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
            success: function(response){
                if(response.success){
                    location.reload();
                }else{
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

