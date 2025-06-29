@extends('template')
@section('titulo', 'Devoluciones')
@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<style>
    .error-message {
        color: red;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }
</style>

<style>
    .toggle-wrapper {
        display: flex;
        flex-direction: column;
        gap: 10px;
        margin-bottom: 20px;
    }

    .custom-toggle {
        position: relative;
        display: inline-block;
        width: 60px;
        height: 34px;
    }

    .custom-toggle input {
        opacity: 0;
        width: 0;
        height: 0;
    }

    .slider {
        position: absolute;
        cursor: pointer;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background-color: #f44336;
        /* rojo apagado */
        transition: 0.4s;
        border-radius: 34px;
        box-shadow: inset -2px -2px 5px rgba(255, 255, 255, 0.5),
            inset 2px 2px 5px rgba(0, 0, 0, 0.2);
    }

    .slider:before {
        position: absolute;
        content: "";
        height: 26px;
        width: 26px;
        left: 4px;
        bottom: 4px;
        background-color: white;
        transition: 0.4s;
        border-radius: 50%;
        box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
    }

    .custom-toggle input:checked+.slider {
        background-color: #4caf50;
        /* verde encendido */
    }

    .custom-toggle input:checked+.slider:before {
        transform: translateX(26px);
    }

    .toggle-label {
        font-size: 14px;
        color: #333;
    }
</style>

@endpush

@section('contenido')
<div class="flex justify-center item-center mx-3">
    <div class="bg-white p-5 rounded-xl shadow-lg w-full max-w-7xl mb-10">
        <form action="{{ route('devoluciones.store') }}" method="POST">
            @csrf

            <div id="usuario"></div>
            <div class="lg:grid lg:grid-cols-2 lg:gap-5 sm:grid sm:grid-cols-1 sm:gap-5">
                <fieldset class="border-2 border-gray-200 p-2 rounded-2xl">
                    <legend class="text-blue-500 font-bold">Venta</legend>

                    <div class="mt-2 mb-5">
                        <label for="id_venta" class="uppercase block text-sm font-medium text-gray-900">Numero de venta</label>
                        <select
                            name="id_venta"
                            id="id_venta"
                            class="select2-sucursal block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">
                            <option value="">Seleccione una venta</option>
                            @foreach($ventas as $venta)
                            <option value="{{ $venta->id }}">{{ $venta->id }}</option>
                            @endforeach
                        </select>

                        @error('id_venta')
                        <div role="alert" class="alert alert-error mt-4 p-2">
                            <span class="text-white font-bold">{{ $message}}</span>
                        </div>


                        @enderror
                    </div>
                    <div class="mt-3 mb-5">
                        <label class="uppercase block text-sm font-medium text-gray-900">Motivo</label>
                        <textarea
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                            name="motivo"
                            id="motivo"
                            style="height: 150px;"
                            placeholder="Motivo de la devolución"></textarea>

                        @error('motivo')
                        <div role="alert" class="alert alert-error mt-4 p-2">
                            <span class="text-white font-bold">{{ $message}}</span>
                        </div>


                        @enderror
                    </div>



                    <button type="submit" class=" cursor-pointer mt-3 rounded-md bg-indigo-600 px-3 w-full py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-indigo-600">Ingresar</button>

                </fieldset>

                <fieldset class="border-2 border-gray-200 p-2 rounded-2xl">
                    <legend class="text-blue-500 font-bold">Detalle de la devolucion</legend>

                    <div class="mt-2 mb-5">
                        <label for="sucursal_nombre" class="uppercase block text-sm font-medium text-gray-900">Sucursal</label>
                        <input type="text"
                            name="sucursal_nombre"
                            id="sucursal_nombre"
                            autocomplete="given-name"
                            placeholder="sucursal"
                            class=" block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                            readonly>
                        <input type="hidden" name="id_sucursal" id="id_sucursal">

                        @error('sucursal_nombre')
                        <div role="alert" class="alert alert-error mt-4 p-2">
                            <span class="text-white font-bold">{{ $message}}</span>
                        </div>


                        @enderror
                    </div>

                    <div class="mt-2 mb-5">
                        <label for="persona_nombre" class="uppercase block text-sm font-medium text-gray-900">Persona</label>
                        <input type="text"
                            name="persona_nombre"
                            id="persona_nombre"
                            placeholder="persona"
                            class=" block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                            readonly>
                        <input type="hidden" name="id_persona" id="id_persona">


                        @error('persona_nombre')
                        <div role="alert" class="alert alert-error mt-4 p-2">
                            <span class="text-white font-bold">{{ $message}}</span>
                        </div>


                        @enderror

                    </div>
                    <div class="mt-2 mb-5">
                        <label for="observaciones" class="uppercase block text-sm font-medium text-gray-900">Observaciones del producto</label>
                        <textarea
                            name="observaciones"
                            id="observaciones"
                            style="height: 150px;"
                            class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"
                            placeholder="Observaciones"></textarea>
                        @error('observaciones')
                        <div role="alert" class="alert alert-error mt-4 p-2">
                            <span class="text-white font-bold">{{ $message}}</span>
                        </div>


                        @enderror


                    </div>

                    <div class="toggle-wrapper">
                        <label class="custom-toggle">
                            <input type="checkbox" id="toggle_vencimiento">
                            <span class="slider"></span>
                        </label>
                        <span class="toggle-label">Agregar fecha de vencimiento</span>

                        <input type="date" id="input_vencimiento" name="fecha_vencimineto" style="display: none;" />
                    </div>



                </fieldset>
            </div>


            <!-- Tabla dinámica de productos -->
            <div class="mt-5">
                <h2 class="text-center m-5 font-bold text-lg">Detalle de la devolucion</h2>
                <div class="overflow-x-auto">
                    <table id="tabla-detalles" class="table  table-md table-pin-rows table-pin-cols">
                        <thead>
                            <tr>
                                <th>Producto</th>
                                <th>Cantidad</th>
                                <th>Precio</th>
                                <th>Devolver</th>
                                <th>Subtotal</th>
                                <th>Acciones</th> <!-- Nueva columna -->
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Se llenará por JS -->
                        </tbody>
                        <tfoot>
                            <tr>
                                <th></th>
                                <td class="text-sm font-black">SUMA: <span id="suma" class="font-black ">0</span></td>
                                <td class="text-sm font-black">IVA %: <span id="iva" class="font-black">0</span></td>
                                <td class="text-sm font-black"><input type="hidden" name="total" value="0" id="inputTotal"> TOTAL: <span id="total" class="font-black">0</span></td>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </form>
    </div>
