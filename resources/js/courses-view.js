document.addEventListener('DOMContentLoaded', (event) => {
    // Obtén todas las secciones
    const sections = document.querySelectorAll('#seccion-list');

    sections.forEach((section) => {
        section.addEventListener('click', (event) => {
            // Encuentra el menú de lecciones para esta sección
            const lessonMenu = section.nextElementSibling;

            // Alternar la visibilidad del menú de lecciones
            lessonMenu.style.display = (lessonMenu.style.display === 'none') ? 'block' : 'none';
        });
    });
});
