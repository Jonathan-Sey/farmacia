        <header class="p-5 border-b bg-white shadow">
            <div class="container mx-auto flex justify-between items-center">
                <h1 class="text-3xl font-black">
                    <a href="/">Farmacia</a>
                </h1>
                <div class="flex gap-2 items-center">
                    {{-- <a class="font-bold uppercase text-gray-600 text-sm" href="/crear-cuenta">Usuarios</a> --}}
                    <a class="font-bold uppercase text-gray-600 text-sm" href="{{ route('roles.index') }}">Roles</a>
                    <a class="font-bold uppercase text-gray-600 text-sm" href="{{ route('categorias.index') }}">Categorias</a>
                    <a class="font-bold uppercase text-gray-600 text-sm" href="{{ route('sucursales.index') }}">Sucursales</a>
                </div>

            </div>
        </header>