</div>




@endsection
@push('js')


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="/js/obtenerUsuario.js"></script>
<script>
    //uso del select2 para Ventas
    $(document).ready(function() {
        $('.select2-sucursal').select2({
            width: '100%',
            placeholder: "Buscar Venta",
            allowClear: true
        });
        // pocicionar el cursor en el input para buscar producto
        $('.select2-sucursal').on('select2-sucursal:open', function() {
            document.querySelector('.select2-search__field').focus();
        });

        //uso del select2 para proveedores
        $('.select2-producto').select2({
            width: '100%',
            placeholder: "Buscar producto",
            allowClear: true
        });
        // pocicionar el cursor en el input para buscar producto
        $('.select2-producto').on('select2-producto:open', function() {
            document.querySelector('.select2-search__field').focus();
        });
    });
</script>

<script>
    $(document).ready(function() {
        const ventas = $('#id_venta');
        const personaInput = $('#id_persona');
        const sucursalInput = $('#id_sucursal');
        const tablaDetalles = $('#tabla-detalles tbody');
        const suma = $("#suma");
        const total = $("#total");
        const inputTotal = $("#inputTotal");
        const iva = $("#iva");

        function recalcularTotales() {
            let sumaTotal = 0;

            tablaDetalles.find('tr').each(function() {
                const row = $(this);
                const precio = parseFloat(row.find('input[name$="[precio]"]').val());
                const cantidadInput = row.find('input[name$="[cantidad]"]');
                const cantidad = parseInt(cantidadInput.val()) || 0;
                const max = parseInt(cantidadInput.attr('max'));

                // Validación en tiempo real
                if (cantidad <= 0 || cantidad > max || isNaN(cantidad)) {
                    cantidadInput.addClass('border-red-500');
                } else {
                    cantidadInput.removeClass('border-red-500');
                }

                const subtotal = precio * cantidad;
                row.find('td.subtotal').text(subtotal.toFixed(2));
                sumaTotal += subtotal;
            });

            const ivaValor = sumaTotal * 0; // IVA en 0 por ahora
            const totalConIVA = sumaTotal + ivaValor;

            suma.text(sumaTotal.toFixed(2));
            iva.text(ivaValor.toFixed(2));
            total.text(totalConIVA.toFixed(2));
            inputTotal.val(totalConIVA.toFixed(2));
        }

        ventas.change(function() {
            const ventaId = $(this).val();

            if (ventaId) {
                $.ajax({
                    url: `/ventas-devoluciones/${ventaId}`,
                    method: "GET",
                    success: function(response) {
                        $('#persona_nombre').val(response.persona.nombre);
                        $('#sucursal_nombre').val(response.sucursal.nombre);
                        $('#id_persona').val(response.persona.id);
                        $('#id_sucursal').val(response.sucursal.id);

                        tablaDetalles.empty();

                        response.detalles.forEach((detalle, index) => {
                            const subtotal = detalle.precio * detalle.cantidad;

                            const row = `
                                <tr>
                                    <td>${detalle.producto.nombre}</td>
                                    <td>${detalle.cantidad}</td>
                                    <td>${detalle.precio}</td>
                                    <td class="cantidad-cell">
                                        <input type="hidden" name="detalles[${index}][producto_id]" value="${detalle.producto.id}">
                                        <input type="hidden" name="detalles[${index}][precio]" value="${detalle.precio}">
                                        <input type="number" name="detalles[${index}][cantidad]" value="${detalle.cantidad}" min="1" max="${detalle.cantidad}" class="cantidad-input w-20 border rounded px-2 py-1">
                                    </td>
                                    <td class="subtotal">${subtotal.toFixed(2)}</td>
                                    <td>
                                        <button type="button" class="btn-eliminar text-red-600 hover:text-red-800 font-bold">
                                            <i class="p-3 cursor-pointer fa-solid fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            `;
                            tablaDetalles.append(row);
                        });

                        recalcularTotales();
                    },
                    error: function() {
                        alert('Error al cargar los detalles de la venta');
                    }
                });
            }
        });

        // Recalcular al cambiar cantidad
        tablaDetalles.on('input', '.cantidad-input', function() {
            recalcularTotales();
        });

        // Eliminar fila
        tablaDetalles.on('click', '.btn-eliminar', function() {
            $(this).closest('tr').remove();
            recalcularTotales();
        });
    });
</script>


<!-- Mostrar/ocultar vencimiento -->
<script>
    $('#toggle_vencimiento').on('change', function() {
        if ($(this).is(':checked')) {
            $('#input_vencimiento').show();
        } else {
            $('#input_vencimiento').hide().val('');
        }
    });
</script>


<script>
    $('#toggle_vencimiento').on('change', function() {
        if ($(this).is(':checked')) {
            $('#input_vencimiento').show();
        } else {
            $('#input_vencimiento').hide();
            $('#input_vencimiento').val('');
        }
    });
</script>

@endpush