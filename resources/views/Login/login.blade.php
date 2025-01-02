@extends('layouts.login')

@section('contenido')
<div class="max-w-[1000px] sm:max-w-full bg-[#00000050] grid grid-cols-1 p-4 rounded-2xl">
    <div class=" max-w-100 sm:max-w-full grid gap-3 overflow-hidden">
    
        <h1 class="text-5xl font-bold text-white">Iniciar Secion</h1>
    
        <form class="my-20 mx-20  space-y-5 text-white" action="" >
       
                <div class="space-y-5 grid grid-cols-1 relative">
                    <div class="absolute top-6 left-1 items-center justify-center bg-[#FFFFFF30] rounded-full px-2 py-1 ring-2 ring-cyan-500">
                        <i class="fa-solid fa-envelope"></i>
                    </div>
                        <input type="email" placeholder="Usuario" class="w-80 bg-[#FFFFFF30] px-11 py-2 rounded-full focus:bg-[#00000050] focus:outline-none focus:ring-2 focus:ring-cyan">
           
                    <div  class="absolute top-16 left-1 items-center justify-center bg-[#FFFFFF30] rounded-full px-2 py-1 ring-2 ring-cyan-500">
                        <i class="fa-solid fa-lock"></i>
                    </div>    
                    <input type="text" placeholder="Contraseña" class="w-80 bg-[#FFFFFF30] px-10 py-2 rounded-full focus:bg-[#00000050] focus:outline-none focus:ring-2 focus:ring-cyan">
                </div>
            
                <button class="btn btn-outline font-bold w-80 text-white hover:bg-[#00000050] hover:text-white hover:border-white">Iniciar Secion</button>
    </form>
    <div>
        <p class=" font-bold text-white">
            ¿Olvido la contraseña? <a href="/Recuperacion_contraseña" class="text-[#3ABFF8]">Recuperar</a>
        </p>
    </div>
</div>
</div>
@endsection

