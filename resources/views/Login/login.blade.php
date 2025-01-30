@extends('layouts.login')

@section('contenido')
<div class="min-h-screen flex items-center justify-center px-3">
    <div class="max-w-md w-full bg-[#004D4D] p-6 md:p-8 rounded-2xl shadow-lg">
        <h1 class="text-3xl sm:text-5xl font-bold text-white ">Iniciar Sesion</h1>
        <div class="mx-4 sm:mx-2 flex flex-wrap items-center justify-center">
            <form id="login-form" class="my-8 sm:my-20 mx-8 sm:mx-20 space-y-5 text-white w-full">
                <!-- Campo Email -->
                <div class="relative">
                    <div class="absolute top-1/2 left-1 transform -translate-y-1/2 flex items-center bg-[#FFFFFF30] rounded-full px-2 py-2 ring-2 ring-cyan-500">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                    <input id="email" type="email" name="email" placeholder="email" class="w-full sm:w-80 bg-[#FFFFFF30] pl-11 pr-4 py-2 rounded-full focus:bg-[#00000050] focus:outline-none focus:ring-2 focus:ring-cyan">
                </div>
                <!-- Campo Password -->
                <div class="relative">
                    <div class="absolute top-1/2 left-1 transform -translate-y-1/2 flex items-center bg-[#FFFFFF30] rounded-full px-2 py-2 ring-2 ring-cyan-500">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    <input id="password" type="password" name="password" placeholder="password" class="w-full sm:w-80 bg-[#FFFFFF30] pl-11 pr-4 py-2 rounded-full focus:bg-[#00000050] focus:outline-none focus:ring-2 focus:ring-cyan">
                </div>
                <!-- Botón -->
                <button type="submit" class="btn btn-outline font-bold w-full sm:w-80 text-white hover:bg-[#00000050] hover:text-white hover:border-white py-2 rounded-full">Iniciar Sesion</button>
            </form>
        </div>
    </div>
</div>

<script src="{{ asset('js/axios.min.js') }}"></script>
<script src="/js/controlIniciodesecion.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection