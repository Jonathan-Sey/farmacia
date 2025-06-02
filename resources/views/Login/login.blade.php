@extends('layouts.login')

@section('contenido')
<div class="min-h-screen flex items-center justify-center px-3">
    <div class="max-w-6xl w-full bg-[#487375] p-6 md:p-9 rounded-2xl shadow-lg flex flex-col md:flex-row items-center  md:h-auto">
        <!-- Sección del formulario -->
        <div class="w-full md:w-1/2 p-6 flex flex-col justify-center items-center text-center md:text-left h-full">
            <img src="/Logos/LOGO_MUNIANTIGUA_BLANCO.png" alt="Logo_Muni" height="100px" width="300px" class="mx-auto md:mx-0 mb-4 md:mb-0">
            <h1 class="text-3xl sm:text-5xl font-bold text-white my-2">Iniciar Sesión</h1>
            <form id="login-form" class="my-8 space-y-5 text-white w-full max-w-md">
                <!-- Campo Correo -->
                <div class="relative">
                    <div class="absolute top-1/2 left-1 transform -translate-y-1/2 flex items-center bg-[#FFFFFF30] rounded-full px-2 py-2 ring-2 ring-cyan-500">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                    <input id="email" type="email" name="email" placeholder="Correo" class="w-full bg-[#FFFFFF30] pl-11 pr-4 py-2 rounded-full focus:bg-[#00000050] focus:outline-none focus:ring-2 focus:ring-cyan">
                </div>
                <!-- Campo Contraseña -->
                <div class="relative">
                    <div class="absolute top-1/2 left-1 transform -translate-y-1/2 flex items-center bg-[#FFFFFF30] rounded-full px-2 py-2 ring-2 ring-cyan-500">
                        <i class="fa-solid fa-lock"></i>
                    </div>
                    <input id="password" type="password" name="password" placeholder="Contraseña" class="w-full bg-[#FFFFFF30] pl-11 pr-4 py-2 rounded-full focus:bg-[#00000050] focus:outline-none focus:ring-2 focus:ring-cyan">
                </div>
                <!-- Botón -->
                <button type="submit" class="btn btn-outline font-bold w-full text-white hover:bg-[#00000050] hover:text-white hover:border-white py-2 rounded-full">Iniciar Sesión</button>
            </form>
        </div>
        
        <!-- Sección de la imagen -->
        <div class="w-full md:w-1/2  p-6 hidden md:block">
            <img src="/Logos/Fuente.webp" alt="Imagen de inicio de sesión" class="w-full h-[500px] max-w-sm rounded-lg shadow-md">
        </div>
    </div>
</div>

<script src="{{ asset('js/axios.min.js') }}"></script>
<script src="/js/controlIniciodesecion.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
@endsection

