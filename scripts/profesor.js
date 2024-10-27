document.addEventListener("DOMContentLoaded", function () {
    const btnEspacio = document.getElementById('btn-espacio');
    const btnCalendario = document.getElementById('btn-calendario');
    const btnCursos = document.getElementById('btn-cursos');
    const btnNuevaConversacion = document.getElementById('btn-nueva-conversacion');
    const btnIniciarDebate = document.getElementById('btn-iniciar-debate');
    const btnRecibidas = document.getElementById('btn-recibidas');
    const btnEnviadas = document.getElementById('btn-enviadas');
    const btnPendientes = document.getElementById('btn-pendientes');

    const espacioDiv = document.getElementById('mi-espacio');
    const calendarDiv = document.getElementById('calendar');
    const cursosDiv = document.getElementById('filtros-cursos');
    const nuevaConversacionDiv = document.getElementById('nueva-conversacion');
    const iniciarDebateDiv = document.getElementById('iniciar-debate');
    const recibidasDiv = document.getElementById('recibidas');
    const enviadasDiv = document.getElementById('enviadas');
    const pendientesDiv = document.getElementById('pendientes');

    // Inicializar el calendario
    let calendarInitialized = false;
    let calendar;

    function mostrarSeccion(seccion) {
        const secciones = [espacioDiv, calendarDiv, cursosDiv, nuevaConversacionDiv, iniciarDebateDiv, recibidasDiv, enviadasDiv, pendientesDiv];

        // Ocultar todas las secciones
        secciones.forEach((s) => s.style.display = 'none');

        // Mostrar la sección seleccionada
        seccion.style.display = 'block';

        // Inicializar el calendario solo si se muestra por primera vez
        if (seccion === calendarDiv && !calendarInitialized) {
            calendar = new FullCalendar.Calendar(calendarDiv, {
                initialView: 'dayGridMonth'
            });
            calendar.render();
            calendarInitialized = true;
        }
    }

    // Función para cargar cursos
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

    // Eventos para los botones
    btnEspacio.addEventListener('click', function () {
        mostrarSeccion(espacioDiv);
    });

    btnCalendario.addEventListener('click', function () {
        mostrarSeccion(calendarDiv);
    });

    btnCursos.addEventListener('click', function () {
        mostrarSeccion(cursosDiv);
        cargarCursos();
    });

    btnNuevaConversacion.addEventListener('click', function () {
        mostrarSeccion(nuevaConversacionDiv);
        inicializarTinyMCE(); // Inicializar TinyMCE aquí
    });

    btnIniciarDebate.addEventListener('click', function () {
        mostrarSeccion(iniciarDebateDiv);
        // Aquí puedes agregar la lógica adicional que necesites para "Iniciar Debate"
    });

    btnRecibidas.addEventListener('click', function () {
        mostrarSeccion(recibidasDiv);
    });

    btnEnviadas.addEventListener('click', function () {
        mostrarSeccion(enviadasDiv);
    });

    btnPendientes.addEventListener('click', function () {
        mostrarSeccion(pendientesDiv);
    });

    // Inicializar mostrando "Mi Espacio"
    mostrarSeccion(espacioDiv);
});

function inicializarTinyMCE() {
    // Lógica para inicializar TinyMCE
}
