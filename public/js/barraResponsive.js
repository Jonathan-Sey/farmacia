document.addEventListener('DOMContentLoaded', function () {
    const menuBtn = document.getElementById('menu-btn');
    const menu = document.getElementById('menu');

    
    if (menuBtn && menu) {
  
        menuBtn.addEventListener('click', function() {
            console.log('Botón de menú clickeado'); 
            menu.classList.toggle('hidden'); 
            
        });
    


    const token = localStorage.getItem('jwt_token');
    console.log(token);
    if (token) {
        const decodedToken = jwt_decode(token);
        const pestanas = decodedToken.pestanas || [];
        
        setupNavigation(pestanas);
    }
}
    // Función para configurar la navegación dinámica según las pestañas
    function setupNavigation(pestanas) {
        const navItems = document.querySelectorAll('[data-pestana]');
        navItems.forEach(item => item.style.display = 'none'); // Ocultar todas las pestañas

        pestanas.forEach(pestana => {
            const navItem = document.querySelector(`[data-pestana="${pestana}"]`);
            if (navItem) {
                navItem.style.display = 'block'; // Mostrar las pestañas correspondientes
            }
        });
    }
});

