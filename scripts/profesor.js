function mostrarMenus(sections) {
    // Escuchar clics en los botones del menú
    document.querySelectorAll('.btn-menu').forEach(boton => {
        boton.addEventListener('click', function () {
            const botonId = this.id;   //segun el boton escuchado, se pasa el id a la variable botonId
            // Ocultar todas las secciones primero
            sections.forEach(section => section.classList.add('d-none'));
            // Mostrar la sección correspondiente según el botón clickeado
            switch (botonId) {
                case 'btn-inicio':
                    document.getElementById('seccion-inicio').classList.remove('d-none');
                    break;
                case 'btn-actividades':
                    document.getElementById('seccion-actividades').classList.remove('d-none');
                    break;

                case 'btn-asistencia':
                    document.getElementById('seccion-asistencias').classList.remove('d-none');
                    break;
                case 'btn-calificaciones':
                    document.getElementById('seccion-calificaciones').classList.remove('d-none');
                    break;
                case 'btn-mensaje':
                    document.getElementById('seccion-mensajes').classList.remove('d-none');
                    break;
                case 'btn-perfil':
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
    mostrarMenus(sections);

}

document.addEventListener('DOMContentLoaded', inicio);