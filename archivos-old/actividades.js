
// Función cargarActividades - Realiza la petición AJAX y obtiene las actividades del curso
function cargarActividades(idCurso) {
    fetch(`obtenerActividades.php?id_curso=${idCurso}`)
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                renderizarActividades(data.data, idCurso); // Enviar actividades y ID de curso a renderizar
            } else {
                mostrarMensajeError(data.message || 'No se pudieron cargar las actividades');
            }
        })
        .catch(error => {
            console.error('Error en la solicitud:', error);
            mostrarMensajeError('Error al cargar las actividades');
        });
}

// Función renderizarActividades - Renderiza las actividades en la interfaz
function renderizarActividades(actividades, idCurso) {
    // Buscar el contenedor de actividades específico del curso
    const contenedorCurso = document.querySelector(`.card[data-id="${idCurso}"]`);
    let contenedorActividades = contenedorCurso.querySelector('.sub-seccion-actividades');

    // Crear el contenedor de actividades si no existe
    if (!contenedorActividades) {
        contenedorActividades = document.createElement('div');
        contenedorActividades.classList.add('sub-seccion', 'sub-seccion-actividades');
        contenedorCurso.appendChild(contenedorActividades);
    }

    // Limpiar actividades previas y agregar título
    contenedorActividades.innerHTML = '<h4>Actividades del Curso</h4>';

    if (actividades.length > 0) {
        const listaActividades = document.createElement('ul');
        actividades.forEach(actividad => {
            const item = document.createElement('li');
            item.innerHTML = `<strong>${actividad.consigna}</strong> - Fecha límite: ${actividad.fecha_limite}`;
            listaActividades.appendChild(item);
        });
        contenedorActividades.appendChild(listaActividades);
    } else {
        contenedorActividades.innerHTML += '<p>No hay actividades disponibles para este curso.</p>';
    }

    contenedorActividades.classList.remove('d-none'); // Mostrar el contenedor de actividades
}

// Función para mostrar mensajes de error
function mostrarMensajeError(mensaje) {
    alert(mensaje); // Puedes personalizar el estilo o usar un modal en lugar de alert
}

// Función obtenerIdCursoSeleccionado - Obtiene el ID del curso seleccionado en el DOM
function obtenerIdCursoSeleccionado() {
    // Buscar la tarjeta que tiene la clase 'selected'
    const cursoSeleccionado = document.querySelector('.card.selected');
    if (cursoSeleccionado) {
        // Si se encuentra una tarjeta seleccionada, devolver su ID
        return cursoSeleccionado.dataset.id;
    }
    // Si no hay ninguna tarjeta seleccionada, retornar null
    return null;
}

// Agregar un event listener para hacer clic en las tarjetas de los cursos
document.addEventListener('DOMContentLoaded', () => {
    const tarjetasCursos = document.querySelectorAll('.card'); // Seleccionar todas las tarjetas de curso

    tarjetasCursos.forEach(cursoCard => {
        // Event listener para cada tarjeta de curso
        cursoCard.addEventListener('click', () => {
            // Eliminar la clase 'selected' de todas las tarjetas
            tarjetasCursos.forEach(card => card.classList.remove('selected'));

            // Añadir la clase 'selected' a la tarjeta clickeada
            cursoCard.classList.add('selected');

            // Obtener el ID del curso seleccionado
            const idCurso = obtenerIdCursoSeleccionado();
            if (idCurso) {
                console.log('ID del curso seleccionado:', idCurso); // Log para verificar
                cargarActividades(idCurso); // Cargar actividades para este curso
            } else {
                console.log('ID de curso no encontrado para cargar actividades');
            }
        });
    });
});


// Función principal que se ejecuta cuando el DOM se ha cargado completamente
function inicio() {
    const idCurso = obtenerIdCursoSeleccionado(); // Define tu propia lógica para obtener el ID del curso seleccionado
    if (idCurso) {
        cargarActividades(idCurso); // Llamada AJAX para cargar actividades del curso seleccionado
    } else {
        console.error("ID de curso no encontrado para cargar actividades");
    }
}

// Event listener que ejecuta la función 'inicio' cuando el DOM está completamente cargado
document.addEventListener('DOMContentLoaded', inicio);