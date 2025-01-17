@extends('template')
@section('titulo','Roles')

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
@endpush

@section('contenido')
{{-- mensaje script  --}}
<a href="{{ route('roles.create') }}">
    <button class="btn btn-success text-white font-bold uppercase">
        crear
    </button>
</a>

    <h1>Contenido Roles</h1>

    <ul>
        @foreach ($roles as $rol)
            <li>{{ $rol->nombre }}</li>
            <li>{{ $rol->descripcion }}</li>
                {{-- Validacion de estado --}}
            Estado:
            <a href="#" class="estado" data-id="{{ $rol->id}}" data-estado="{{$rol->estado}}">
                @if ($rol->estado == 1)
                    <span class="text-green-500 font-bold" >Activo</span>
                @else
                    <span class="text-red-500 font-bold" >Inactivo</span>
                @endif
            </a>
            <div>

        {{-- Mostrar las pestañas asignadas --}}
        <p><strong>Pestañas asignadas:</strong></p>
        <ul>
            @foreach ($rol->pestanas as $pestana)
                <li>{{ $pestana->nombre }}</li> {{-- Puedes mostrar el nombre de las pestañas asignadas--}}
            @endforeach
        </ul>

        <div>
            {{-- Botón editar --}}
            <form action="{{ route('roles.edit', ['rol' => $rol->id]) }}" method="GET">
                @csrf
                <button type="submit" class="btn btn-primary font-bold uppercase">
                    <i class="fas fa-edit"></i>
                </button>
            </form>

            {{-- Botón eliminar --}}
            <button type="button" class="btn btn-warning font-bold uppercase eliminar-btn" data-id="{{ $rol->id }}" data-info="{{ $rol->nombre }}">
                 <i class="fas fa-trash"></i>
            </button>

            {{-- Formulario oculto para eliminación --}}
            <form id="form-eliminar{{ $rol->id }}" action="{{ route('roles.destroy', ['rol' => $rol->id]) }}" method="POST" style="display: none;">
                @csrf
                    @method('DELETE')
            </form>
        </div>
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

{{-- Cambio de estado --}}
<script>
    $(document).ready(function(){
        $('.estado').click(function(e){
            e.preventDefault();
            var rolId = $(this).data('id')
            var estado = $(this).data('estado')

            $.ajax({
                url: '/roles/' + rolId,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token()}}',
                    _method: 'DELETE',
                    status: estado == 1 ? 2 : 1
                },
                success: function(response){
                    if(response.success){
                        location.reload()
                    }else{
                        alert('Error al cambiar el estado')
                    }
                }
            })
        })
    });
</script>

{{-- Modal para eliminar --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.eliminar-btn');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const rolId = this.getAttribute('data-id');
            const rolnombre = this.getAttribute('data-info');
            Swal.fire({
                title: "¿Estás seguro?",
                text: "¡Deseas eliminar el rol " + rolnombre + "!",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí, ¡elimínalo!",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    // Activar el formulario de eliminación y enviarlo
                    document.getElementById('form-eliminar' + rolId).submit();
                }
            });
        });
    });
});

</script>
@endpush
