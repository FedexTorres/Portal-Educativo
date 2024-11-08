function manejarSubSecciones() {
    const todasLasCards = document.querySelectorAll('.card');  // Obtén todas las cards

    todasLasCards.forEach(cursoCard => {
        const cursoId = cursoCard.dataset.id;
        if (!cursoId) return;

        // Función para ocultar todas las sub-secciones visibles
        function ocultarSubSecciones() {
            todasLasCards.forEach(card => {
                // Oculta todas las sub-secciones de cada card
                card.querySelectorAll('.sub-seccion').forEach(sub => {
                    sub.classList.add('d-none');
                });
            });
        }

        // Función para ocultar las cards de los cursos no seleccionados
        function ocultarOtrasCards() {
            todasLasCards.forEach(card => {
                if (card !== cursoCard) {
                    card.classList.add('d-none'); // Oculta todas las cards que no son la actual
                }
            });
        }

        // Función para mostrar la sub-sección correspondiente
        function mostrarSubSeccion(subSeccion) {
            // Primero oculta todas las sub-secciones visibles
            ocultarSubSecciones();

            // Luego muestra solo la sub-sección que fue seleccionada
            subSeccion.classList.remove('d-none');
        }

        // Event listeners para los botones de sub-secciones
        cursoCard.querySelector('.btn-consultar-asistencia').addEventListener('click', () => {
            const asistencia = cursoCard.querySelector(`#asistencia-${cursoId}`);
            if (asistencia) {
                ocultarOtrasCards(); // Oculta las cards de los demás cursos
                mostrarSubSeccion(asistencia); // Muestra la sub-sección de asistencia
            }
        });

        cursoCard.querySelector('.btn-consultar-calificacion').addEventListener('click', () => {
            const calificacion = cursoCard.querySelector(`#calificacion-${cursoId}`);
            if (calificacion) {
                ocultarOtrasCards(); // Oculta las cards de los demás cursos
                mostrarSubSeccion(calificacion); // Muestra la sub-sección de calificación
            }
        });

        cursoCard.querySelector('.btn-subir-trabajo').addEventListener('click', () => {
            const subirTrabajo = cursoCard.querySelector(`#subir-trabajo-${cursoId}`);
            if (subirTrabajo) {
                ocultarOtrasCards(); // Oculta las cards de los demás cursos
                mostrarSubSeccion(subirTrabajo); // Muestra la sub-sección de subir trabajo
            }
        });

        cursoCard.querySelector('.btn-filtrar-asistencia').addEventListener('click', () => {
            const filtrarAsistencia = cursoCard.querySelector(`#filtrar-asistencia-${cursoId}`);
            if (filtrarAsistencia) {
                ocultarOtrasCards(); // Oculta las cards de los demás cursos
                mostrarSubSeccion(filtrarAsistencia); // Muestra la sub-sección de filtrar asistencia
            }
        });

        // Manejar botón "volver" para restaurar la vista original de la card
        cursoCard.querySelectorAll('.btn-volver').forEach(boton => {
            boton.addEventListener('click', () => {
                // Muestra todas las cards nuevamente
                todasLasCards.forEach(card => card.classList.remove('d-none'));

                // Oculta todas las sub-secciones de la card actual
                cursoCard.querySelectorAll('.sub-seccion').forEach(sub => {
                    sub.classList.add('d-none');
                });
            });
        });
    });
}

async function cargarMisCursos() {
    try {
        const response = await fetch('Modulos/cargarMisCursos.php');  // Llamada a cargarMisCursos.php
        const cursos = await response.json();

        if (cursos.status === 'success') {
            renderizarCursos(cursos.data);// Función para mostrar los cursos en el DOM
        } else if (cursos.status === 'info') {
            mostrarMensajeInfo(cursos.message); // Si no hay cursos
        } else {
            mostrarErrGlobal(cursos.message); // Si hubo un error en el backend
        }
    } catch (error) {
        mostrarErrGlobal('No se pudo cargar la lista de cursos. Intenta nuevamente.');
    }
}


