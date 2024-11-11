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

        cursoCard.querySelector('.btn-subir-actividad').addEventListener('click', () => {
            const subirActividad = cursoCard.querySelector(`#actividad-${cursoId}`);
            if (subirActividad) {
                ocultarOtrasCards(); // Oculta las cards de los demás cursos
                mostrarSubSeccion(subirActividad); // Muestra la sub-sección de subir trabajo
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

async function checkSiEstaLogueado() {
    try {
        const response = await fetch('Modulos/validarLogin.php');  // Hacemos la petición al backend
        const data = await response.json();

        if (data.status === 'success') {
            // El usuario está logueado, devolvemos true
            return true;
        } else {
            // El usuario no está logueado
            return false;
        }
    } catch (error) {
        console.error("Error al verificar el estado de sesión: ", error);
        return false;  // Si ocurre un error, asumimos que no está logueado
    }
}

async function cargarMisCursos() {
    const estaLogueado = await checkSiEstaLogueado();  // Verificamos si está logueado

    if (!estaLogueado) {
        console.log("Usuario no logueado, no se cargan los cursos.");
        return;  // No hacemos la llamada AJAX si no está logueado
    }
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
async function renderizarCursos(cursos) {
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
                <button class="btn btn-success btn-subir-actividad">Actividades</button>
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
                
                <div class="sub-seccion d-none" id="actividad-${curso.id}">
                    <hr>
                    <h4>Actividades del Curso ${curso.nombre}</h4>
                    <div id="contenedor-actividades-${curso.id}"></div> <!-- Aquí se cargarán las actividades -->
                    <button class="btn btn-secondary btn-volver">Volver</button>
                    <hr>
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

        // Cargar actividades para cada curso
        cargarActividades(curso.id);
    });

    manejarSubSecciones();
}

// Función para cargar las actividades de un curso específico
async function cargarActividades(cursoId) {
    const contenedorActividades = document.getElementById(`contenedor-actividades-${cursoId}`);
    try {
        const response = await fetch(`Modulos/obtenerActividades.php?cursoId=${cursoId}`);
        const actividades = await response.json();

        if (actividades.status === 'success' && actividades.data.length > 0) {
            renderizarActividades(cursoId, actividades.data, contenedorActividades);
        } else {
            contenedorActividades.innerHTML = '<p>No hay actividades disponibles.</p>';
        }
    } catch (error) {
        contenedorActividades.innerHTML = '<p>Error al cargar las actividades. Intenta nuevamente.</p>';
    }
}

// Función para renderizar las actividades en el DOM
function renderizarActividades(cursoId, actividades, contenedor) {
    const header = document.createElement('h4');
    contenedor.appendChild(header);

    actividades.forEach(actividad => {
        const actividadDiv = document.createElement('div');
        actividadDiv.classList.add('actividad');
        actividadDiv.innerHTML = `
            <h5>Consigna: ${actividad.consigna}</h5>
            <p>Fecha límite: ${actividad.fecha_limite}</p>
            <!-- Formulario de subida de actividad -->
            <form id="form-subir-actividad-${cursoId}-${actividad.id}" data-curso-id="${cursoId}" data-actividad-id="${actividad.id}">
                <input type="file" class="form-control mb-3" required>
                <button type="submit" class="btn btn-primary">Subir Actividad</button>
            </form>
            <div id="alert-subActividad-${actividad.id}"></div>
        `;
        contenedor.appendChild(actividadDiv);
    });
}

// Función para manejar el envío de la actividad por parte del estudiante
async function subirActividad(event) {
    event.preventDefault(); // Evitar el comportamiento predeterminado del formulario

    const form = event.target;
    const cursoId = form.dataset.cursoId;
    const actividadId = form.dataset.actividadId;
    // Verificamos que los datos estén presentes antes de proceder
    if (!cursoId || !actividadId) {
        alert('Curso o actividad no especificados.');
        return;
    }
    const fileInput = form.querySelector('input[type="file"]');
    // Verifica si un archivo fue seleccionado
    if (!fileInput.files.length) {
        alert('Por favor, selecciona un archivo.');
        return;
    }

    const formData = new FormData();
    formData.append('actividad', fileInput.files[0]);
    formData.append('cursoId', cursoId);
    formData.append('actividadId', actividadId);

    try {
        const response = await fetch('Modulos/subirActividad.php', {
            method: 'POST',
            body: formData
        });

        const result = await response.json();

        if (result.status === 'success') {
            mostrarMjeExito(result.message, form); // Usamos el mensaje del JSON
        } else {
            mostrarErrGlobal(result.message, form);
        }
    } catch (error) {
        mostrarErrGlobal('Error al subir la actividad', form);
        console.log(error);
    }
}

// Añadir la lógica para manejar el formulario de subida
document.addEventListener('submit', (event) => {
    if (event.target.matches('form[id^="form-subir-actividad"]')) {
        subirActividad(event);
    }
});

function mostrarErrGlobal(mensaje, form) {
    const actividadId = form.dataset.actividadId; // Obtén el ID de la actividad desde el formulario
    const alertContainer = document.getElementById(`alert-subActividad-${actividadId}`); // Selecciona el contenedor específico
    if (alertContainer) {
        alertContainer.classList.remove('alert-success'); // Elimina clase de éxito si existe
        alertContainer.classList.add('alert', 'alert-danger'); // Añade las clases de error
        alertContainer.innerHTML = mensaje; // Muestra el mensaje de error
        // Elimina el mensaje después de 3 segundos
        setTimeout(() => {
            alertContainer.innerHTML = '';
            alertContainer.classList.remove('alert-danger');
        }, 3000);
    }
}

// Función de éxito
function mostrarMjeExito(mensaje, form) {
    const actividadId = form.dataset.actividadId;
    const alertContainer = document.getElementById(`alert-subActividad-${actividadId}`); // Selecciona el contenedor específico

    if (alertContainer) {
        alertContainer.classList.remove('alert-danger'); // Elimina cualquier clase de error
        alertContainer.classList.add('alert', 'alert-success'); // Añade clases de éxito
        alertContainer.innerHTML = mensaje; // Muestra el mensaje
        // Elimina el mensaje después de 3 segundos
        setTimeout(() => {
            alertContainer.innerHTML = '';
            alertContainer.classList.remove('alert-success');
        }, 3000);
    }
}


function inicio() {

    document.getElementById('seccion-inicio').classList.remove('d-none'); // Mostrar solo la sección de Inicio al cargar la página
    cargarMisCursos();  // Llamar a cargarCursos al inicio
}

document.addEventListener('DOMContentLoaded', inicio);
