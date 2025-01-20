@extends('template')
@section('titulo','Crear Usuario')

@push('css')

@endpush

@section('contenido')
<div class="flex justify-center items-center mx-3">
    <div class="bg-white p-5 rounded-xl shadow-lg w-full max-w-3xl mb-10">
        <form action="{{ route('usuarios.register') }}" method="POST">
            @csrf
            <div class="border-b border-gray-900/10 pb-12">
                <!-- Nombre del Usuario -->
                <div class="mt-2 mb-5">
                    <label for="nombre" class="uppercase block text-sm font-medium text-gray-900">Nombre del Usuario</label>
                    <input type="text" name="nombre" id="nombre" autocomplete="given-name" placeholder="Nombre del Usuario" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm" value="{{ old('nombre') }}">
                    @error('nombre')
                    <div role="alert" class="alert alert-error mt-4 p-2">
                        <span class="text-white font-bold">{{ $message }}</span>
                    </div>
                    @enderror
                </div>

                <!-- Email -->
                <div class="mt-2 mb-5">
                    <label for="email" class="uppercase block text-sm font-medium text-gray-900">Correo Electrónico</label>
                    <input type="email" name="email" id="email" autocomplete="email" placeholder="Correo Electrónico" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm" value="{{ old('email') }}">
                    @error('email')
                    <div role="alert" class="alert alert-error mt-4 p-2">
                        <span class="text-white font-bold">{{ $message }}</span>
                    </div>
                    @enderror
                </div>

                <!-- Contraseña -->
                <div class="mt-2 mb-5">
                    <label for="password" class="uppercase block text-sm font-medium text-gray-900">Contraseña</label>
                    <input type="text" name="password" id="password" placeholder="Contraseña" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">
                    @error('password')
                    <div role="alert" class="alert alert-error mt-4 p-2">
                        <span class="text-white font-bold">{{ $message }}</span>
                    </div>
                    @enderror
                </div>

                <!-- Selección de Rol -->
                <div class="mt-4">
                    <label for="id_rol" class="block text-sm font-medium text-gray-900">Seleccionar Rol</label>
                    <select name="id_rol" id="id_rol" class="block w-full rounded-md bg-white px-3 py-1.5 text-base text-gray-900 outline outline-1 -outline-offset-1 outline-gray-300 placeholder:text-gray-400 focus:outline focus:outline-2 focus:-outline-offset-2 focus:outline-indigo-600 sm:text-sm">
                        @foreach ($roles as $rol)
                            <option value="{{ $rol->id }}">{{ $rol->nombre }}</option>
                        @endforeach
                    </select>
                    @error('id_rol')
                    <div class="alert alert-error mt-4 p-2">
                        <span class="text-white font-bold">{{ $message }}</span>
                    </div>
                    @enderror
                </div>
            </div>

            <!-- Botones -->
            <div class="mt-6 flex items-center justify-end gap-x-6">
                <a href="{{ route('usuarios.index') }}">
                    <button type="button" class="text-sm font-semibold text-gray-900">Cancelar</button>
                </a>
                <button type="submit" class="rounded-md bg-indigo-600 px-3 py-2 text-sm font-semibold text-white shadow-sm hover:bg-indigo-500 focus-visible:outline focus-visible:outline-2 focus-visible:outline-indigo-600">Guardar</button>
            </div>
        </form>
    </div>
</div>
@endsection
@push('js')
@endpush