function renderizarCursos(cursos) {
    const seccionMisCursos = document.getElementById('seccion-mis-cursos');

    // Limpiar cursos anteriores si es necesario
    seccionMisCursos.innerHTML = `
        <h1 class="my-4 titulo">Mis Cursos</h1>
        <hr>
        <h3 class="my-4 titulo">Lista de cursos en los que estás inscripto</h3>
    `;

    cursos.forEach(curso => {
        const cursoCard = document.createElement('div');
        cursoCard.classList.add('card', 'mb-4');
        cursoCard.dataset.id = curso.id; // Asignar ID al dataset de la card

        cursoCard.innerHTML = `
            <div class="card-body">
                <h5 class="card-title">${curso.nombre}</h5>
                <h6 class="card-subtitle mb-2 text-muted">Fecha: ${curso.fecha_inicio} - ${curso.fecha_fin}</h6>
                <p class="card-text">${curso.descripcion}</p>
                
                <!-- Botones de opciones para cada curso -->
                <button class="btn btn-primary btn-consultar-asistencia">Consultar Asistencia</button>
                <button class="btn btn-secondary btn-consultar-calificacion">Consultar Calificación</button>
                <button class="btn btn-success btn-subir-trabajo">Subir Trabajo Práctico</button>
                <button class="btn btn-warning btn-filtrar-asistencia">Filtrar Asistencia</button>

                <!-- Sub-secciones dinámicas dentro de la card de cada curso -->
                <div class="sub-seccion d-none" id="asistencia-${curso.id}">
                    <h4>Asistencia del Curso</h4>
                    <ul>
                        <li>Clase 1: Presente</li>
                        <li>Clase 2: Ausente</li>
                        <li>Clase 3: Presente</li>
                    </ul>
                    <button class="btn btn-secondary btn-volver">Volver</button>
                </div>
                
                <div class="sub-seccion d-none" id="calificacion-${curso.id}">
                    <h4>Calificaciones del Curso</h4>
                    <ul>
                        <li>Examen Parcial 1: 8</li>
                        <li>Examen Parcial 2: 7</li>
                        <li>Examen Final: 9</li>
                    </ul>
                    <button class="btn btn-secondary btn-volver">Volver</button>
                </div>
                
                <div class="sub-seccion d-none" id="subir-trabajo-${curso.id}">
                    <h4>Subir Trabajo Práctico</h4>
                    <form id="form-subir-trabajo-${curso.id}">
                        <input type="file" class="form-control mb-3" required>
                        <button type="submit" class="btn btn-primary">Subir Trabajo</button>
                    </form>
                    <button class="btn btn-secondary btn-volver">Volver</button>
                </div>
                
                <div class="sub-seccion d-none" id="filtrar-asistencia-${curso.id}">
                    <h4>Filtrar Asistencia</h4>
                    <select class="form-select mb-3">
                        <option value="todos">Todos</option>
                        <option value="presente">Presentes</option>
                        <option value="ausente">Ausentes</option>
                    </select>
                    <button class="btn btn-primary">Filtrar</button>
                    <button class="btn btn-secondary btn-volver">Volver</button>
                </div>
            </div>
        `;

        seccionMisCursos.appendChild(cursoCard);
    });

    manejarSubSecciones();
}

// Funciones para mostrar mensajes de error o información
function mostrarErrGlobal(mensaje) {
    alert(`Error: ${mensaje}`);
}

function mostrarMensajeInfo(mensaje) {
    alert(`Info: ${mensaje}`);
}

function inicio() {

    document.getElementById('seccion-inicio').classList.remove('d-none'); // Mostrar solo la sección de Inicio al cargar la página
    cargarMisCursos();  // Llamar a cargarCursos al inicio
}

document.addEventListener('DOMContentLoaded', inicio);
