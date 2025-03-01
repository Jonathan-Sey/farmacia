document.addEventListener('DOMContentLoaded', function () {
    function obtenerUsuario() {
        const cantidad = document.getElementById('notificacion');

        axios.get('/solicitudes/cantidad', {
            headers: {
                'Authorization': `Bearer ${localStorage.getItem('token')}`
            }
        })
        .then(response => {
            const totalCantidad = response.data.cantidad;
            console.log("Cantidad de solicitudes:", totalCantidad);

            if (cantidad) {
                if (totalCantidad == 0) {
                    cantidad.insertAdjacentHTML('beforeend', `
                        <div class="absolute inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-green-500 border-2 border-white rounded-full -top-2 -right-2">
                            ${totalCantidad}
                        </div>
                    `);
                }else{
                    cantidad.insertAdjacentHTML('beforeend', `
                        <div class="absolute inline-flex items-center justify-center w-6 h-6 text-xs font-bold text-white bg-red-500 border-2 border-white rounded-full -top-2 -right-2">
                            ${totalCantidad}
                        </div>
                    `);
                }
               
            }
        })
        .catch(error => {
            console.error("Error:", error);
        });
    }

    obtenerUsuario();
});
