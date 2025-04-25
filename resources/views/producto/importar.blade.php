@extends('template')

@section('titulo', 'Importar Productos')

@section('contenido')
<div class="flex justify-center items-center mx-3">
    <div class="bg-white p-5 rounded-xl shadow-lg w-full max-w-3xl">
        <h2 class="text-2xl font-bold mb-5">Importar Productos desde Excel</h2>

        <form action="{{ route('productos.importar.procesar') }}" method="POST" enctype="multipart/form-data">
            @csrf

            <div class="mb-5">
                <label class="block text-sm font-medium text-gray-700 mb-2">Archivo Excel</label>
                <input type="file" name="archivo" accept=".xlsx,.xls" class="block w-full text-sm text-gray-500
                    file:mr-4 file:py-2 file:px-4
                    file:rounded-md file:border-0
                    file:text-sm file:font-semibold
                    file:bg-blue-50 file:text-blue-700
                    hover:file:bg-blue-100" required>
                <p class="mt-1 text-sm text-gray-500">Formatos soportados: .xlsx, .xls</p>
            </div>

            <div class="mt-6 flex items-center justify-end gap-x-6">
                <a href="{{ route('productos.index') }}" class="text-sm font-semibold text-gray-900">Cancelar</a>
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500">Previsualizar</button>
            </div>
        </form>
    </div>
</div>
@endsection