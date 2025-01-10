import jwt_decode  from "https://cdn.jsdelivr.net/npm/jwt-decode@3.1.2/build/jwt-decode.esm.js";
document.addEventListener('DOMContentLoaded', function() {
   
    const token = localStorage.getItem('jwt_token'); // Obtener el token del localStorage
    // Asegúrate de que devuelva un valor válido
    if (token) {
        // Decodificar el token para obtener el payload
        const decodedToken = jwt_decode(token);

        // Obtener el rol del payload decodificado
        const role = decodedToken.rol;
        console.log(decodedToken); // Asegúrate de ver el rol aquí
        console.log(decodedToken.rol); 

        
        // Llamamos a la función para configurar la navegación según el rol
        setupNavigation(role);
    }
   

});

function setupNavigation(role) {
    const adminNavElements = document.querySelectorAll('#admin-nav');
    const userNavElements = document.querySelectorAll('#user-nav');

    if (adminNavElements.length > 0 && userNavElements.length > 0) {
        if (role === 'admin') {
            adminNavElements.forEach(el => el.style.display = 'block');
            userNavElements.forEach(el => el.style.display = 'none');
        } else if (role === 'cajero') {
            adminNavElements.forEach(el => el.style.display = 'none');
            userNavElements.forEach(el => el.style.display = 'block');
        } else {
            adminNavElements.forEach(el => el.style.display = 'none');
            userNavElements.forEach(el => el.style.display = 'none');
        }
    }
}

