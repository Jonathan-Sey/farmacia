document.addEventListener('DOMContentLoaded', function () {function obtenerUsuario(){
    const token = localStorage.getItem('jwt_token');
    const crearUsuario = document.getElementById('usuario');

    if (!token) {
        alert("No se encontró un token activo");
        return;
    }

    const decodificarToken = jwt_decode(token);


        axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;


        const UserId = decodificarToken.id;

      console.log(UserId)
    crearUsuario.innerHTML = ` <input type="text" class="form-control" id="idUsuario" name="idUsuario" value="${UserId}" hidden>`; 


}

obtenerUsuario();})
