<header>        
    <div class="flex absolute bg-gray-100 min-h-screen">
        <!-- Barra lateral (pantallas grandes) -->
        <div class="hidden md:flex flex-col w-56 bg-white rounded-r-3xl overflow-hidden">
            <div class="flex items-center justify-center h-20 shadow-md">
                <h1 class="text-3xl uppercase text-indigo-500">Farmacia</h1>
            </div>
            <ul class="flex flex-col py-4">
                <li>
                    <a href="/dashboard" class="flex flex-row items-center h-12 transform hover:translate-x-2 transition-transform ease-in duration-200 text-gray-500 hover:text-gray-800">
                        <span class="inline-flex items-center justify-center h-12 w-12 text-lg text-gray-400"><i class="bx bx-home"></i></span>
                        <span class="text-sm font-medium">Dashboard</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('roles.index') }}" class="flex flex-row items-center h-12 transform hover:translate-x-2 transition-transform ease-in duration-200 text-gray-500 hover:text-gray-800">
                        <span class="inline-flex items-center justify-center h-12 w-12 text-lg text-gray-400"><i class='bx bx-briefcase-alt'></i></span>
                        <span class="text-sm font-medium">Roles</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('categorias.index') }}" class="flex flex-row items-center h-12 transform hover:translate-x-2 transition-transform ease-in duration-200 text-gray-500 hover:text-gray-800">
                        <span class="inline-flex items-center justify-center h-12 w-12 text-lg text-gray-400"><i class="bx bx-category"></i></span>
                        <span class="text-sm font-medium">Categorias</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('sucursales.index') }}" class="flex flex-row items-center h-12 transform hover:translate-x-2 transition-transform ease-in duration-200 text-gray-500 hover:text-gray-800">
                        <span class="inline-flex items-center justify-center h-12 w-12 text-lg text-gray-400"><i class='bx bxs-building'></i></span>
                        <span class="text-sm font-medium">Sucursal</span>
                    </a>
                </li>
                <li>
                    <a href="{{ route('productos.index') }}" class="flex flex-row items-center h-12 transform hover:translate-x-2 transition-transform ease-in duration-200 text-gray-500 hover:text-gray-800">
                        <span class="inline-flex items-center justify-center h-12 w-12 text-lg text-gray-400"><i class='bx bxs-package'></i></span>
                        <span class="text-sm font-medium">Productos</span>
                    </a>
                </li>
            </ul>
        </div>

        <!-- Barra móvil (pantallas pequeñas) -->
        <div class="flex md:hidden bg-white w-full shadow-md fixed top-0 z-50">
            <div class="flex justify-between items-center w-full px-4 h-16">
                <h1 class="text-xl uppercase text-indigo-500">Farmacia</h1>
                <button id="menu-btn" class="text-gray-500 focus:outline-none">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"></path>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Menú desplegable móvil -->
        <ul id="menu" class="hidden md:hidden bg-white shadow-md flex-col fixed top-16 left-0 w-full z-40">
            <li class="px-6 py-2 hover:bg-gray-100">
                <a href="/dashboard">Dashboard</a>
            </li>
            <li class="px-6 py-2 hover:bg-gray-100">
                <a href="{{ route('roles.index') }}">Roles</a>
            </li>
            <li class="px-6 py-2 hover:bg-gray-100">
                <a href="{{ route('categorias.index') }}">Categorias</a>
            </li>
            <li class="px-6 py-2 hover:bg-gray-100">
                <a href="{{ route('sucursales.index') }}">Sucursal</a>
            </li>
            <li class="px-6 py-2 hover:bg-gray-100">
                <a href="{{ route('productos.index') }}">Productos</a>
            </li>
        </ul>
    </div>
</header>
