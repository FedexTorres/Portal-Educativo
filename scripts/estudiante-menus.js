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
                    document.getElementById('seccion-mis-cursos').classList.remove('d-none');
                    break;
                case 'mensajes-btn':
                    document.getElementById('seccion-mensajes').classList.remove('d-none');
                    break;
                case 'perfil-btn':
                    document.getElementById('seccion-perfil').classList.remove('d-none');
                    break;
                default:
                    console.log('Botón no reconocido');
                    break;
            }
        });
    });
}

function inicio() {
    const sections = document.querySelectorAll('section');
    document.getElementById('seccion-inicio').classList.remove('d-none');// Mostrar solo la sección de Inicio al cargar la página
    mostrarMenus(sections);
}

window.onload = inicio;
