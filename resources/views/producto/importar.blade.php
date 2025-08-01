@extends('template')
@push('css')
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">
    
@endpush


@section('titulo', 'Importar Productos')

@section('contenido')
<div class="flex justify-center items-center mx-3">
    <div class="bg-white p-5 rounded-xl shadow-lg w-full max-w-3xl">
        <div class="flex flex-col sm:flex-row sm:justify-between md:justify-between">
            <h2 class="text-2xl font-bold mb-5">Cargar Productos desde Excel</h2>    
            <button class="btn btn-sm bg-green-200 hover:bg-green-300 hover:border-green-200 max-w-36 w-auto mb-2   " onclick="my_modal_4.showModal()">Ver Formato</button> 
        </div>
        <!-- modal para visualizar formato de tabla -->
         <dialog id="my_modal_4" class="modal">
                <div class="modal-box w-11/12 max-w-5xl">
                    <div class="flex flex-col sm:flex-row gap-2">
                        <h3 class="text-lg font-bold">Formato de Excel</h3> 
                        <button onclick="descargarFormato()" class="btn btn-sm text-white bg-red-600 hover:bg-red-500 ml-2 max-w-[400px]">
                                <i class="fas fa-download mr-1"></i> Descargar
                        </button>

                    </div>
                    

                    {{-- tabla modelo --}}
                    <div class="overflow-x-auto">
                        <table class="table  table-md table-pin-rows table-pin-cols">
                            <thead>
                            <tr>
                                <td>Codigo</td>
                                <td>Nombre</td>
                                <td>precio_costo</td>
                                <td>Categoria</td>
                            </tr>
                            </thead>
                            <tbody>
                            <tr>
                                <td>A001</td>
                                <td>Acetaminofén</td>
                                <td>6</td>
                                <td>Analgésicos</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="modal-action">
                    <form method="dialog">
                        <button class="btn bg-red-600 hover:bg-red-500 text-white">Close</button>
                    </form>
                    </div>
                </div>
                </dialog>

     

        <form action="{{ route('productos.importar.procesar') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">Archivo Excel</label>
                <input type="file" name="archivo" id="archivo" accept=".xlsx,.xls" class="block w-full text-sm text-gray-500
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-md file:border-0
                    file:text-sm file:font-semibold
                    file:bg-blue-50 file:text-blue-700
                    hover:file:bg-blue-100" >
                <p class="mt-1 text-sm text-gray-500">Formatos soportados: .xlsx, .xls</p>
            </div>
            <button id="cargar">prueba</button>

            <div class="mt-6 flex items-center justify-end gap-x-6">
                <a href="{{ route('productos.index') }}" class="text-sm font-semibold text-gray-900">Cancelar</a>
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">Previsualizar</button>
            </div>
        </form>
    </div>
</div>
@endsection
@push('js')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function descargarFormato() {
            const link = document.createElement('a');
            link.href = '/plantillas/formato.xlsx';
            link.download = 'formato.xlsx';
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    </script>

    <script>
          $(document).ready(function(){            
            const archivo = document.getElementById('archivo');
            console.log(archivo);
            const extencion = archivo.accept
            //const format = split
            console.log(extencion)
            //const validacion = document.getElementById('archivo');            
            //  const aceptacion = validacion.accept;

            $('#cargar').click(function(){
                    validaciones();
                });            
            });

            function validaciones(){
                Swal.fire('Error', 'El formato es incorrecto, este debe de ser .xlsx,.xls','error')
                return;
            }
    </script>
@endpush