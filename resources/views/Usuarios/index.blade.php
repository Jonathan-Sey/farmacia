@extends('template')
@section('titulo','Usuarios')

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@section('contenido')
{{-- mensaje script  --}}
<a href="{{ route('usuarios.create') }}">
    <button class="btn btn-success text-white font-bold uppercase">
        crear
    </button>
</a>

<h1>Contenido Usuarios</h1>

<ul id="usuarios-list">
    @foreach ($usuarios as $usuario)
        <li id="usuario-{{ $usuario->id }}">
            {{ $usuario->name }}
            <br>
            {{ $usuario->email }}
            <br>
            
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
        @endforeach
    </ul>

@endsection

@push('js')
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

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
                                'success'
                            );

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
