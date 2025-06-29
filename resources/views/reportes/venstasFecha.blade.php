@extends('template')
@section('titulo', 'Reporte de ventas por fecha')
@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/3.2.0/css/buttons.dataTables.css">
<link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.bootstrap5.css">

<style>
/* Estilos para el toggle personalizado */
.toggle-bg {
    position: relative;
    display: inline-block;
    width: 2.5rem; /* 40px */
    height: 1.5rem; /* 24px */
    background-color: #d1d5db; /* gray-300 */
    border-radius: 9999px;
    box-shadow: inset 0 2px 4px 0 rgba(0, 0, 0, 0.06);
    transition: background-color 0.3s ease-in-out;
}

.toggle-dot {
    position: absolute;
    top: 0.125rem; /* 2px */
    left: 0.125rem; /* 2px */
    width: 1.25rem; /* 20px */
    height: 1.25rem; /* 20px */
    background-color: white;
    border-radius: 50%;
    box-shadow: 0 1px 3px 0 rgba(0, 0, 0, 0.1), 0 1px 2px 0 rgba(0, 0, 0, 0.06);
    transition: transform 0.3s ease-in-out;
}

/* Estados del toggle */
#buscarPorRango:checked ~ .toggle-bg {
    background-color: #3b82f6; /* blue-500 */
}

#buscarPorRango:checked ~ .toggle-bg .toggle-dot {
    transform: translateX(1rem); /* 16px */
}

#buscarPorRango:focus ~ .toggle-bg {
    box-shadow: 0 0 0 2px rgba(59, 130, 246, 0.5), inset 0 2px 4px 0 rgba(0, 0, 0, 0.06);
}

/* Efectos hover */
.toggle-bg:hover {
    background-color: #9ca3af; /* gray-400 */
}

#buscarPorRango:checked ~ .toggle-bg:hover {
    background-color: #2563eb; /* blue-600 */
}

/* Transición suave para el cursor */
label:hover {
    cursor: pointer;
}
</style>
@endpush

@section('contenido')

  


<div class="max-w-5xl mx-auto p-4 sm:p-6 m-2 sm:m-5 bg-white rounded-lg shadow-md">

    <form id="formReporte" class="space-y-4 sm:space-y-6">
        <!-- Toggle para alternar modo de búsqueda -->
        <div class="flex items-center justify-center sm:justify-start mb-4">
            <div class="flex items-center gap-2 ">
                 <span class="ml-3 text-sm font-medium text-gray-600 select-none">
                        Buscar por rango de fechas
                    </span>
                <label for="buscarPorRango" class="flex items-center cursor-pointer">
                    <div class="relative">
                        <input type="checkbox" id="buscarPorRango" class="sr-only">
                        <div class="toggle-bg">
                            <div class="toggle-dot"></div>
                        </div>
                    </div>
                   
                </label>
            </div>
        </div>

        <!-- Campos individuales (Día, Mes, Año) -->
        <div id="camposIndividuales" class="flex flex-col sm:flex-row gap-2 sm:gap-4">
            <div class="flex-1 mb-4 sm:mb-0">
                <label for="fecha" class="block text-sm font-medium text-gray-600 mb-1">Día:</label>
                <input type="date" id="fecha" name="fecha"
                    class="w-full p-2 sm:p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-300 transition-all duration-200 text-sm sm:text-base">
            </div>

            <div class="flex-1 mb-4 sm:mb-0">
                <label for="mes" class="block text-sm font-medium text-gray-600 mb-1">Mes:</label>
                <input type="month" id="mes" name="mes"
                    class="w-full p-2 sm:p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-300 transition-all duration-200 text-sm sm:text-base">
            </div>

            <div class="flex-1 mb-4 sm:mb-0">
                <label for="año" class="block text-sm font-medium text-gray-600 mb-1">Año:</label>
                <input type="number" id="año" name="año" min="2000" max="2100"
                    class="w-full p-2 sm:p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-300 transition-all duration-200 text-sm sm:text-base">
            </div>
        </div>

        <!-- Campos de rango (ocultos por defecto) -->
        <div id="camposRango" class="hidden flex-col sm:flex-row gap-2 sm:gap-4">
            <div class="flex-1 mb-4 sm:mb-0">
                <label for="fechaInicio" class="block text-sm font-medium text-gray-600 mb-1">Desde:</label>
                <input type="date" id="fechaInicio" name="fechaInicio"
                    class="w-full p-2 sm:p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-300 transition-all duration-200 text-sm sm:text-base">
            </div>

            <div class="flex-1 mb-4 sm:mb-0">
                <label for="fechaFin" class="block text-sm font-medium text-gray-600 mb-1">Hasta:</label>
                <input type="date" id="fechaFin" name="fechaFin"
                    class="w-full p-2 sm:p-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-300 focus:border-blue-300 transition-all duration-200 text-sm sm:text-base">
            </div>
        </div>

        <!-- Botón -->
        <div class="flex flex-col sm:flex-row justify-center sm:justify-end mt-6">
            <button type="button" id="btnGenerarInforme"
                class="w-full sm:w-auto px-6 py-3 bg-green-500 text-white rounded-lg shadow-md hover:bg-green-600 focus:bg-green-600 focus:ring-4 focus:ring-green-200 transition-all duration-200 font-medium text-sm sm:text-base">
                Generar Informe
            </button>
        </div>
    </form>
