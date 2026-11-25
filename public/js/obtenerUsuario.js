document.addEventListener('DOMContentLoaded', function () {

    function obtenerUsuario() {
        const token = localStorage.getItem('jwt_token');
        const crearUsuario = document.getElementById('usuario');

    if (!token) {
        Swal.fire({
            icon: 'error',
            title: '¡Error!',
            text: 'No se encontro un token activo.',
            showConfirmButton: false,
            timer:2000,
            customClass: {
                popup: 'z-50',
            },
        })
        return;
    }

    if (!crearUsuario) {
        return;
    }

    const decodificarToken = jwt_decode(token);
    axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
    const UserId = decodificarToken.id;
    crearUsuario.innerHTML = `
        <input type="hidden" id="idUsuario" name="idUsuario" value="${UserId}">`;

}

obtenerUsuario();
});

//});
