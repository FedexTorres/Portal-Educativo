
// Función para mostrar errores globales
function mostrarErrorModal(cursoId, mensaje) {
    const errorModal = document.getElementById(`errorModal${cursoId}`);
    if (!errorModal) return; // Si no encuentra el modal, termina la función.
    errorModal.classList.remove('d-none'); // Muestra el contenedor de error
    // Si `mensaje` es un array, crear lista de errores
    if (Array.isArray(mensaje)) {
        const listaErrores = mensaje.map(error => `<li>${error}</li>`).join('');
        errorModal.innerHTML = `<ul>${listaErrores}</ul>`;
    } else {
        errorModal.innerHTML = `<p>${mensaje}</p>`;
    }
    setTimeout(() => {
        errorModal.classList.add('d-none');
    }, 4000);
}

// Función para mostrar mensajes de éxito
function mostrarMjsExito(cursoId, mensaje) {
    const mensajeExito = document.getElementById(`mjsExito${cursoId}`);
    if (!mensajeExito) return; // Si no encuentra el contenedor, no hace nada.

    mensajeExito.classList.remove('d-none');
    mensajeExito.innerHTML = mensaje;
    setTimeout(() => {
        mensajeExito.classList.add('d-none');
    }, 4000);
}

// Función principal para manejar la inscripción al curso
async function inscribirEnCurso(e) {
    const cursoId = e.target.getAttribute('data-id'); // Tomamos el cursoId directamente del botón
    if (!cursoId) {
        mostrarErrorModal(cursoId, "Error: el ID del curso no se ha encontrado.");
        return;
    }

    const datos = new FormData();
    datos.append('curso_id', cursoId);

    try {
        const respuesta = await fetch('Modulos/inscripcionCurso.php', {
            method: 'POST',
            body: datos,
        });

        if (!respuesta.ok) {
            throw new Error('Error en la solicitud al servidor');
        }

        const resultado = await respuesta.json();
        if (resultado.status === 'success') {
            mostrarMjsExito(cursoId, resultado.message);
        } else {
            mostrarErrorModal(cursoId, resultado.message);

        }
    } catch (error) {
        console.error('Error de conexión:', error.message);
        mostrarErrorModal(`Error de conexión: ${error.message}`);
    }
}

function inicio() {
    // Usamos delegación de eventos: escuchar los clics en el contenedor de los botones
    document.querySelector('#courses-section').addEventListener('click', function (e) {
        // Verificar que el clic fue en un botón con la clase .btn-primary y un atributo data-id
        if (e.target && e.target.matches('button.btn-primary[data-id]')) {
            inscribirEnCurso(e);
        }
    });
}
document.addEventListener('DOMContentLoaded', inicio);