</div>



<!-- Aquí se insertará la tabla dinámica -->
<x-data-table>
            <x-slot name="thead">
                <thead class="text-white font-bold">
                    <tr class="bg-slate-600">
                        <th class="px-6 py-3 text-left font-medium uppercase tracking-wider">Código</th>
                        <th class="px-6 py-3 text-left font-medium uppercase tracking-wider">producto</th>
                        <th class="px-6 py-3 text-left font-medium uppercase tracking-wider">cantidad</th>
                        <th class="px-6 py-3 text-left font-medium uppercase tracking-wider">precio</th>
                        <th class="px-6 py-3 text-left font-medium uppercase tracking-wider">impuesto</th>
                        <th class="px-6 py-3 text-left font-medium uppercase tracking-wider">total</th>
                        <th class="px-6 py-3 text-left font-medium uppercase tracking-wider">usuario</th>
                        <th class="px-6 py-3 text-left font-medium uppercase tracking-wider">persona</th>
                        <th class="px-6 py-3 text-left font-medium uppercase tracking-wider">fecha</th>


                    </tr>
                </thead>
            </x-slot>

            <x-slot name="tbody">
            <tbody id="tabla">
                <!-- Aquí se insertarán las filas de la tabla -->
            </tbody>
            </x-slot>
        </x-data-table>


@push('js')





    <script>
// Manejar el cambio del checkbox para alternar entre modos de búsqueda
document.getElementById('buscarPorRango').addEventListener('change', function() {
    const camposIndividuales = document.getElementById('camposIndividuales');
    const camposRango = document.getElementById('camposRango');
    
    if (this.checked) {
        // Mostrar campos de rango y ocultar campos individuales
        camposIndividuales.classList.add('hidden');
        camposRango.classList.remove('hidden');
        camposRango.classList.add('flex');
        
        // Limpiar valores de campos individuales
        document.getElementById('fecha').value = '';
        document.getElementById('mes').value = '';
        document.getElementById('año').value = '';
    } else {
        // Mostrar campos individuales y ocultar campos de rango
        camposRango.classList.add('hidden');
        camposRango.classList.remove('flex');
        camposIndividuales.classList.remove('hidden');
        
        // Limpiar valores de campos de rango
        document.getElementById('fechaInicio').value = '';
        document.getElementById('fechaFin').value = '';
    }
});

// Script principal para generar informe
document.getElementById('btnGenerarInforme').addEventListener('click', async () => {
    // Obtener valores de los inputs
    const fecha = document.getElementById('fecha').value;
    const mes = document.getElementById('mes').value;
    const año = document.getElementById('año').value;
    const fechaInicio = document.getElementById('fechaInicio').value;
    const fechaFin = document.getElementById('fechaFin').value;

    // Construir URL
    let url = '/ventas-informe?';
    if (fecha) url += `fecha=${fecha}&`;
    if (mes) url += `mes=${mes}&`;
    if (año) url += `año=${año}&`;
    if (fechaInicio && fechaFin) url += `fechaInicio=${fechaInicio}&fechaFin=${fechaFin}`;

    try {
        // Fetch data
        const response = await fetch(url);
        if (!response.ok) throw new Error('Error en la respuesta');
        const data = await response.json();

        // Generar HTML de las filas
        const rows = data.map(venta => `
            <tr>
                <td class="px-6 py-3">${venta.venta_id}</td>
                <td class="px-6 py-3">${venta.nombre_producto || 'N/A'}</td>
                <td class="px-6 py-3">${venta.cantidad}</td>
                <td class="px-6 py-3">${venta.precio}</td>
                <td class="px-6 py-3">${venta.impuesto}</td>
                <td class="px-6 py-3">${venta.subtotal}</td>
                <td class="px-6 py-3">${venta.nombre_usuario}</td>
                <td class="px-6 py-3">${venta.nombre_persona}</td>
                <td class="px-6 py-3">${venta.fecha_venta}</td>
            </tr>
        `).join('');

        // Insertar filas en la tabla
        const tbody = document.getElementById('tabla');
        tbody.innerHTML = rows;

        // Destruir DataTable si existe
        if ($.fn.DataTable.isDataTable('#example')) {
            $('#example').DataTable().destroy();
            tbody.innerHTML = ''; // Limpiar temporalmente
            tbody.innerHTML = rows; // Volver a insertar
        }

        // Inicializar DataTable
        $('#example').DataTable({
            responsive: true,
            order: [[0, 'desc']],
            language: { url: '/js/i18n/Spanish.json' },
            dom: 'Bfrtip',
            buttons: ['copy', 'excel', 'pdf', 'print', 'colvis'],
            columnDefs: [
                { responsivePriority: 1, targets: 0 },
                { responsivePriority: 2, targets: 1 }
            ]
        });

    } catch (error) {
        console.error('Error:', error);
        Swal.fire('Error', 'No se pudieron cargar los datos', 'error');
    }
});
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

@endpush
@endsection
