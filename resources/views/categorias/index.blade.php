@extends('template')
@section('titulo','Categorias')

@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

@endpush

@section('contenido')

{{-- mensaje script  --}}
<a href="{{ route('categorias.create') }}">
    <button class="btn btn-success text-white font-bold uppercase">
        crear
    </button>
</a>

    <h1>Contenido Roles</h1>
    <div id="token-info" class="mb-4"> <!-- La información del token se mostrará aquí --> </div>
    <ul>
        @foreach ($categorias as $categoria)
            <li>{{ $categoria->nombre }}</li>
            <li>{{ $categoria->descripcion }}</li>
                {{-- Validacion de estado --}}
            Estado:
            <a href="#" class="estado" data-id="{{ $categoria->id}}" data-estado="{{$categoria->estado}}">
                @if ($categoria->estado == 1)
                    <span class="text-green-500 font-bold" >Ativo</span>
                @else
                    <span class="text-red-500 font-bold" >Inactivo</span>
                @endif
            </a>
            <div>

               {{-- Boton editar --}}
               <form action="{{route('categorias.edit',['categoria'=>$categoria->id])}}" method="GET">
                @csrf
                <button type="submit" class="btn btn-primary font-bold uppercase">
                    <i class="fas fa-edit"></i>
                </button>
               </form>

               {{-- <form id="eliminar-from-{{ $rol-id }}" action="{{route('')}}"> --}}
                {{-- Cambio de estado --}}
                <button type="button" class="btn btn-warning font-bold uppercase eliminar-btn" data-id="{{$categoria->id}}"  data-info="{{$categoria->nombre}}">
                    <i class="fas fa-trash"></i>
                </button>

                {{-- Formulario oculto para eliminación --}}
                <form id="form-eliminar{{$categoria->id}}" action="{{ route('categorias.destroy', $categoria->id) }}" method="POST" style="display: none;">
                    @csrf
                    @method('DELETE')
                </form>

            </div>
        @endforeach
    </ul>




@endsection

@push('js')
<script src="/path/to/controldeNavPorRol.js"></script>
<script src="/path/to/controldeNavPorRol.js"></script>
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
                console.log("Evento DOMContentLoaded disparado");
                Toast.fire({ icon: "success",
                title: "{{ session('success')}}"
                });
        });
</script>
@endif

{{-- Cambio de estado --}}
<script>
    $(document).ready(function(){
        $('.estado').click(function(e){
            e.preventDefault();
            var id = $(this).data('id')
            var estado = $(this).data('estado')

            $.ajax({
                url: '/categorias/' + id,
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



{{-- Modal para eliminar  --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.eliminar-btn');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
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
                    document.getElementById('form-eliminar' + id).submit();
                }
            });
        });
    });
});
</script>

<script>
    function mostrarInformacionToken(decodedToken) { const tokenInfoElement = document.getElementById('token-info'); if (tokenInfoElement) { tokenInfoElement.innerHTML = ` <p>Email: ${decodedToken.email}</p> <p>Rol: ${decodedToken.rol}</p> <p>Nombre: ${decodedToken.name}</p> <p>Pestañas: ${decodedToken.pestanas.join(', ')}</p> `; } }
</script>
@endpush
