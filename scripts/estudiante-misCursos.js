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
            consultarAsistencia(cursoId, filtro = 'todos'); // Llama a la función pasando el ID del curso
            if (asistencia) {
                ocultarOtrasCards(); // Oculta las cards de los demás cursos
                mostrarSubSeccion(asistencia); // Muestra la sub-sección de asistencia
            }
        });

        cursoCard.querySelector('.btn-consultar-calificacion').addEventListener('click', () => {
            const calificacion = cursoCard.querySelector(`#calificacion-${cursoId}`);
            consultarCalificacion(cursoId);
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

        cursoCard.querySelector('.btn-consultar-material').addEventListener('click', () => {
            const verMaterial = cursoCard.querySelector(`#material-${cursoId}`);
            cargarMaterialEstudio(cursoId);
            if (verMaterial) {
                ocultarOtrasCards(); // Oculta las cards de los demás cursos
                mostrarSubSeccion(verMaterial); // Muestra la sub-sección de subir trabajo
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
                <div class="opciones">
                <button class="btn btn-danger btn-consultar-calificacion">Consultar Calificación</button>
                <button class="btn btn-success btn-subir-actividad">Actividades</button>
                <button class="btn btn-primary btn-consultar-asistencia">Consultar Asistencia</button>
                <button class="btn btn-info btn-consultar-material">Material de Estudio</button>
                </div>
                <!-- Sub-secciones dinámicas dentro de la card de cada curso -->
                                
                <div class="sub-seccion d-none" id="calificacion-${curso.id}">
                <hr>
                    <h3>Calificaciones del Curso: ${curso.nombre}</h3>
                        <div id="contenedor-calificaciones-${curso.id}"></div> <!-- Aquí se cargarán las calificaciones -->
                        <div id="errorCalificacion-${curso.id}" class="d-none"></div>
                        <button class="btn btn-secondary btn-volver">Volver</button>
                </div>
                
                <div class="sub-seccion d-none" id="actividad-${curso.id}">
                    <br>
                        <h3>Actividades del Curso: ${curso.nombre}</h3>
                        <hr>
                        <div id="contenedor-actividades-${curso.id}"></div> <!-- Aquí se cargarán las actividades -->
                        <div id="errorActividad-${curso.id}" class="d-none"></div>
                        <button class="btn btn-secondary btn-volver">Volver</button>
                    <hr>
                </div>
             
                <div class="sub-seccion d-none" id="asistencia-${curso.id}">
                    <br>
                        <div id="contenedor-asistencias-${curso.id}"></div>
                        <h3>Asistencias del Curso: ${curso.nombre}</h3>
                        <div id="errorAsistencia-${curso.id}" class="d-none"></div>
                        <button class="btn btn-secondary btn-volver">Volver</button>
                </div>              

                <div class="sub-seccion d-none" id="material-${curso.id}">
                    <hr>
                        <h3>Material de Estudio del Curso: ${curso.nombre}</h3>
                        <div id="contenedor-material-${curso.id}"></div> <!-- Aquí se cargará el material de estudio -->
                        <div id="errorMaterial-${curso.id}" class="d-none"></div>
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

// Función para consultar la asistencia de un curso
async function consultarAsistencia(cursoId, filtro = 'todos') {
    const asistenciaContenedor = document.getElementById(`asistencia-${cursoId}`);
    const errorDiv = document.getElementById(`errorAsistencia-${cursoId}`);


    if (!asistenciaContenedor) {
        console.error(`No se encontró el contenedor para la asistencia del curso con ID ${cursoId}`);
        return;
    }

    try {
        const url = `Modulos/consultarAsistencia.php?cursoId=${cursoId}&filtro=${filtro}`;
        const response = await fetch(url);
        const result = await response.json();

        if (result.status === 'success') {
            renderizarAsistencia(result.data, asistenciaContenedor, cursoId);
        } else if (result.status === 'info') {

            errorDiv.textContent = result.message; // Asigna el mensaje de error
            errorDiv.classList.remove('d-none'); // Muestra el div eliminando la clase d-none
            errorDiv.classList.add('alert', 'alert-danger'); // Añade las clases de alerta
            const computedStyles = getComputedStyle(errorDiv);
        } else if (result.status === 'error') {

            errorDiv.textContent = result.message; // Asigna el mensaje de error
            errorDiv.classList.remove('d-none'); // Muestra el div eliminando la clase d-none
            errorDiv.classList.add('alert', 'alert-danger'); // Añade las clases de alerta
            const computedStyles = getComputedStyle(errorDiv);
        }
    } catch (error) {
        console.error('Error al consultar la asistencia:', error);

    }
}

// Función principal para renderizar la asistencia
function renderizarAsistencia(asistencias, contenedor, cursoId) {
    const calificacionDiv = document.createElement('div');
    calificacionDiv.classList.add('calificacion');
    // Crear la estructura del filtro y la tabla
    contenedor.innerHTML = `
        <div>
        <hr>
            <h4>Filtrar Asistencia</h4>
            <select id="filtro-asistencia-${cursoId}" class="form-select mb-3">
                <option value="todos">Todos</option>
                <option value="presente">Presentes</option>
                <option value="ausente">Ausentes</option>
            </select>
            <button class="btn btn-primary btn-filtrar" data-id="${cursoId}">Filtrar</button>
            <hr>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Fecha</th>
                    <th>Estado</th>
                    <th>Estudiante</th>
                </tr>
            </thead>
            <tbody id="tabla-asistencias-${cursoId}"></tbody>
        </table>
        <button class="btn btn-secondary btn-volver">Volver</button>
    `;

    // Seleccionar el cuerpo de la tabla
    const cuerpo = document.getElementById(`tabla-asistencias-${cursoId}`);
    // Llamada inicial para mostrar todos los datos
    renderizarDatos(asistencias, cuerpo);

    // Manejar el evento de filtrado
    const btnFiltrar = contenedor.querySelector(`.btn-filtrar`);
    btnFiltrar.addEventListener('click', () => {
        const filtroSeleccionado = document.getElementById(`filtro-asistencia-${cursoId}`).value;
        // Filtrar los datos según el estado seleccionado
        const datosFiltrados = filtroSeleccionado === 'todos'
            ? asistencias
            : asistencias.filter(asistencia => asistencia.estado.toLowerCase() === filtroSeleccionado.toLowerCase());
        // Renderizar los datos filtrados
        renderizarDatos(datosFiltrados, cuerpo);
    });

    // Manejar el evento del botón "Volver"
    const btnVolver = contenedor.querySelector('.btn-volver');
    btnVolver.addEventListener('click', () => {
        // Muestra todas las cards nuevamente
        const todasLasCards = document.querySelectorAll('.card');
        todasLasCards.forEach(card => card.classList.remove('d-none'));

        // Oculta todas las sub-secciones de la card actual
        const cursoCard = document.querySelector(`.card[data-id="${cursoId}"]`);
        cursoCard.querySelectorAll('.sub-seccion').forEach(sub => {
            sub.classList.add('d-none');
        });
    });
}

// Función para renderizar los datos en la tabla de asistencia
function renderizarDatos(datos, cuerpo) {
    cuerpo.innerHTML = datos
        .map(asistencia => `
            <tr>
                <td>${asistencia.fecha}</td>
                <td>${asistencia.estado}</td>
                <td>${asistencia.estudiante_nombre} ${asistencia.estudiante_apellido}</td>
            </tr>
        `)
        .join('');
}

// Función para consultar las calificaciones de un curso específico
async function consultarCalificacion(cursoId) {
    // Se obtiene el contenedor de calificaciones del curso
    const contenedorCalificaciones = document.getElementById(`contenedor-calificaciones-${cursoId}`);
    contenedorCalificaciones.innerHTML = ""; // Limpia el contenido previo

    if (!contenedorCalificaciones) {
        console.error(`No se encontró el contenedor para calificaciones del curso con ID ${cursoId}`);
        return;
    }

    try {
        const response = await fetch(`Modulos/obtenerCalificacion.php?cursoId=${cursoId}`);
        const result = await response.json();
        const errorDiv = document.getElementById(`errorCalificacion-${cursoId}`);

        if (result.status === 'success' && result.data.length > 0) {
            // Renderizar las calificaciones si hay datos disponibles
            renderizarCalificaciones(cursoId, result.data, contenedorCalificaciones);
        } else if (result.status === 'info') {
            errorDiv.textContent = result.message; // Asigna el mensaje de error
            errorDiv.classList.remove('d-none'); // Muestra el div eliminando la clase d-none
            errorDiv.classList.add('alert', 'alert-danger'); // Añade las clases de alerta
        }
        else if (result.status === 'error') {
            errorDiv.textContent = result.message; // Asigna el mensaje de error
            errorDiv.classList.remove('d-none'); // Muestra el div eliminando la clase d-none
            errorDiv.classList.add('alert', 'alert-danger'); // Añade las clases de alerta
        }
    } catch (error) {
        contenedorCalificaciones.innerHTML = '<p>Error al cargar las Calificaciones. Intenta nuevamente.</p>';
    }
}
function renderizarCalificaciones(cursoId, calificaciones, contenedor) {
    contenedor.innerHTML = ''; // Limpiamos el contenedor para evitar duplicados

    const calificacionDiv = document.createElement('div');
    calificacionDiv.classList.add('calificacion');
    calificacionDiv.innerHTML = `
        <div class="table-responsive">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Actividad</th>
                        <th>Nro de Entrega</th>
                        <th>Calificación</th>
                    </tr>
                </thead>
                <tbody id="tabla-calificaciones-${cursoId}">
                    ${calificaciones.map(calificacion => `
                        <tr>
                            <td>${calificacion.nombre_actividad}</td>
                            <td>${calificacion.numero_entrega}</td>
                            <td>${calificacion.calificacion}</td>
                        </tr>
                    `).join('')}
                </tbody>
            </table>
        </div>

    `;
    contenedor.appendChild(calificacionDiv);
}

// Función para cargar las actividades de un curso específico
async function cargarActividades(cursoId) {
    const contenedorActividades = document.getElementById(`contenedor-actividades-${cursoId}`);
    try {
        const response = await fetch(`Modulos/obtenerActividades.php?cursoId=${cursoId}`);
        const actividades = await response.json();
        const errorDiv = document.getElementById(`errorActividad-${cursoId}`);

        if (actividades.status === 'success' && actividades.data.length > 0) {
            renderizarActividades(cursoId, actividades.data, contenedorActividades);
        } else if (actividades.status === 'info') {
            errorDiv.textContent = actividades.message; // Asigna el mensaje de error
            errorDiv.classList.remove('d-none'); // Muestra el div eliminando la clase d-none
            errorDiv.classList.add('alert', 'alert-danger'); // Añade las clases de alerta
        } else if (actividades.status === 'error') {
            errorDiv.textContent = actividades.message; // Asigna el mensaje de error
            errorDiv.classList.remove('d-none'); // Muestra el div eliminando la clase d-none
            errorDiv.classList.add('alert', 'alert-danger'); // Añade las clases de alerta
        }
    } catch (error) {
        contenedorActividades.innerHTML = '<p>Error al cargar las actividades. Intenta nuevamente.</p>';
    }
}


// Función para renderizar las actividades en el DOM
function renderizarActividades(cursoId, actividades, contenedor) {

    actividades.forEach(actividad => {
        const actividadDiv = document.createElement('div');
        actividadDiv.classList.add('actividad');
        actividadDiv.innerHTML = `
            <h5> ${actividad.nombre}</h5>
            <p>Consigna: ${actividad.consigna}</p>
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

// Escuchar el formulario de subida
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

// Función para cargar el material de estudio de un curso
async function cargarMaterialEstudio(cursoId) {
    try {
        const response = await fetch(`Modulos/obtenerMaterialEstudio.php?cursoId=${cursoId}`);
        const data = await response.json();
        const errorDiv = document.getElementById(`errorMaterial-${cursoId}`);

        if (data.status === "success") {
            const materiales = data.data;
            renderizarMaterialEstudio(materiales, cursoId);
        } else if (data.status === 'error') {
            errorDiv.textContent = data.message; // Asigna el mensaje de error
            errorDiv.classList.remove('d-none'); // Muestra el div eliminando la clase d-none
            errorDiv.classList.add('alert', 'alert-danger'); // Añade las clases de alerta
        }
    } catch (error) {
        console.error("Error al cargar el material de estudio:", error);
    }
}

// Función para renderizar el material de estudio en el HTML
function renderizarMaterialEstudio(materiales, cursoId) {
    const contenedorMaterial = document.getElementById(`contenedor-material-${cursoId}`);

    if (!contenedorMaterial) {
        console.error(`No se encontró el contenedor para el material de estudio del curso con ID ${cursoId}`);
        return;
    }

    contenedorMaterial.innerHTML = `
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Nombre del Archivo</th>
                    <th>Descripción</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody>
                ${materiales.map(material => `
                    <tr>
                        <td>${material.titulo}</td>
                        <td>${material.descripcion}</td>
                        <td>
                            <button class="btn btn-primary" onclick="descargarMaterialEstudio(${material.id_material})">Descargar</button>
                        </td>
                    </tr>
                `).join('')}
            </tbody>
        </table>
    `;
}

// Función para descargar un archivo de material de estudio
function descargarMaterialEstudio(idMaterial) {
    window.location.href = `Modulos/descargarMaterialEstudio.php?id_material=${encodeURIComponent(idMaterial)}`;

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
