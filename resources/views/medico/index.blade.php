@extends('template')

@section('titulo','Medicos')

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.0/css/buttons.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css">


@endpush

@section('contenido')
    <a href="{{ route('medicos.create') }}">
        <button class="btn btn-success text-white font-bold uppercase">
            Crear
        </button>
    </a>
    <x-data-table>
        <x-slot name="thead">
            <thead class=" text-white font-bold">
                <tr class="bg-slate-600  ">
                    <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider" >Código</th>
                    <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider" >Medico</th>
                    <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider" >Especialidad</th>
                    <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider" >Colegiado</th>
                    <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider">Horarios</th>
                    <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider" >Estado</th>
                    <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider" >Acciones</th>
                </tr>
            </thead>
        </x-slot>

        <x-slot name="tbody">
            <tbody>
                @foreach ($medicos as $medico)
                <tr>
                    <td class=" px-6 py-4 whitespace-nowrap">{{$medico->id}}</td>
                    <td class=" px-6 py-4 whitespace-nowrap">{{$medico->usuario->name}}</td>
                    <td class=" px-6 py-4 whitespace-nowrap">{{$medico->especialidad}}</td>
                    <td class=" px-6 py-4 whitespace-nowrap">{{$medico->numero_colegiado}}</td>
                    <td class="px-6 py-4">
                        @if ($medico->horarios)
                            @php
                                $horarios = json_decode($medico->horarios, true);
                            @endphp
                            <ul class="list-disc pl-4">
                                @foreach ($horarios as $horario)
                                    <li>
                                        <strong>{{ ucfirst($horario['dia']) }}:</strong> 
                                        {{ $horario['hora_inicio'] }} - {{ $horario['hora_fin'] }}
                                    </li>
                                @endforeach
                            </ul>
                        @else
                            <span class="text-gray-500">Sin horarios</span>
                        @endif
                    </td>


                    <td class=" px-6 py-4 whitespace-nowrap text-center">
                        <a href="#" class="estado" data-id="{{ $medico->id}}" data-estado="{{$medico->estado}}">
                            @if ($medico->estado == 1)
                                <span class="text-green-500 font-bold">Activo</span>
                            @elseif ($medico->estado == 2)
                                <span class="text-red-500 font-bold">Inactivo</span>
                            @else
                                <span class="text-red-500 font-bold">Eliminado</span>
                            @endif
                        </a>
                    </td>
                    <td class="flex gap-2 justify-center">

                        <form action="{{route('medicos.edit',['medico'=>$medico->id])}}" method="GET">
                            @csrf
                            <button type="submit" class="btn btn-primary font-bold uppercase btn-sm">
                                <i class="fas fa-edit"></i>
                            </button>
                        </form>


                        <button type="button" class="btn btn-warning font-bold uppercase eliminar-btn btn-sm" data-id="{{$medico->id}}"  data-info="{{$medico->especialidad}}">
                            <i class="fas fa-trash"></i>
                        </button>


                        <form id="form-eliminar{{$medico->id}}" action="{{ route('medicos.destroy', $medico->id) }}" method="POST" style="display: none;">
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

                    buttons: ['copy', 'excel', 'pdf', 'print', 'colvis']
                }
            },
            columnDefs: [
                { responsivePriority: 3, targets: 0 },
                { responsivePriority: 1, targets: 1 },

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

{{-- Cambio de estado --}}
<script>
    $(document).ready(function(){
        $('.estado').click(function(e){
            e.preventDefault();
            var Id = $(this).data('id')
            var estado = $(this).data('estado')

            $.ajax({
                url: '/medicos/' + Id,
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
