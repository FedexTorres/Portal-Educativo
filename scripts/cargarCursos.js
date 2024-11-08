// Función para mostrar errores globales 
function mostrarErrorGlobal(mensaje) {
    const errorGlobal = document.getElementById('errorGlobal');
    errorGlobal.classList.remove('d-none'); // Mostrar el contenedor de error

    if (Array.isArray(mensaje)) {
        const listaErrores = mensaje.map(error => `<li>${error}</li>`).join('');
        errorGlobal.innerHTML = `<ul>${listaErrores}</ul>`;
    } else {
        errorGlobal.innerHTML = `<p>${mensaje}</p>`;
    }
}

// Función para mostrar los cursos en cards
function mostrarCursos(cursos) {
    const contenedorCursos = document.getElementById('courses-section');
    contenedorCursos.innerHTML = ''; // Limpiar el contenedor antes de agregar las cards
    cursos.forEach(curso => {
        // Generar el HTML para cada card
        const cardHTML = `
        <div class="col-md-4 mb-4">
            <div class="card bg-secondary text-light border-0">
                <img src="${curso.imagen_url}" class="card-img-top card-image" alt="${curso.nombre}">
                <div class="card-body">
                    <h5 class="card-title">${curso.nombre}</h5>
                    <p class="card-text"><b>Descripción:</b> <hr>${curso.descripcion}</p>
                    <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modalCurso${curso.id}">
                        Ver Detalles
                    </button>
                </div>
            </div>
        </div>
    `;
        contenedorCursos.innerHTML += cardHTML;

        // Generar el HTML para cada modal
        const modalHTML = `
        <div class="modal fade" id="modalCurso${curso.id}" tabindex="-1" aria-labelledby="modalCurso${curso.id}Label" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCurso${curso.id}Label">${curso.nombre} - Detalles</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <b>Programa de Estudios:</b><br><br> ${curso.programa_estudios}
                        <br><br>
                        <b>Fecha de Inicio:</b> ${curso.fecha_inicio} <br>
                        <b>Fecha de Fin:</b> ${curso.fecha_fin} <br>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-primary" data-id="${curso.id}">Inscribirse</button>
                    </div>
                    <div id="errorModal${curso.id}" class="alert alert-danger d-none"></div>
                    <div id="mjsExito${curso.id}" class="alert alert-success d-none"></div>
                </div>
            </div>
        </div>
    `;
        contenedorCursos.innerHTML += modalHTML; // Esto agregará el modal dentro de la misma sección
    });

}

// Función para cargar los cursos desde el backend
async function cargarCursos() {
    try {
        const response = await fetch('Modulos/obtenerCursos.php');
        const data = await response.json();

        if (data.status === 'success') {
            mostrarCursos(data.cursos);
        } else {
            mostrarErrorGlobal(data.message || 'No se encontraron cursos disponibles');
        }
    } catch (error) {
        mostrarErrorGlobal(`Error en la conexión: ${error.message}`);
    }
}

// Inicializar la carga de cursos al cargar la página
document.addEventListener('DOMContentLoaded', cargarCursos);
