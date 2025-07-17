@extends('template')

@section('titulo','Personas')

@push('css')
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.0/css/buttons.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css">


@endpush

@section('contenido')
    <a href="{{ route('personas.create') }}">
        <button class="btn btn-success text-white font-bold uppercase">
            Crear
        </button>
    </a>
    <x-data-table>
        <x-slot name="thead">
            <thead class=" text-white font-bold">
                <tr class="bg-slate-600  ">
                    <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider" >Nombre</th>
                    <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider" >Nit</th>
                    <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider" >Rol</th>
                    <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider" >Telefono</th>
                    <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider" >Estado</th>
                    <th scope="col" class="px-6 py-3 text-left font-medium uppercase tracking-wider" >Acciones</th>
                </tr>
            </thead>
        </x-slot>

        <x-slot name="tbody">
            <tbody>
                @foreach ($personas as $persona)
                <tr>
                    <td class=" px-6 py-4 whitespace-nowrap">{{$persona->nombre}}</td>
                    <td class=" px-6 py-4 whitespace-nowrap">{{$persona->nit}}</td>
                    <td class=" px-6 py-4 whitespace-nowrap">

                        @if ($persona->rol == 1)
                            <span class="text-orange-600 font-bold">Cliente</span>
                        @elseif ($persona->rol == 2)
                            <span class="text-blue-600 font-bold">Paciente</span>
                        @else
                            <span class="text-teal-600 font-bold">Menor de edad</span>
                        @endif

                    </td>
                    <td class=" px-6 py-4 whitespace-nowrap">{{$persona->telefono}}</td>

                    <td class=" px-6 py-4 whitespace-nowrap text-center">
                        <a class="estado" data-id="{{ $persona->id}}" data-estado="{{$persona->estado}}">
                            @if ($persona->estado == 1)
                                <span class="text-green-500 font-bold">Activo</span>
                            @else
                                <span class="text-red-500 font-bold">Inactivo</span>
                            @endif
                        </a>
                    </td>
                    <td class="flex gap-2 justify-center">
                        <form action="{{route('personas.edit',['persona'=>$persona->id])}}" method="GET">
                            @csrf
                            <button type="submit" class="btn btn-primary font-bold uppercase btn-sm">
                                <i class="fas fa-edit"></i>
                            </button>
                        </form>

                        <button onclick="mostrarRestricciones({{ $persona->id }})"
                            class="btn btn-info font-bold uppercase btn-sm text-white">
                            <i class="fas fa-shield-alt"></i>
                        </button>

                             {{-- Botón Cambiar estado --}}
                        <form action="{{ route('personas.show', $persona->id) }}" method="GET">
                            <button type="submit" class="btn btn-primary font-bold uppercase btn-sm">
                                <i class="fas fa-eye"></i>
                            </button>
                        </form>
                        <button type="button" class="btn btn-warning font-bold uppercase cambiar-estado-btn btn-sm" data-id="{{ $persona->id }}" data-estado="{{ $persona->estado }}" data-info="{{ $persona->nombre }}">
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
                { responsivePriority: 3, targets: 1 },
                { responsivePriority: 1, targets: 0 },
                { responsivePriority: 2, targets: 5 },

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

             $('#example tbody').on('click', '.cambiar-estado-btn', function () {
                const button = $(this);
                const Id = button.data('id');
                let estado = button.data('estado');
                const nombre = button.data('info');
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
                    $.ajax({
                        url: '/persona/' + Id + '/cambiar-estado',
                        method: 'POST',
                        data: {
                        _token: '{{ csrf_token() }}',
                        estado: estado == 1 ? 2 : 1,
                        },
                        success(response) {
                        if (response.success) {
                            location.reload();
                        } else {
                            alert('Error al cambiar el estado');
                        }
                        },
                        error() {
                        alert('Ocurrió un error en la solicitud.');
                        }
                    });
                    }
                });
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
                            url: '/persona/' + Id + '/cambiar-estado',
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

