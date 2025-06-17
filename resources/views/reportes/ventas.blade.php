@extends('template')
@section('titulo', 'Reporte de ventas')
@push('css')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" />
@endpush

@section('contenido')
<div class="grid gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-8">
    <a href="{{ route('Reporte_ventas.filtrarPorFecha') }}">
        <div class="w-auto h-40 bg-slate-50 rounded-md shadow-lg flex justify-center items-center text-center sm:p-3">
            <div class="flex flex-col items-center">
                <i class='bx bxs-package text-7xl lg:text-6xl md:text-6xl sm:text-6xl'></i>
                <p class="uppercase text-lg font-bold">reporte por fecha</p>
            </div>
        </div>
    </a>

    <a href="{{ route('Reporte_ventas.filtrarPorSucursal') }}">
        <div class="w-auto h-40 bg-slate-50 rounded-md shadow-lg flex justify-center items-center text-center sm:p-3">
            <div class="flex flex-col items-center">
                <i class='bx bxs-building text-7xl lg:text-6xl sm:text-6xl'></i>
                <p class="uppercase text-lg font-bold">reporte por sucursales</p>
            </div>
        </div>
    </a>

    <a href="{{ route('Reporte_ventas.filtrarPorUsuario') }}">
        <div class="w-auto h-40 bg-slate-50 rounded-md shadow-lg flex justify-center items-center text-center sm:p-3">
            <div class="flex flex-col items-center">
                <i class='fa-solid bx bxs-user text-7xl lg:text-6xl sm:text-6xl'></i>
                <p class="uppercase text-lg font-bold">reporte por usuario</p>
            </div>
        </div>
    </a>

    <a href="{{ route('reporte.productos') }}">
        <div class="w-auto h-40 bg-slate-50 rounded-md shadow-lg flex justify-center items-center text-center sm:p-3">
            <div class="flex flex-col items-center">
                <i class='fa-solid fa-bag-shopping text-7xl lg:text-6xl sm:text-6xl'></i>
                <p class="uppercase text-lg font-bold">reporte por producto</p>
            </div>
        </div>
    </a>

    <a href="{{ route('Reporte_ventas.create') }}">
        <div class="w-auto h-40 bg-slate-50 rounded-md shadow-lg flex justify-center items-center text-center sm:p-3">
            <div class="flex flex-col items-center">
                <i class='fa-solid fa-bag-shopping text-7xl lg:text-6xl sm:text-6xl'></i>
                <p class="uppercase text-lg font-bold">reporte Ingresos y Egresos</p>
            </div>
        </div>
    </a>
</div>



    <div class="grid gap-5 grid-cols-1 items-start mb-8">
        <div class="max-h-[800px] overflow-x-auto bg-white p-2 rounded-lg shadow-lg text-center">
            <div class="md:flex md:flex-row md:gap-3 lg:justify-between p-3 pb-8 sm:flex sm:flex-col sm:gap-5 sm:justify-center">
                <h2 class="text-2xl m-2 font-bold sm:grid sm:grid-cols-1 ">Ventas por mes</h2>
                    <div>
                            <label for="sucursalSelector" class="uppercase block text-sm font-medium text-gray-900">Sucursal</label>
                            <select
                                class="select2-sucursal block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm"

                                id="sucursalSelector"
                                >
                                <option value="">Todo</option>
                                @foreach ($nombreSucursales as $sucursal)
                                    <option value="{{ $sucursal->id }}">{{$sucursal->nombre}} - {{$sucursal->ubicacion}}</option>
                                @endforeach
                            </select>
                    </div>
                <input type="month" id="mesAñoSelector" value="{{date('Y-m')}}">
            </div>
            <div id="ventasMes">

            </div>
        </div>
    </div>

