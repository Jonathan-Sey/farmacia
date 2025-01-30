document.addEventListener('DOMContentLoaded', function () {
    const token = localStorage.getItem('jwt_token');

    if (!token) {
        window.location.href = '/';
    } else {
        const decodificarToken = jwt_decode(token);
        const limiteDeTiempo = Date.now() / 1000;
        //validamos el tiempo
        if (decodificarToken.exp < limiteDeTiempo) {
            alert('Tu sesión ha expirado. Por favor, inicia sesión nuevamente.');
            //removemos
            localStorage.removeItem('jwt_token');
            window.location.href = '/';
        } else {
            //Axios incluira el token en todas las peticiones
            axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

            // Configurar las pestañas del menú
            const pestanas = decodificarToken.pestanas || [];

            const userName = decodificarToken.name;
            const userTabs = decodificarToken.pestanas;

            document.getElementById('user-name').innerText = userName;   
        }
    }
});