<script>
function mostrarRestricciones(idPersona) {
    fetch(`/personas/${idPersona}/restricciones`)
        .then(response => response.json())
        
        .then(data => {
            const modal = `
                <dialog id="modalRestricciones" class="modal">
                    <div class="modal-box w-11/12 max-w-5xl">
                        <h3 class="font-bold text-lg">Control de Compras</h3>
                        <div class="py-4">
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                <div>
                                    <label class="label">
                                        <span class="label-text">Límite de compras</span>
                                        <button onclick="document.getElementById('limiteCompras').value = ''"
                                                class="btn btn-xs btn-ghost">Limpiar</button>
                                    </label>
                                    <input type="number" id="limiteCompras" value="${data.limite_compras || ''}"
                                           class="input input-bordered w-full" min="0" placeholder="Sin límite">
                                </div>
                                <div>
                                    <label class="label">
                                        <span class="label-text">Período (días)</span>
                                        <button onclick="document.getElementById('periodoControl').value = ''"
                                                class="btn btn-xs btn-ghost">Limpiar</button>
                                    </label>
                                    <input type="number" id="periodoControl" value="${data.periodo_control || ''}"
                                           class="input input-bordered w-full" min="1" placeholder="Sin período">
                                </div>
                            </div>
                            <div class="mt-4">
                                <label class="cursor-pointer label flex justify-start gap-4">
                                    <input type="checkbox" id="restriccionActiva"
                                           ${data.restriccion_activa ? 'checked' : ''}
                                           class="checkbox checkbox-primary">
                                    <span class="label-text">Restricción activa</span>
                                </label>
                            </div>
                            <div class="mt-4 bg-gray-100 p-4 rounded">
                                <p>Compras recientes: <strong>${data.compras_recientes}</strong></p>
                                <p>Período evaluado: últimos <strong>${data.periodo_control || '30'} días</strong></p>
                            </div>
                        </div>
                        <div class="modal-action flex flex-wrap gap-2 justify-center sm:flex-nowrap">
                            <button onclick="cerrarModal()" class="btn w-full sm:w-auto px-4 py-2 rounded">Cancelar</button>
                            <button onclick="guardarRestricciones(${data.id})" class="btn btn-primary w-full sm:w-auto  px-4 py-2 rounded">Guardar</button>
                            <button onclick="eliminarRestricciones(${data.id})" class="btn btn-error w-full sm:w-auto px-4 py-2 rounded">Quitar todas las restricciones</button>
                        </div>
                    </div>
                </dialog>
            `;

            document.body.insertAdjacentHTML('beforeend', modal);
            document.getElementById('modalRestricciones').showModal();
        });
}

// Función para eliminar todas las restricciones
function eliminarRestricciones(idPersona) {
    fetch('/personas/actualizar-restricciones', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            id_persona: idPersona,
            limite_compras: null,
            periodo_control: null,
            restriccion_activa: false
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            cerrarModal();
            Swal.fire('Éxito', 'Todas las restricciones fueron eliminadas', 'success');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire('Error', 'No se pudieron eliminar las restricciones', 'error');
    });
}
    // Función para guardar las restricciones
    function guardarRestricciones(idPersona) {
        const data = {
            id_persona: idPersona,
            limite_compras: document.getElementById('limiteCompras').value || null,
            periodo_control: document.getElementById('periodoControl').value || null,
            restriccion_activa: document.getElementById('restriccionActiva').checked
        };

        fetch('/personas/actualizar-restricciones', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify(data)
        })
        .then(response => {
            if (!response.ok) {
                return response.json().then(err => { throw err; });
            }
            return response.json();
        })
        .then(data => {
            if (data.success) {
                cerrarModal();
                Swal.fire('Éxito', 'Restricciones actualizadas', 'success');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            Swal.fire('Error', error.message || 'Error al guardar las restricciones', 'error');
        });
    }

    function cerrarModal() {
        const modal = document.getElementById('modalRestricciones');
        if (modal) {
            modal.close();
            modal.remove();
        }
    }
</script>
@endpush
