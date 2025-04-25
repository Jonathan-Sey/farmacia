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
@endpush

@section('contenido')

<div class="flex justify-center items-center mx-3">
    <div class="bg-white rounded-xl shadow-lg w-full max-w-3xl mb-10 p-5">
        <form action="{{ route('devoluciones.store') }}" method="POST">
            @csrf
            <div class="border-b border-gray-900/10 pb-12">
                <div id="usuario"></div>

                <div class="mt-2 mb-5">
                    <label for="id_venta" class="uppercase block text-sm font-medium text-gray-900">numero de la venta</label>
                        <input type="number"
                         name="id_venta" id="id_venta" placeholder="numero de la venta"
                         class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">     
                </div>

                <div class="mt-2 mb-5">
                    <label  for="producto" class="uppercase block text-sm font-medium text-gray-900">producto</label>
                    <select name="producto" id="producto" 
                    class="select2 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">
                    <option value="">seleccionar el producto</option>
                    @foreach($productos as $producto)
                    <option value="{{$producto->id}}">{{$producto->nombre}}</option>
                    @endforeach
                    </select>
                </div>

                <div class="mt-2 mb-5">
                    <label for="cantidad" class="uppercase block text-sm font-medium text-gray-900">cantidad</label>
                    <input type="number" name="cantidad" id="cantidad"
                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">
                </div>

                <div    class="mt-2 mb-5">
                    <label for="monto" class="uppercase block text-sm font-medium text-gray-900" >monto</label>
                    <input type="number" name="monto" id="monto"
                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">
                </div>

                <div class="mt-2 mb-5">
                    <label for="motivo" class="uppercase block text-sm font-medium text-gray-900" >motivo</label>
                    <textarea type="text" name="motivo" id="motivo"
                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">
                    </textarea>
                </div>

          

                <div    class="mt-2 mb-5">
                    <label for="observaciones" class="uppercase block text-sm font-medium text-gray-900">observaciones</label>

                    <textarea  name="observaciones" id="observaciones"
                    class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">
                    </textarea>
                </div>

                <div class="mt-2 mb-5">
                    <label for="usuario" class="uppercase block text-sm font-medium text-gray-900">Usuario</label>
                    <select name="usuario" id="usuario" 
                    class="select2 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">>
                        <option value="">seleccionar un usuario</option>   
                    @foreach($personas as $persona)
                        <option value="{{$persona->id}}">{{$persona->nombre}}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mt-2 mb-5">
                    <label for="sucursal" class="uppercase block text-sm font-medium text-gray-900">sucursal</label>
                    <select name="sucursal" id="sucursal"
                    class="select2 block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">>
                        <option value="">seleccionar una sucursal</option>    
                    @foreach($sucursales as $sucursal)
                        <option value="{{$sucursal->id}}">{{$sucursal->nombre}}</option>
                        @endforeach
                    </select>
                </div>


            </div>
            <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 m-3 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-indigo-600">Guardar</button>

        </form>
    </div>
</div>



@endsection
@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdn.jsdelivr.net/npm/jquery@3.6.0/dist/jquery.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
{{-- select2 de productos y sucursales --}}
<script>
    //uso del select2 para proveedores
    $(document).ready(function() {
        $('.select2-sucursal').select2({
            width: '100%',
            placeholder: "Buscar",
            allowClear: true
        });
        // pocicionar el cursor en el input para buscar producto
        $('.select2-sucursal').on('select2-sucursal:open', function() {
            document.querySelector('.select2-search__field').focus();
        });
    });
</script>
<script>
    $(document).ready(function() {
        // Escuchar el cambio en el select de sucursal
        $('#id_sucursal').change(function() {
            var sucursalId = $(this).val(); // Obtener el id de la sucursal seleccionada

            if (sucursalId) {
                // Hacer una petición AJAX para obtener los productos de la sucursal seleccionada
                $.ajax({
                    url: '/productos/sucursal/' + sucursalId, // Ruta que proporcionaremos en el controlador
                    method: 'GET',
                    success: function(response) {
                        // Limpiar el select de productos
                        $('#id_producto').empty();
                        $('#id_producto').append('<option value="">Buscar un producto</option>');

                        // Llenar el select de productos con los productos obtenidos
                        response.forEach(function(producto) {
                            $('#id_producto').append(`
                                    <option value="${producto.id}"
                                        data-precio="${producto.precio_venta}"
                                        data-nombre="${producto.nombre}"
                                        data-tipo="${producto.tipo}"
                                        data-stock="${producto.stock}"
                                        data-imagen="${producto.imagen}">
                                        ${producto.nombre} - Precio: ${producto.precio_venta}
                                    </option>
                                `);
                        });
                    },
                    error: function() {
                        alert('Error al cargar los productos');
                    }
                });
            } else {
                // Si no se selecciona una sucursal, limpiar el select de productos
                $('#id_producto').empty();
                $('#id_producto').append('<option value="">Buscar un producto</option>');
            }
        });
    });
