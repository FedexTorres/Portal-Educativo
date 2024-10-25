function mostrarMenus(sections) {
    // Escuchar clics en los botones del menú
    document.querySelectorAll('.btn-menu').forEach(boton => {
        boton.addEventListener('click', function () {
            const botonId = this.id;   //segun el boton escuchado, se pasa el id a la variable botonId

            // Ocultar todas las secciones primero
            sections.forEach(section => section.classList.add('d-none'));

            // Inicializar el calendario
            let calendarInitialized = false;
            let calendar;

            // Mostrar la sección correspondiente según el botón clickeado
            switch (botonId) {
                case 'btn-espacio':
                    document.getElementById('mi-espacio').classList.remove('d-none');
                    break;
                case 'btn-calendario':
                    
                    document.getElementById('calendar').classList.remove('d-none');
                    if (calendarInitialized == false) {
                        calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
                            initialView: 'dayGridMonth'
                        });
                        calendar.render();
                        calendarInitialized = true;
                    }

                    break;               
                case 'btn-cursos':
                    document.getElementById('filtros-cursos').classList.remove('d-none');
                    cargarCursos();
                    break;
                case 'mensaje-btn':
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

function cargarCursos() {
    const tablaCursos = document.getElementById('tabla-cursos');
    tablaCursos.innerHTML = ''; // Limpiar tabla antes de agregar nuevos cursos

    const cursos = [
        { nombre: "Matemáticas", nivel: "Primario", tipo: "Obligatorio" },
        { nombre: "Lengua", nivel: "Secundario", tipo: "Optativo" },
        { nombre: "Física", nivel: "Universitario", tipo: "Obligatorio" },
        { nombre: "Programación", nivel: "Secundario", tipo: "Optativo" },
        { nombre: "Química", nivel: "Universitario", tipo: "Obligatorio" },
        { nombre: "Historia", nivel: "Primario", tipo: "Obligatorio" }
    ];

    cursos.forEach(function (curso) {
        const fila = `
            <tr>
              <td>${curso.nombre}</td>
              <td>${curso.nivel}</td>
              <td>${curso.tipo}</td>
              <td>
                <button class="btn btn-primary btn-sm">Editar</button>
                <button class="btn btn-danger btn-sm">Eliminar</button>
              </td>
            </tr>
        `;
        tablaCursos.innerHTML += fila;
    });
}





function inicio() {
    const sections = document.querySelectorAll('section');
    mostrarMenus(sections);

}

window.onload = inicio;