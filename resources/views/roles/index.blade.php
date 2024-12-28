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
            Estado:
            @if ($rol->estado == 1)
                <span class="text-green-500 font-bold" >Ativo</span>
            @else
                <span class="text-red-500 font-bold" >Inactivo</span>
            @endif
            <div>
               <form action="{{route('roles.edit',['rol'=>$rol->id])}} " method="GET">
                @csrf
                <button type="submit" class="btn btn-primary font-bold uppercase">
                    <i class="fas fa-edit"></i>
                </button>
               </form>
               {{-- <form id="eliminar-from-{{ $rol-id }}" action="{{route('')}}"> --}}
                @if ($rol->estado == 1)
                <button type="submit" class="btn btn-warning font-bold uppercase eliminar-btn" data-id="{{$rol->id}}">
                    <i class="fas fa-trash"></i>
                </button>
                @else
                <button type="submit" class="btn btn-warning font-bold uppercase eliminar-btn" data-id="{{$rol->id}}">
                    <i class="fa-solid fa-rotate-left"></i>
                </button>
                @endif

                {{-- formulario para mandar el id a eliminar --}}
                <form id="form-eliminar{{$rol->id}}" action="{{route('roles.destroy',['rol'=> $rol->id])}}" method="POST" >
                    @csrf
                    @method('DELETE')
                </form>
            </div>
        @endforeach
    </ul>




@endsection

@push('js')

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

{{-- Modal para eliminar  --}}

<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.eliminar-btn');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const rolId = this.getAttribute('data-id');
            Swal.fire({
                title: "¿Estás seguro?",
                text: "¡No podrás revertir esto!" + rolId,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí, ¡elimínalo!",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('form-eliminar' + rolId).submit();
                }
            });
        });
    });
});
</script>
@endpush
