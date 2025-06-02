document.addEventListener('DOMContentLoaded', function () {
    const token = localStorage.getItem('jwt_token');
    const pesta = localStorage.getItem('pestanas');

    if (!token) {
        window.location.href = '/';
    } else {
        const decodificarToken = jwt_decode(token);
        const limiteDeTiempo = Date.now() / 1000;

        if (decodificarToken.exp < limiteDeTiempo) {
            Swal.fire({
                icon: 'error',
                title: '¡Error!',
                text: 'Tu sesión a expirado.',
                showConfirmButton: false,
                timer:2000,
                customClass: {
                    popup: 'z-50',
                },
                didOpen: () => {
                    const scrollBarWidth = window.innerWidth - document.documentElement.clientWidth;
                    document.body.style.overflow = 'hidden'; // Desactiva el scroll
                    document.body.style.marginRight = `${scrollBarWidth}px`; // Compensa la barra de scroll
                },
                willClose: () => {
                    document.body.style.overflow = ''; // Restaura el scroll
                    document.body.style.marginRight = ''; // Elimina el margen adicional
                    localStorage.clear();//Elimina lo que esta en el local storage.
                    window.location.href = '/';
                }
            });
        } else {
            //Axios incluira el token en todas las peticiones
            axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

            // Obtener las pestañas permitidas del usuario
            const pestanasPermitidas = decodificarToken.pestanas || [];
            // Almacenar las pestañas permitidas en localStorage
            localStorage.setItem('pestanas', JSON.stringify(pestanasPermitidas));

            const userName = decodificarToken.name;
            //const userTabs = decodificarToken.pestanas;

            document.getElementById('user-name').innerText = userName;
            // Configurar las pestañas del menú
            // const userName = decodificarToken.name;
            // document.getElementById('user-name').innerText = userName;
        }
    }
});



