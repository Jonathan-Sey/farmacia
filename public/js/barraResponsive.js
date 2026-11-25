document.addEventListener('DOMContentLoaded', function () {
    const menuBtn = document.getElementById('menu-btn');
    const menu = document.getElementById('menu');

    if (!menuBtn || !menu) {
        return;
    }

    menuBtn.addEventListener('click', function () {
        const isExpanded = menuBtn.getAttribute('aria-expanded') === 'true';
        menu.classList.toggle('hidden', isExpanded);
        menuBtn.setAttribute('aria-expanded', String(!isExpanded));
    });

    window.addEventListener('resize', function () {
        if (window.innerWidth >= 768) {
            menu.classList.add('hidden');
            menuBtn.setAttribute('aria-expanded', 'false');
        }
    });

    const token = localStorage.getItem('jwt_token');
    if (!token) {
        return;
    }

    try {
        const decodedToken = jwt_decode(token);
        setupNavigation(Array.isArray(decodedToken.pestanas) ? decodedToken.pestanas : []);
    } catch (error) {
        console.error('No se pudieron cargar las opciones del menú móvil.', error);
    }

    function setupNavigation(pestanas) {
        const allowedTabs = new Set(pestanas);
        document.querySelectorAll('[data-pestanal]').forEach(function (item) {
            item.style.display = allowedTabs.has(item.dataset.pestanal) ? 'block' : 'none';
        });
    }
});



