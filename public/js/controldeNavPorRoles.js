import jwt_decode from "https://cdn.jsdelivr.net/npm/jwt-decode@3.1.2/build/jwt-decode.esm.js";

document.addEventListener('DOMContentLoaded', function () {
    const token = localStorage.getItem('jwt_token');

    if (token) {

        const decodedToken = jwt_decode(token);
      console.log(decodedToken);
        // Obtener las pestañas desde el payload del token
        const pestanas = decodedToken.pestanas || [];
       
        // Configurar la navegación dinámica
        setupNavigation(pestanas);
    }
});

function setupNavigation(pestanas) {
    // Ocultar todas las pestañas primero
    const navItems = document.querySelectorAll('[data-pestana]');
    navItems.forEach(item => item.style.display = 'none');

    // Mostrar solo las pestañas permitidas
    pestanas.forEach(pestana => {
        const navItem = document.querySelector(`[data-pestana="${pestana}"]`);
        if (navItem) {
            navItem.style.display = 'block';
        }
    });
    
}
