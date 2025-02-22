@extends('template')
@section('titulo', 'Categorías')

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.0/css/buttons.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css">
@endpush

@section('contenido')

{{-- Botón para crear nueva categoría --}}
<a href="{{ route('categorias.create') }}">
    <button class="btn btn-success text-white font-bold uppercase">
        Crear
    </button>
</a>

{{-- Tabla de categorías --}}
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
            @foreach ($categorias as $categoria)
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">{{ $categoria->id }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $categoria->nombre }}</td>
                <td class="px-6 py-4 whitespace-nowrap">{{ $categoria->descripcion }}</td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                    <a href="#" class="estado" data-id="{{ $categoria->id }}" data-estado="{{ $categoria->estado }}">
                        @if ($categoria->estado == 1)
                            <span class="text-green-500 font-bold">Activo</span>
                        @else
                            <span class="text-red-500 font-bold">Inactivo</span>
                        @endif
                    </a>
                </td>
                <td class="flex gap-2 justify-center">
                    {{-- Botón Editar --}}
                    <form action="{{ route('categorias.edit', ['categoria' => $categoria->id]) }}" method="GET">
                        @csrf
                        <button type="submit" class="btn btn-primary font-bold uppercase btn-sm">
                            <i class="fas fa-edit"></i>
                        </button>
                    </form>

                    {{-- Botón Eliminar --}}
                    <button type="button" class="btn btn-warning font-bold uppercase eliminar-btn btn-sm" data-id="{{ $categoria->id }}" data-info="{{ $categoria->nombre }}">
                        <i class="fas fa-trash"></i>
                    </button>

                    {{-- Formulario oculto para eliminación --}}
                    <form id="form-eliminar{{ $categoria->id }}" action="{{ route('categorias.destroy', $categoria->id) }}" method="POST" style="display: none;">
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
        Toast.fire({
            icon: "success",
            title: "{{ session('success') }}"
        });
    });
</script>
@endif

{{-- Cambio de estado --}}
<script>
    $(document).ready(function() {
        $('.estado').click(function(e) {
            e.preventDefault();
            const id = $(this).data('id');
            const estado = $(this).data('estado');
            $.ajax({
                url: `/categorias/${id}`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    _method: 'PUT',
                    estado: estado == 1 ? 0 : 1 // Cambia el estado
                },
                success: function(response) {
                    if (response.success) {
                        location.reload();
                    } else {
                        alert('Error al cambiar el estado');
                    }
                }
            });
        });
    });
</script>

{{-- Modal para eliminar --}}
<script>
document.addEventListener('DOMContentLoaded', function() {
    const deleteButtons = document.querySelectorAll('.eliminar-btn');

    deleteButtons.forEach(button => {
        button.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const nombre = this.getAttribute('data-info');
            Swal.fire({
                title: "¿Estás seguro?",
                text: `¡Deseas eliminar la categoría ${nombre}!`,
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Sí, ¡elimínalo!",
                cancelButtonText: "Cancelar"
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById(`form-eliminar${id}`).submit();
                }
            });
        });
    });
});
</script>

<script>

   // function mostrarInformacionToken(decodedToken) { const tokenInfoElement = document.getElementById('token-info'); if (tokenInfoElement) { tokenInfoElement.innerHTML = ` <p>Email: ${decodedToken.email}</p> <p>Rol: ${decodedToken.rol}</p> <p>Nombre: ${decodedToken.name}</p> <p>Pestañas: ${decodedToken.pestanas.join(', ')}</p> `; } }
</script>
@endpush

