function updateSelectedTabs() {
    const selectedTabs = document.getElementById('pestanas').selectedOptions;
    const newTab = document.getElementById('nueva_pestana').value;  // Obtener el valor de la nueva pestaña seleccionada
    const selectedTabsList = document.getElementById('selected-tabs-list');
    
    // Limpiar la lista
    selectedTabsList.innerHTML = '';

    // Crear una lista de pestañas seleccionadas, incluyendo la nueva pestaña
    let tabsArray = Array.from(selectedTabs).map(option => option.text);

    // Si hay una nueva pestaña seleccionada, agregarla al principio
    if (newTab) {
        const newTabText = document.querySelector(`#nueva_pestana option[value='${newTab}']`).text;
        tabsArray.unshift(newTabText);  // Agregar al principio
    }

    // Añadir las pestañas a la lista
    tabsArray.forEach(tab => {
        const listItem = document.createElement('li');
        listItem.textContent = tab;
        selectedTabsList.appendChild(listItem);
    });
}

// Llamar a la función al cargar la página en caso de que haya selecciones previas
window.onload = updateSelectedTabs;

