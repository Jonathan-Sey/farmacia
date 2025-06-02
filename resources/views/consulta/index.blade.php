@extends('template')

@section('titulo','Consultas')

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.0/css/buttons.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css">


@endpush

@section('contenido')
    <a href="{{ route('consultas.create') }}">
        <div id="consultas-container"></div>

        <button class="btn btn-success text-white font-bold uppercase">
            Crear
        </button>
    </a>
    <x-data-table>
        <x-slot name="thead">
            <thead class=" text-white font-bold">
                <tr class="bg-slate-600  ">
                    <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider" >Código</th>
                    <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider" >Paciente</th>
                    <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider" >Asunto</th>
                    <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider" >Medico</th>
                    <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider" >Consulta</th>
                    <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider" >Proxima Cita</th>
                    <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider" >Estado</th>
                    <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider" >Acciones</th>
                </tr>
            </thead>
        </x-slot>

        <x-slot name="tbody">
            <tbody>
                @foreach ($consultas as $consulta)
                <tr>
                    <td class=" px-6 py-4 whitespace-nowrap">{{$consulta->id}}</td>
                    <td class=" px-6 py-4 whitespace-nowrap">{{$consulta->persona->nombre}}</td>
                    <td class=" px-6 py-4 whitespace-nowrap">{{$consulta->asunto}}</td>
                    <td class=" px-6 py-4 whitespace-nowrap">{{$consulta->medico->usuario->name}}</td>
                    <td class=" px-6 py-4 whitespace-nowrap">{{$consulta->fecha_consulta}}</td>
                    <td class=" px-6 py-4 whitespace-nowrap">{{$consulta->proxima_cita}}</td>
                    <td class=" px-6 py-4 whitespace-nowrap text-center">
                        <a class="estado" data-id="{{ $consulta->id}}" data-estado="{{$consulta->estado}}">
                            @if ($consulta->estado == 1)
                                <span class="text-green-500 font-bold">Activo</span>
                            @else
                                <span class="text-red-500 font-bold">Inactivo</span>
                            @endif
                        </a>
                    </td>
                    <td class="flex gap-2 justify-center">

                         <form action="{{route('consultas.edit',['consulta'=>$consulta->id])}}" method="GET">
                            @csrf
                            <button type="submit" class="btn btn-primary font-bold uppercase btn-sm">
                                <i class="fas fa-edit"></i>
                            </button>
                        </form>


                           {{-- Botón Cambiar estado --}}
                        <button type="button" class="btn btn-warning font-bold uppercase cambiar-estado-btn btn-sm" data-id="{{ $consulta->id }}" data-estado="{{ $consulta->estado }}" data-info="{{ $consulta->nombre }}">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </x-slot>
    </x-data-table>
@endsection

@push('js')

<script>
    document.addEventListener('DOMContentLoaded', function () {
    axios.get('/api/consultas')
        .then(response => {
            console.log(response.data);
            // Renderiza las consultas en tu vista
            renderConsultas(response.data.consultas);
        })
        .catch(error => {
            if (error.response.status === 401) {
                alert('No estás autenticado. Por favor, inicia sesión.');
                window.location.href = '/login';
            } else {
                console.error('Error al cargar las consultas:', error.response.data);
            }
        });
});

function renderConsultas(consultas) {
    const container = document.getElementById('consultas-container');
    container.innerHTML = ''; // Limpia el contenedor

    consultas.forEach(consulta => {
        const div = document.createElement('div');
        div.innerHTML = `
            <p>Consulta ID: ${consulta.id}</p>
            <p>Paciente: ${consulta.persona.nombre}</p>
            <p>Médico: ${consulta.medico.usuario.name}</p>
        `;
        container.appendChild(div);
    });
}

</script>

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
            order: [0,'desc'],
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
                        text: 'Export',
                        buttons: ['copy', 'pdf', 'excel', 'print']
                        },
                        'colvis'
                    ]
                }
            },
            columnDefs: [
                { responsivePriority: 3, targets: 0 },
                { responsivePriority: 1, targets: 1 },
                { responsivePriority: 2, targets: 7 },


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
                            url: '/consulta/' + Id + '/cambiar-estado',
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