@push('js')
{{-- librerias para generar graficas --}}
        <script src="https://code.highcharts.com/highcharts.js"></script>
        <script src="https://code.highcharts.com/modules/exporting.js"></script>
        <script src="https://code.highcharts.com/modules/export-data.js"></script>
        <script src="https://code.highcharts.com/modules/accessibility.js"></script>
        <script src="https://code.highcharts.com/modules/offline-exporting.js"></script>

        <script>
        // creacion de arreglo para iterar los meses pero con su respectico nombre
        const Meses = ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio","Julio","Agosto","Septiembre","Octubre","Noviembre","Diciembre"];

        function reformarChart(diasMes, ventasPorDia, totalGeneral, mes, año){
            // odenamos las ventas
            const ventasRedondeadas = ventasPorDia.map(venta => round(venta));


            // diseño de la tabla
            let totalVentas = round(totalGeneral);
            Highcharts.chart('ventasMes', {
                chart: {
                    type: 'line'
                },
                title: {
                    text: 'Ventas de '+ Meses[mes - 1] + ' del '+ año
                },
                subtitle: {
                    text: 'Total Ventas: Q.' + totalVentas
                },
                xAxis: {
                    categories: diasMes,
                    accessibility: {
                        description: 'Días del mes'
                    }
                },
                yAxis: {
                    title: {
                        text: 'Total de ingresos por día'
                    },
                    labels: {
                        formatter: function() {
                            return round(this.value); // Aplicar redondeo a las etiquetas del eje Y
                        }
                    }
                },
                tooltip: {
                    crosshairs: true,
                    shared: true,
                    formatter: function() {
                // Formatear el tooltip para mostrar valores redondeados
                        return '<b>' + this.x + '</b><br/>' +
                            this.series.name + ': <b>Q.' + round(this.y) + '</b>';
                    }
                },
                plotOptions: {
                    line: {
                        dataLabels:{
                            enabled:true,
                            formatter: function() {
                                return round(this.y); // Redondear los datos mostrados en la gráfica
                            }
                        },
                        marker: {
                            radius: 4,
                            lineColor: '#666666',
                            lineWidth: 1
                        }
                    }
                },
                series: [{
                    name: 'Ventas del día ',
                    marker: {
                        enabled: true,
                        symbol: 'circle'
                    },
                    data: ventasRedondeadas

                }]
            });

        }

        function fetchData() {
            var mesAño = document.getElementById('mesAñoSelector').value.split('-');
            //var mesAño = this.value.split('-'); // corecion de por split
            var año = mesAño[0];
            var mes = mesAño[1];
            var sucursal = document.getElementById('sucursalSelector').value;

            $.ajax({
                url: '{{ route("dashboard.filtrarVentas")}}',
                method:'GET',
                data:{
                    año: año,
                    mes: mes,
                    sucursal: sucursal,
                },
                success: function(response){
                    reformarChart(response.diasMes, response.ventasPorDia, response.totalGeneral, response.mes, response.año);
                }
            });
        }

        // Agregacion de evneto a los selectores
        document.getElementById('mesAñoSelector').addEventListener('change', fetchData);
        document.getElementById('sucursalSelector').addEventListener('change', fetchData);

        // iniciamos la grafica con los valores por default, justo lo que esta en su value
        document.addEventListener('DOMContentLoaded', function(){
            reformarChart({!! json_encode($diasMes) !!}, {!! json_encode($ventasPorDia) !!}, '{{ $totalGeneral }}', '{{ $mes }}', '{{ $año }}')
        });

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

        // proceso para traducir las opciones a español
        Highcharts.setOptions({
            lang: {
            contextButtonTitle: "Menú contextual",
            downloadCSV: "Descargar archivo CSV",
            downloadJPEG: "Descargar imagen JPEG",
            downloadPDF: "Descargar documento PDF",
            downloadPNG: "Descargar imagen PNG",
            downloadSVG: "Descargar imagen vectorial SVG",
            downloadXLS: "Descargar archivo XLS",
            viewData: "Ver tabla de datos",
            hideData: "Ocultar tabla de datos",  // Traducción de "Hide data table"
            viewFullscreen: "Ver en pantalla completa",
            exitFullscreen: "Salir de pantalla completa",  // Traducción de "Exit from full screen"
            printChart: "Imprimir gráfico",
            resetZoom: "Restablecer zoom",
            resetZoomTitle: "Restablecer nivel de zoom",
            thousandsSep: ".",
            decimalPoint: ",",
            loading: "Cargando...",
            months: ["Enero", "Febrero", "Marzo", "Abril", "Mayo", "Junio", "Julio", "Agosto", "Septiembre", "Octubre", "Noviembre", "Diciembre"],
            shortMonths: ["Ene", "Feb", "Mar", "Abr", "May", "Jun", "Jul", "Ago", "Sep", "Oct", "Nov", "Dic"],
            weekdays: ["Domingo", "Lunes", "Martes", "Miércoles", "Jueves", "Viernes", "Sábado"],
            rangeSelectorFrom: "De",
            rangeSelectorTo: "A",
            rangeSelectorZoom: "Periodo",
            },
            tooltip:{
                    valueDecimals: 2
                }
        });
        </script>

        <script>

        </script>
@endpush
@endsection
