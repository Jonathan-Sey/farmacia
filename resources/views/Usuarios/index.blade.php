@extends('template')
@section('titulo','Usuarios')

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.0/css/buttons.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css">
@endpush

@section('contenido')
{{-- mensaje script  --}}
<a href="{{ route('usuarios.create') }}">
    <button class="btn btn-success text-white font-bold uppercase">
        crear
    </button>
</a>

{{-- Tabla de categorías --}}
<x-data-table>
    <x-slot name="thead">
        <thead class="text-white font-bold">
            <tr class="bg-slate-600">
                <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider">Id</th>
                <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider">Nombre</th>
                <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider">Email</th>
                <th scope="col" class="px-6 py-3 text-center font-medium uppercase tracking-wider">Rol</th>
                <th scope="col" class="px-6 py-3 text-center font-medium uppercase tracking-wider">Acciones</th>
            </tr>
        </thead>
    </x-slot>

    <x-slot name="tbody">
        <tbody>
            @foreach ($usuarios as $usuario)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">{{ $usuario->id }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $usuario->name }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $usuario->email }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $usuario->rol->nombre ?? 'Sin rol asignado' }} <!-- Mostrar el nombre del rol --></td>
                
                <td class="flex gap-2 justify-center">
                    <!-- Botón Editar -->
            <form action="{{ route('usuarios.edit', ['usuario' => $usuario->id]) }}" method="GET" style="display:inline;">
                @csrf
                <button type="submit" class="btn btn-primary font-bold uppercase">
                    <i class="fas fa-edit"></i> Editar
                </button>
            </form>
    
                    <!-- Botón Eliminar -->
            <button type="button" class="btn btn-warning font-bold uppercase eliminar-btn" data-id="{{ $usuario->id }}" data-info="{{ $usuario->name }}">
                <i class="fas fa-trash"></i> Eliminar
            </button>

            <!-- Formulario oculto para eliminación -->
            <form id="form-eliminar{{ $usuario->id }}" action="{{ route('usuarios.actualizarEstado', ['usuario' => $usuario->id]) }}" method="POST" style="display: none;">
                @csrf
                @method('PATCH') <!-- Usamos PATCH para actualizar el estado -->
            </form>
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
        Toast.fire({ icon: "success", title: "{{ session('success') }}" });
    });
</script>
@endif

{{-- Modal para eliminar --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.eliminar-btn');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const usuarioId = this.getAttribute('data-id');
            const usuarioNombre = this.getAttribute('data-info');

            Swal.fire({
                title: "¿Estás seguro?",
                text: "¡Deseas eliminar al usuario " + usuarioNombre + "!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí, ¡elimínalo!",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    // Enviar solicitud AJAX
                    $.ajax({
                        url: '/usuarios/' + usuarioId + '/actualizar-estado',
                        type: 'PATCH',
                        data: {
                            _token: '{{ csrf_token() }}' // Asegúrate de que este token sea válido
                        },
                        success: function(response) {
                          
                            console.log(response); // Depura la respuesta
                            Swal.fire(
                                'Eliminado!',
                                'El usuario ha sido desactivado.',
                                'success',
                                
                            );
                            // Recarga la página después de 1.5 segundos
                            setTimeout(function() {
                                location.reload(); 
                            }, 1500); 
                            // Remover el usuario de la lista
                            document.getElementById('usuario-' + usuarioId).remove();
                            
                        },
                        
                        error: function(xhr) {
                            console.error("Error:", xhr.responseText); // Depura el error
                            Swal.fire(
                                'Error',
                                'Ocurrió un error al intentar eliminar al usuario.',
                                'error'
                            );
                        }
                    });
                }
            });
        });
    });
});

</script>
@endpush