</script>

<script>
    $(document).ready(function() {
        $('#id_producto').change(function() {
            const selectedOption = $(this).find('option:selected');
            const imagenUrl = selectedOption.data('imagen'); // Obtener la URL de la imagen

            if (imagenUrl) {
                $('#imagen-producto').removeClass('hidden'); // Mostrar el contenedor de la imagen
                $('#imagen').attr('src', imagenUrl); // Actualizar la imagen
            } else {
                $('#imagen-producto').addClass('hidden'); // Ocultar el contenedor de la imagen
            }
        });
    });
</script>

<script>
    $(document).ready(function() {

        $('.select2-producto').select2();

        // Actualizar stock al seleccionar producto
        $('#id_producto').on('change', function() {
            const selectedOption = $(this).find('option:selected');
            const tipo = selectedOption.data('tipo');
            const stock = selectedOption.data('stock');

            if (tipo === 1) { // si es Producto
                $('#stock').val(stock).prop('readonly', true);
                $('#cantidad').prop('disabled', false).attr('placeholder', 'Ingrese la cantidad');
            } else { // si es servicio
                $('#stock').val('').prop('placeholder', 'N/A');
                // nota se agrego 0 o puede dejarse como n/a, ya que es un servicio codigo nuevo
                $('#impuesto').val('').prop('placeholder', 'N/A');
                // fin
                $('#cantidad').prop('disabled', true).val('').attr('placeholder', 'No aplica');

            }
        });

        //obtener datos de producto
        $('#id_producto').change(mostrarValores);


        $('#btn-agregar').click(function() {
            agregarProducto();
        });

        $('#impuesto').val(impuesto + '%');

        $('#porcentaje').on('input', function() {
            mostrarValores();
        });


    })

    let precioProducto
    let nombreProducto

    function mostrarValores() {
        let selectProducto = document.getElementById('id_producto');
        let precioBase = parseFloat(selectProducto.options[selectProducto.selectedIndex].getAttribute('data-precio'));

        let porcentaje = parseFloat($('#porcentaje').val()) || 0;
        let precioConAumento = round(precioBase + (precioBase * (porcentaje / 100)));

        precioProducto = precioConAumento;
        nombreProducto = selectProducto.options[selectProducto.selectedIndex].getAttribute('data-nombre');

        $('#precio').val(precioProducto);
    }



    let contador = 0;
    let subtotal = [];
    let suma = 0;
    let iva = 0;
    let total = 0;


    const impuesto = 12;

    function agregarProducto() {
        let idSucursal = $('#id_sucursal').val(); // nuevo dato a obtener
        let id_producto = $('#id_producto').val();
        let producto = nombreProducto;
        let cantidad = parseInt($('#cantidad').val());
        let precio = parseFloat(precioProducto); // Ya tiene el porcentaje aplicado
        let stock = parseInt($('#stock').val()) || 0;
        let tipo = $('#id_producto').find('option:selected').data('tipo');
        let aplicarImpuesto = $('#impuesto-checkbox').is(':checked');

        // nueva validacion aca verificamos si el producto ya esta en el detalle compra
        let productoExistente = $(`#tabla-productos tbody tr input[name="arrayIdProducto[]"][value="${id_producto}"]`).closest('tr');
        if (productoExistente.length > 0) {
            // Si el producto ya está en la tabla, editar la cantidad
            let index = productoExistente.find('th').text();
            editarProducto(index, idSucursal);
            return;
        }

        if (id_producto != '' && producto != '' && precio > 0) {
            if (tipo === 1) { // validar si es producto
                if (!cantidad || cantidad <= 0 || cantidad % 1 !== 0) {
                    mensaje('Favor ingresar una cantidad válida.');
                    return;
                }
                if (cantidad > stock) {
                    mensaje(`La cantidad ingresada (${cantidad}) supera el stock disponible (${stock}).`);
                    return;
                }
            } else {
                cantidad = 1;
            }

            contador++;
            subtotal[contador] = round(cantidad * precio);
            suma += subtotal[contador];

            if (tipo === 1 && aplicarImpuesto) {
                iva += round((subtotal[contador] / 100) * impuesto);
            }

            total = round(suma + iva);

            $('#tabla-productos tbody').append(`
                    <tr id="fila${contador}">
                        <th>${contador}</th>
                        <td><input type="hidden" name="arrayIdProducto[]" value="${id_producto}">${producto}</td>
                        <td><input type="hidden" name="arraycantidad[]" value="${cantidad}">${cantidad}</td>
                        <td><input type="hidden" name="arrayprecio[]" value="${precio}">${precio}</td>
                        <td>${subtotal[contador]}</td>
                        <td>
                            <button type="button" onclick="editarProducto('${contador}')"><i class="p-3 cursor-pointer fa-solid fa-edit"></i></button>
                            <button type="button" onclick="eliminarProducto('${contador}')"><i class="p-3 cursor-pointer fa-solid fa-trash"></i></button>
                        </td>

                    </tr>
                `);

            limpiar();

            $('#suma').html(suma);
            $('#iva').html(iva);
            $('#total').html(total);
            $('#impuesto').val(iva);
            $('#inputTotal').val(total);




        } else {
            mensaje('Los campos están vacíos o son inválidos.');
        }
    }

    function editarProducto(index) {
        let idSucursal = $('#id_sucursal').val(); // Obtener el ID de la sucursal seleccionada
        let cantidadActual = $(`#fila${index} input[name="arraycantidad[]"]`).val();
        let idProducto = $(`#fila${index} input[name="arrayIdProducto[]"]`).val();
        let tipo = $(`#fila${index} input[name="arrayIdProducto[]"]`).closest('tr').find('input[name="arraytipo[]"]').val();

        if (tipo === 2) { // Si es servicio, no permitir editar la cantidad
            mensaje('No se puede editar la cantidad de un servicio.');
            return;
        }

        // Obtener el stock disponible del producto desde el servidor
        $.ajax({
            url: '/productos/stock/' + idProducto + '/' + idSucursal, // Ruta para obtener el stock del producto
            method: 'GET',
            success: function(response) {
                let stockDisponible = response.stock;
                if (stockDisponible === undefined) {
                    mensaje('No se pudo obtener el stock del producto.');
                    return;
                }

                Swal.fire({
                    title: 'Editar Cantidad',
                    input: 'number',
                    inputValue: cantidadActual,
                    inputAttributes: {
                        min: 1,
                        max: stockDisponible,
                        step: 1
                    },
                    showCancelButton: true,
                    confirmButtonText: 'Guardar',
                    cancelButtonText: 'Cancelar',
                    inputValidator: (value) => {
                        if (!value || value <= 0 || value > stockDisponible) {
                            return `La cantidad debe ser mayor que 0 y no superar el stock disponible (${stockDisponible}).`;
                        }
                    }
                }).then((result) => {
                    if (result.isConfirmed) {
                        let nuevaCantidad = parseInt(result.value);
                        let precio = parseFloat($(`#fila${index} input[name="arrayprecio[]"]`).val());
                        let aplicarImpuesto = $('#impuesto-checkbox').is(':checked');

                        // Recalcular el subtotal, suma, IVA y total
                        subtotal[index] = round(nuevaCantidad * precio);
                        suma = subtotal.reduce((a, b) => a + b, 0);

                        if (aplicarImpuesto) {
                            iva = round(suma / 100 * impuesto);
                        } else {
                            iva = 0;
                        }

                        total = round(suma + iva);

                        // Actualizar los valores en la fila de la tabla
                        $(`#fila${index} input[name="arraycantidad[]"]`).val(nuevaCantidad); // Actualizar cantidad en el input oculto
                        $(`#fila${index} td:eq(1)`).html(`<input type="hidden" name="arraycantidad[]" value="${nuevaCantidad}">${nuevaCantidad}`); // Actualizar cantidad visible
                        $(`#fila${index} input[name="arrayprecio[]"]`).val(precio); // Actualizar precio en el input oculto
                        $(`#fila${index} td:eq(2)`).html(`<input type="hidden" name="arrayprecio[]" value="${precio}">${precio}`); // Actualizar precio visible
                        $(`#fila${index} td:eq(3)`).text(subtotal[index].toFixed(2)); // Actualizar subtotal visible

                        // Actualizar los valores en la interfaz
                        $('#suma').html(suma.toFixed(2));
                        $('#iva').html(iva.toFixed(2));
                        $('#total').html(total.toFixed(2));
                        $('#impuesto').val(iva.toFixed(2));
                        $('#inputTotal').val(total.toFixed(2));
                    }
                });
            },
            error: function(xhr, status, error) {
                console.error('Error en la solicitud AJAX:', xhr.responseText);
                mensaje('Error al obtener el stock disponible del producto.');
            }
        });
    }



    function eliminarProducto(index) {
        // recalculamos el detalle de venta
        // suma -= round(subtotal[index]);
        // iva = round(suma / 100 * impuesto);
        // total = round(suma + iva);

        // segunda foram, recalcular los precios
        suma -= subtotal[index];
        let producto = $(`#fila${index} input[name="arrayIdProducto[]"]`).closest('tr');
        let tipo = producto.find('input[name="arraytipo[]"]').val();

        // Si el producto tenía IVA aplicado, restar el IVA correspondiente
        if (tipo === 1 && $('#impuesto-checkbox').is(':checked')) {
            iva -= round((subtotal[index] / 100) * impuesto);
        }
        // Recalcular el total
        total = round(suma + iva);

        // Si no hay productos, restablecer los valores a 0
        if ($('#tabla-productos tbody tr').length === 1) { // Solo queda la fila de encabezado
            suma = 0;
            iva = 0;
            total = 0;
        }

        // mostramos los nuevos datos
        // $('#suma').html(suma);
        // $('#iva').html(iva);
        // $('#total').html(total);
        // $('#impuesto').val(iva);
        // $('#inputTotal').val(total);
        $('#suma').html(suma.toFixed(2));
        $('#iva').html(iva.toFixed(2));
        $('#total').html(total.toFixed(2));
        $('#impuesto').val(iva.toFixed(2));
        $('#inputTotal').val(total.toFixed(2));

        //eliminamos la fila
        $('#fila' + index).remove();
        // Eliminar el subtotal del array
        delete subtotal[index];
    }



    // Limpiar los campos
    function limpiar() {
        $('#id_producto').val(null).trigger('change');
        $('#producto').val('');
        $('#cantidad').val('');
        $('#precio').val('');
    }

    function mensaje(texto) {
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: texto,
        });
    }

    function generarResumenVenta() {
        let resumen = '<h4>Resumen de la Venta</h4>';
        resumen += '<table class="table table-bordered">';
        resumen += '<thead><tr><th>Producto</th><th>Cantidad</th><th>Precio</th><th>Subtotal</th></tr></thead>';
        resumen += '<tbody>';

        $('#tabla-productos tbody tr').each(function() {
            let producto = $(this).find('td:eq(0)').text();
            let cantidad = $(this).find('td:eq(1)').text();
            let precio = $(this).find('td:eq(2)').text();
            let subtotal = $(this).find('td:eq(3)').text();

            resumen += `<tr><td>${producto}</td><td>${cantidad}</td><td>${precio}</td><td>${subtotal}</td></tr>`;
        });

        resumen += '</tbody></table>';
        resumen += `<p><strong>Subtotal:</strong> ${$('#suma').text()}</p>`;
        resumen += `<p><strong>IVA:</strong> ${$('#iva').text()}</p>`;
        resumen += `<p><strong>Total:</strong> ${$('#total').text()}</p>`;

        return resumen;
    }


    // modal para canselar la compra
    document.getElementById('btn-cancelar').addEventListener('click', function(event) {
        event.preventDefault();
        Swal.fire({
            title: "Estas seguro de esto?",
            text: "Quieres cancelar esta Venta!",
            icon: "warning",
            showCancelButton: true,
            confirmButtonColor: "#3085d6",
            cancelButtonColor: "#d33",
            confirmButtonText: "Si, cancelar!"
        }).then((result) => {
            if (result.isConfirmed) {
                Swal.fire({
                    title: "Cancelado!",
                    text: "La venta fue cancelada.",
                    icon: "success"
                }).then(() => {
                    window.location.href = "{{ route('ventas.index') }}";
                });
            }
        });
    });


    function mensaje(message, icon = "error") {
        const Toast = Swal.mixin({
            toast: true,
            position: "top-end",
            showConfirmButton: false,
            timer: 3000,
            timerProgressBar: true,
            didOpen: (toast) => {
                toast.onmouseenter = Swal.stopTimer;
                toast.onmouseleave = Swal.resumeTimer;
            }
        });
        Toast.fire({
            icon: icon,
            title: message
        });
    }


    // funcion para redondear los numeros
    // funete: https://es.stackoverflow.com/questions/48958/redondear-a-dos-decimales-cuando-sea-necesario
    function round(num, decimales = 2) {
        var signo = (num >= 0 ? 1 : -1);
        num = num * signo;
        if (decimales === 0) //con 0 decimales
            return signo * Math.round(num);
        // round(x * 10 ^ decimales)
        num = num.toString().split('e');
        num = Math.round(+(num[0] + 'e' + (num[1] ? (+num[1] + decimales) : decimales)));
        // x * 10 ^ (-decimales)
        num = num.toString().split('e');
        return signo * (num[0] + 'e' + (num[1] ? (+num[1] - decimales) : -decimales));
    }
