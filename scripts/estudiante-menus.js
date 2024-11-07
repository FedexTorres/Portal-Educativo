function mostrarMenus(sections) {
    document.querySelectorAll('.btn-menu').forEach(boton => {
        boton.addEventListener('click', function () {
            const botonId = this.id;

            // Ocultar todas las secciones primero
            sections.forEach(section => section.classList.add('d-none'));

            // Mostrar la sección correspondiente según el botón clickeado
            switch (botonId) {
                case 'inicio-btn':
                    document.getElementById('seccion-inicio').classList.remove('d-none');
                    break;
                case 'mis-cursos-btn':
                    document.getElementById('seccion-inicio').classList.add('d-none');
                    document.getElementById('seccion-mis-cursos').classList.remove('d-none');
                    break;
                case 'mensajes-btn':
                    document.getElementById('seccion-inicio').classList.add('d-none');
                    document.getElementById('seccion-mensajes').classList.remove('d-none');
                    break;
                case 'perfil-btn':
                    document.getElementById('seccion-inicio').classList.add('d-none');
                    document.getElementById('seccion-perfil').classList.remove('d-none');
                    //cargarPerfil(); // Llamada para cargar datos en el perfil cuando se muestra esta sección
                    break;
                default:
                    console.log('Botón no reconocido');
                    break;
            }
        });
    });
}

function inicio() {
    // Seleccionar todas las secciones que queremos alternar
    const sections = document.querySelectorAll('.seccion');

    // Ocultar todas las secciones inicialmente
    sections.forEach(section => section.classList.add('d-none'));

    // Mostrar solo la sección de inicio al cargar la página
    document.getElementById('seccion-inicio').classList.remove('d-none');

    // Llamar a la función de mostrar menú para inicializar los eventos
    mostrarMenus(sections);
}

document.addEventListener('DOMContentLoaded', inicio);

