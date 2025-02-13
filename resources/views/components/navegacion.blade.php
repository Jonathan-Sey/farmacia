<header>
    <div class="flex">
        <!-- Barra lateral fija -->
        <div class="hidden md:flex flex-col w-56 bg-white rounded-r-3xl overflow-hidden fixed top-0 left-0 h-full overflow-y-auto max-h-screen">
            <div class="flex items-center justify-center h-20 shadow-md">
                <h1 class="text-3xl uppercase text-indigo-500">Farmacia</h1>
            </div>
            <ul class="flex flex-col py-4">
                <!-- Dashboard -->
                <li data-pestana="Dashboard">
                    <a href="/dashboard" class="flex flex-row items-center h-12 transform hover:translate-x-2 transition-transform ease-in duration-200 text-gray-500 hover:text-gray-800">
                        <span class="inline-flex items-center justify-center h-12 w-12 text-lg text-gray-400"><i class="bx bx-home"></i></span>
                        <span class="text-sm font-medium">Dashboard</span>
                    </a>
                </li>

                <li data-pestana="Usuarios" style="display:none;">
                    <a href="{{ route('usuarios.index') }}" class="flex flex-row items-center h-12 transform hover:translate-x-2 transition-transform ease-in duration-200 text-gray-500 hover:text-gray-800">
                        <span class="inline-flex items-center justify-center h-12 w-12 text-lg text-gray-400"><i class='bx bxs-user'></i></span>
                        <span class="text-sm font-medium">Usuarios</span>
                    </a>
                </li>
                <li data-pestana="Rol" style="display:none;">
                    <a href="{{ route('roles.index') }}" class="flex flex-row items-center h-12 transform hover:translate-x-2 transition-transform ease-in duration-200 text-gray-500 hover:text-gray-800">
                        <span class="inline-flex items-center justify-center h-12 w-12 text-lg text-gray-400"><i class="bx bx-briefcase-alt"></i></span>
                        <span class="text-sm font-medium">Roles</span>
                    </a>
                </li>
                <li data-pestana="Categorias" style="display:none;">
                    <a href="{{ route('categorias.index') }}" class="flex flex-row items-center h-12 transform hover:translate-x-2 transition-transform ease-in duration-200 text-gray-500 hover:text-gray-800">
                        <span class="inline-flex items-center justify-center h-12 w-12 text-lg text-gray-400"><i class="bx bx-category"></i></span>
                        <span class="text-sm font-medium">Categorías</span>
                    </a>
                </li>
                <li data-pestana="Proveedores" style="display:none;">
                    <a href="{{ route('proveedores.index') }}" class="flex flex-row items-center h-12 transform hover:translate-x-2 transition-transform ease-in duration-200 text-gray-500 hover:text-gray-800">
                        <span class="inline-flex items-center justify-center h-12 w-12 text-lg text-gray-400"><i class="bx bxs-package"></i></span>
                        <span class="text-sm font-medium">Proveedores</span>
                    </a>
                </li>
                <li data-pestana="Sucursales" style="display:none;">
                    <a href="{{ route('sucursales.index') }}" class="flex flex-row items-center h-12 transform hover:translate-x-2 transition-transform ease-in duration-200 text-gray-500 hover:text-gray-800">
                        <span class="inline-flex items-center justify-center h-12 w-12 text-lg text-gray-400"><i class="bx bxs-building"></i></span>
                        <span class="text-sm font-medium">Sucursal</span>
                    </a>
                </li>
                <li data-pestana="Productos" style="display:none;">
                    <a href="{{ route('productos.index') }}" class="flex flex-row items-center h-12 transform hover:translate-x-2 transition-transform ease-in duration-200 text-gray-500 hover:text-gray-800">
                        <span class="inline-flex items-center justify-center h-12 w-12 text-lg text-gray-400"><i class="bx bxs-package"></i></span>
                        <span class="text-sm font-medium">Productos</span>
                    </a>
                </li>

                <li data-pestana="Almacenes" style="display:none;">
                    <a href="{{ route('almacenes.index') }}" class="flex flex-row items-center h-12 transform hover:translate-x-2 transition-transform ease-in duration-200 text-gray-500 hover:text-gray-800">
                        <span class="inline-flex items-center justify-center h-12 w-12 text-lg text-gray-400"><i class="fa-solid fa-store"></i></span>
                        <span class="text-sm font-medium">Almacenes</span>
                    </a>
                </li>
                <li data-pestana="Compras" style="display:none;">
                    <a href="{{ route('compras.index') }}" class="flex flex-row items-center h-12 transform hover:translate-x-2 transition-transform ease-in duration-200 text-gray-500 hover:text-gray-800">
                        <span class="inline-flex items-center justify-center h-12 w-12 text-lg text-gray-400"><i class="fa-solid fa-cart-shopping"></i></span>
                        <span class="text-sm font-medium">Compras</span>
                    </a>
                </li>
                <li data-pestana="Ventas" style="display:none;">
                    <a href="{{ route('ventas.index') }}" class="flex flex-row items-center h-12 transform hover:translate-x-2 transition-transform ease-in duration-200 text-gray-500 hover:text-gray-800">
                        <span class="inline-flex items-center justify-center h-12 w-12 text-lg text-gray-400"><i class="fa-solid fa-bag-shopping"></i></span>
                        <span class="text-sm font-medium">Ventas</span>
                    </a>
                </li>
                <li data-pestana="Personas" style="display:none;">
                    <a href="{{ route('personas.index') }}" class="flex flex-row items-center h-12 transform hover:translate-x-2 transition-transform ease-in duration-200 text-gray-500 hover:text-gray-800">
                        <span class="inline-flex items-center justify-center h-12 w-12 text-lg text-gray-400"><i class="fa-solid fa-person"></i></span>
                        <span class="text-sm font-medium">Personas</span>
                    </a>
                </li>
                <li data-pestana="Medicos" style="display:none;">
                    <a href="{{ route('medicos.index') }}" class="flex flex-row items-center h-12 transform hover:translate-x-2 transition-transform ease-in duration-200 text-gray-500 hover:text-gray-800">
                        <span class="inline-flex items-center justify-center h-12 w-12 text-lg text-gray-400"><i class="fa-solid fa-briefcase-medical"></i></span>
                        <span class="text-sm font-medium">Medicos</span>
                    </a>
                </li>
                <li data-pestana="Consultas" style="display:none;">
                    <a href="{{ route('consultas.index') }}" class="flex flex-row items-center h-12 transform hover:translate-x-2 transition-transform ease-in duration-200 text-gray-500 hover:text-gray-800">
                        <span class="inline-flex items-center justify-center h-12 w-12 text-lg text-gray-400"><i class="fa-solid fa-book-medical"></i></span>
                        <span class="text-sm font-medium">Consultas</span>
                    </a>
                </li>
                <li data-pestana="traslado" style="display:none;">
                    <a href="{{ route('traslado.index') }}" class="flex flex-row items-center h-12 transform hover:translate-x-2 transition-transform ease-in duration-200 text-gray-500 hover:text-gray-800">
                        <span class="inline-flex items-center justify-center h-12 w-12 text-lg text-gray-400"><i class="fa-solid fa-money-bill-transfer"></i></span>
                        <span class="text-sm font-medium">traslado</span>
                    </a>
                </li>
                <!-- Botón Logout -->
                <li>
                    <a href="javascript:void(0);" id="logout-btn" class="flex flex-row items-center h-12 transform hover:translate-x-2 transition-transform ease-in duration-200 text-gray-500 hover:text-gray-800">
                        <span class="inline-flex items-center justify-center h-12 w-12 text-lg text-gray-400"><i class="bx bx-log-out"></i></span>
                        <span class="text-sm font-medium">Cerrar Sesión</span>
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
     <ul id="menu" class="hidden bg-white shadow-md flex-col fixed top-16 left-0 w-full z-40">

            <li data-pestanal="Dashboard" class="px-6 py-2 hover:bg-gray-100" style="display: none;">
                <a href="/dashboard"><i class="bx bx-home"></i> Dashboard</a>
            </li>
            <li data-pestanal="Usuarios" class="px-6 py-2 hover:bg-gray-100" style="display:none;">
                <a href="{{ route('usuarios.index') }}"><i class='bx bxs-user'></i> Usuarios</a>
            </li>
            <li data-pestanal="Rol" class="px-6 py-2 hover:bg-gray-100" style="display:none;">
                <a href="{{ route('roles.index') }}"><i class="bx bx-briefcase-alt"></i> Roles</a>
            </li>
            <li data-pestanal="Categorias" class="px-6 py-2 hover:bg-gray-100" style="display:none;">
                <a href="{{ route('categorias.index') }}"><i class="bx bx-category"></i> Categorias</a>
            </li>
            <li data-pestanal="Proveedores" class="px-6 py-2 hover:bg-gray-100" style="display:none;">
                <a href="{{ route('proveedores.index') }}"><i class="bx bxs-package"></i> Proveedores</a>
            </li>
            <li data-pestanal="Sucursales" class="px-6 py-2 hover:bg-gray-100" style="display:none;">
                <a href="{{ route('sucursales.index') }}"><i class="bx bxs-building"></i> Sucursales</a>
            </li>
            <li data-pestanal="Productos" class="px-6 py-2 hover:bg-gray-100" style="display:none;">
                <a href="{{ route('productos.index') }}"><i class="bx bxs-package"></i> Productos</a>
            </li>
            <li data-pestanal="Almacenes" class="px-6 py-2 hover:bg-gray-100" style="display:none;">
                <a href="{{ route('almacenes.index') }}"><i class="fa-solid fa-store"></i> Almacenes</a>
            </li>
            <li data-pestanal="Comptas" class="px-6 py-2 hover:bg-gray-100" style="display:none;">
                <a href="{{ route('compras.index') }}"><i class="fa-solid fa-cart-shopping"></i> Compras</a>
            </li>
            <li data-pestanal="Ventas" class="px-6 py-2 hover:bg-gray-100" style="display:none;">
                <a href="{{ route('ventas.index') }}"><i class="fa-solid fa-bag-shopping"></i> Ventas</a>
            </li>
            <li data-pestanal="Personas" class="px-6 py-2 hover:bg-gray-100" style="display:none;">
                <a href="{{ route('personas.index') }}"><i class="fa-solid fa-person"></i> Personas</a>
            </li>
            <li data-pestanal="Medicos" class="px-6 py-2 hover:bg-gray-100" style="display:none;">
               <a href="{{ route('medicos.index') }}"><i class="fa-solid fa-briefcase-medical"></i> Medicos</a>
            </li>
            <li data-pestanal="Consultas" class="px-6 py-2 hover:bg-gray-100" style="display:none;">
                <a href="{{ route('consultas.index') }}"><i class="fa-solid fa-book-medical"></i> Consultas</a>
            </li>
            <li data-pestanal="traslado" class="px-6 py-2 hover:bg-gray-100" style="display:none;">
                <a href="{{ route('traslado.index') }}"><i class="fa-solid fa-book-medical"></i> traslado</a>
            </li>
            <li>
                <button id="logout-btn-mobile" type="submit" class="block py-2 px-4 text-gray-700 hover:bg-red-500 hover:text-white">
                    <i class="bx bx-log-out mr-2"></i>Cerrar Sesión
                </button>
            </li>
        </ul>
    </div>
</header>
<script src="https://cdn.jsdelivr.net/npm/jwt-decode@3.1.2/build/jwt-decode.min.js"></script>
<script src="/js/logout.js"></script>