</script>

@if(session('error'))
<div class="alert-message">
    <span>{{ session('error') }}</span>
</div>
@endif

<script>
    document.addEventListener("DOMContentLoaded", function() {
        const errorMessage = document.querySelector('.alert-message span').textContent;
        if (errorMessage) {
            alert(errorMessage);
        }
    });
</script>

<script>
    document.addEventListener('DOMContentLoaded', function() {

        // Manejar el envío del formulario de venta
        document.getElementById('formVenta').addEventListener('submit', function(e) {
            e.preventDefault(); // Evitar que el formulario se envíe automáticamente

            // Generar el resumen de la venta
            let resumen = generarResumenVenta();

            // Mostrar el resumen y pedir confirmación
            Swal.fire({
                title: 'Confirmar Venta',
                html: resumen,
                icon: 'info',
                showCancelButton: true,
                confirmButtonText: 'Guardar Venta',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
            }).then((result) => {
                if (result.isConfirmed) {
                    // Si el usuario confirma, enviar el formulario
                    this.submit();
                }
            });
        });


        document.getElementById('formPersona').addEventListener('submit', function(e) {
            e.preventDefault(); // Evitar el envío tradicional del formulario
            e.stopPropagation();

            const formData = new FormData(this);
            // Depuración: Verifica el valor de "rol"
            console.log('Valor de rol:', formData.get('rol'));


            fetch(this.action, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                        'Accept': 'application/json',
                    },
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw err;
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        // Cerrar el modal
                        my_modal_1.close();

                        // Limpiar los campos del formulario
                        document.getElementById('nombre').value = '';
                        document.getElementById('nit').value = '';
                        document.getElementById('rol').value = '1';

                        // Actualizar el select de personas
                        const selectPersona = document.getElementById('id_persona');
                        selectPersona.innerHTML = '<option value="">Seleccionar una persona</option>';

                        data.personas.forEach(persona => {
                            const newOption = new Option(persona.nit, persona.id, false, false);
                            selectPersona.appendChild(newOption);
                        });

                        // Seleccionar automáticamente la persona recién creada
                        selectPersona.value = data.persona.id;

                        $(selectPersona).trigger('change');
                        // Mostrar mensaje de éxito
                        Swal.fire({
                            icon: 'success',
                            title: 'Persona registrada',
                            text: 'La persona se ha registrado correctamente.',
                        });
                    }
                })
                .catch(error => {
                    if (error.errors) {
                        // Limpiar errores anteriores
                        document.querySelectorAll('.error-message').forEach(el => el.remove());

                        for (let field in error.errors) {
                            const inputField = document.querySelector(`[name="${field}"]`);
                            if (inputField) {
                                const errorMessage = document.createElement('div');
                                errorMessage.className = 'error-message text-red-500 text-sm mt-1';
                                errorMessage.innerHTML = error.errors[field].join('<br>');
                                inputField.parentNode.insertBefore(errorMessage, inputField.nextSibling);
                            }
                        }
                    } else {
                        // Mostrar mensaje de error genérico
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Ocurrió un error al registrar la persona.',
                        });
                    }
                });
        });
    });
</script>
@endpush