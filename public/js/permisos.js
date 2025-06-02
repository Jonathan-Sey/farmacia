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
    'Requisiciones': '/requisiciones',
    'Traslado': '/traslado',
    'bitacora': '/bitacora',
    'Solicitud': '/solicitud',
    'Reporte_ventas': '/Reporte_ventas',
    'Reporte_ventas_filtro': '/reporte/ventas/filtrar',
<<<<<<< HEAD
    'notificaciones': '/notificaciones',
    'Devoluciones': '/devoluciones',
    'reporte-productos': '/reporte-productos',

=======
>>>>>>> eli
};

function tienePermiso(ruta) {
    const pestanasPermitidas = JSON.parse(localStorage.getItem('pestanas')) || [];
    //console.log('Pestañas permitidas:', pestanasPermitidas);

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
        Swal.fire({
            icon: 'error',
            title: '¡Error!',
            text: 'No tienes permiso para acceder a esta pagina.',
            showConfirmButton: false,
            timer:2000,
            customClass: {
                popup: 'z-50',
            },
            didOpen: () => {
                const scrollBarWidth = window.innerWidth - document.documentElement.clientWidth;
                document.body.style.overflow = 'hidden'; // Desactiva el scroll
                document.body.style.marginRight = `${scrollBarWidth}px`; // Compensa la barra de scroll
                document.querySelector('main').style.display = 'none';//Para ocultar el contenido del main.
                document.getElementById('piepagina').style.display = 'none';//Para ocultar el footer de la pagina.
            },
            willClose: () => {
                document.body.style.overflow = ''; // Restaura el scroll
                document.body.style.marginRight = ''; // Elimina el margen adicional

                const ultimaRuta = localStorage.getItem('ultimaRutaValida');//Obtiene la ultima ruta que se setio y que tiene permiso para acceder
                if (ultimaRuta) {
                    window.location.href = ultimaRuta;//Regresa a la ruta anterior que si tenia permiso
                } else {
                    window.history.back(); //Regresa si no hay ultima ruta
                }

            }
        });
    }
}

// verificacion de permisos
window.addEventListener('load', function () {
    const rutaActual = window.location.pathname; // Obtener la ruta actual

    // Guardar la ruta actual solo si tiene permiso
    if (tienePermiso(rutaActual)) {
        localStorage.setItem('ultimaRutaValida', rutaActual);
    }

    verificarPermiso(rutaActual); // Verificar permisos
});
