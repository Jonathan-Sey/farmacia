// Rutas Mapeadas
const rutasMapeadas = {
    'Dashboard': '/dashboard',
    'Rol': '/roles',
    'Categorias': '/categorias',
    'Proveedores': '/proveedores',
    'Sucursales': '/sucursales',
    'Productos': '/productos',
    'Almacenes': '/almacenes',
    'Compras': '/compras',
    'Ventas': '/ventas',
    'Personas': '/personas',
    'Medicos': '/medicos',
    'Consultas': '/consultas',
    'Usuarios': '/usuarios',
    'Inventario': '/inventario',
    'Traslados': '/traslados'
};

function tienePermiso(ruta) {
    const pestanasPermitidas = JSON.parse(localStorage.getItem('pestanas')) || [];
    console.log('Pestañas permitidas:', pestanasPermitidas);

    // Filtro y mapear de pestañas en la url
    const rutasPermitidas = pestanasPermitidas
        .map(p => rutasMapeadas[p]) // Mapeo a url
        .filter(r => r !== undefined); // Filtrar rutas no mapeadas


    const rutaNormalizada = ruta.toLowerCase();//a minusculas
    const rutasPermitidasNormalizadas = rutasPermitidas.map(r => r.toLowerCase());

    // primera forma
    //return rutasPermitidasNormalizadas.includes(rutaNormalizada);
    // segunda forma
    return rutasPermitidasNormalizadas.some(rutaPermitida =>
        rutaNormalizada.startsWith(rutaPermitida)
    );
}

function verificarPermiso(ruta) {
    if (!tienePermiso(ruta)) {
        alert('No tienes permiso para acceder a esta vista.');
        window.location.href = '/';
    }
}

// verificacion de permisos
window.addEventListener('load', function () {
    const rutaActual = window.location.pathname; // Obtener la ruta actual
    verificarPermiso(rutaActual); // Verificar permisos
});
