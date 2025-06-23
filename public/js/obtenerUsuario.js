document.addEventListener('DOMContentLoaded', function () { 
    
    function obtenerUsuario() {
        const token = localStorage.getItem('jwt_token');
        const crearUsuario = document.getElementById('usuario');
        const user = document.getElementById("idUsuario");

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

        const decodificarToken = jwt_decode(token);
        const UserId = decodificarToken.id;

        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;

        //console.log(UserId)
        
        if (crearUsuario) {
            crearUsuario.innerHTML = ` <input type="text" class="form-control" id="idUsuario" name="idUsuario" value="${UserId}" hidden>`;
        }

        user.style.display = "none";

    }

    obtenerUsuario();

});
