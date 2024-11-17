document.addEventListener("DOMContentLoaded", () => {
    cargarActividades();
    cargarCurso();
    // Llamamos a la función adecuada según el caso
    const formCrearActividad = document.getElementById("form-crear-actividad");
    formCrearActividad.addEventListener("submit", (event) => {
        event.preventDefault();
        // Si estamos creando (idActividadEditando es null), llamamos a crearActividad
        if (idActividadEditando === null) {
            crearActividad();
        } else {
            manejarFormulario(event); // Llamamos a la función de edición
        }
    });
});

// Cargar datos de la actividad en el formulario para editar
async function cargarDatosEnFormulario(idActividad) {
    try {
        const response = await fetch(`Modulos/traerActividad.php?idActividad=${idActividad}`);
        const actividad = await response.json();

        if (actividad.status === "success") {
            // Llenar el formulario con los datos de la actividad
            document.getElementById("nombre-actividad").value = actividad.data.nombre;
            document.getElementById("consigna-actividad").value = actividad.data.consigna;
            document.getElementById("fecha-limite").value = actividad.data.fecha_limite;
            document.getElementById("curso-select").value = actividad.data.id_curso;

            // Guardar el ID en la variable global para saber que estamos en modo edición
            idActividadEditando = idActividad;

            // Cambiar el texto del botón
            document.querySelector("#form-crear-actividad button[type='submit']").textContent = "Actualizar Actividad";
        } else {
            console.log("Error al cargar datos:", actividad.message);
        }
    } catch (error) {
        console.error("Error al obtener datos de la actividad:", error);
    }
    cargarActividades();
}

// Manejar envío del formulario para crear o actualizar
async function manejarFormulario(event) {
    event.preventDefault();

    const nombre = document.getElementById("nombre-actividad").value;
    const consigna = document.getElementById("consigna-actividad").value;
    const fechaLimite = document.getElementById("fecha-limite").value;
    const cursoId = document.getElementById("curso-select").value;

    // Configuración solo para la edición
    const url = "Modulos/editarActividad.php";
    const params = new URLSearchParams({
        idActividad: idActividadEditando,  // Enviamos el ID de la actividad a editar
        nombre,
        consigna,
        fecha_limite: fechaLimite,
        curso_id: cursoId
    });

    try {
        const response = await fetch(url, {
            method: "POST",
            headers: { "Content-Type": "application/x-www-form-urlencoded" },
            body: params.toString()  // Enviamos en formato URL encoded
        });

        const result = await response.json();

        if (result.status === "success") {
            cargarActividades(); // Recargar actividades tras crear o editar
            limpiarFormulario();
            mostrarAlerta('success', result.message); // Mensaje de éxito
        } else {
            console.log(result.message);
        }
    } catch (error) {
        console.error("Error al enviar el formulario:", error);
    }
}

// Limpiar formulario y restablecer modo de creación
function limpiarFormulario() {
    document.getElementById("form-crear-actividad").reset();
    idActividadEditando = null; // Borrar el ID de edición
    document.querySelector("#form-crear-actividad button[type='submit']").textContent = "Crear Actividad";
}

// Cargar actividades desde el backend
async function cargarActividades() {
    try {
        const response = await fetch("Modulos/buscarActividades.php");
        const data = await response.json();

        if (data.status === "success") {
            renderizarActividades(data.data);
            agregarListenersBotones(); // Agregar los listeners a los botones después de renderizar las actividades
        } else {
            console.log(data.message);
        }
    } catch (error) {
        console.error("Error al cargar actividades:", error);
    }
}

function agregarListenersBotones() {
    // Selecciona todos los botones de edición y agrega el evento
    document.querySelectorAll(".btn-editar").forEach((boton) => {
        boton.addEventListener("click", (evento) => {
            const idActividad = evento.target.getAttribute("data-id");
            cargarDatosEnFormulario(idActividad);
        });
    });

    // Selecciona todos los botones de eliminación y agrega el evento
    document.querySelectorAll(".btn-eliminar").forEach((boton) => {
        boton.addEventListener("click", (evento) => {
            const idActividad = evento.target.getAttribute("data-id");
            eliminarActividad(idActividad);
        });
    });
}
// Renderizar las actividades en la tabla
function renderizarActividades(actividades) {
    const tbody = document.getElementById("tabla-actividades").querySelector("tbody");
    tbody.innerHTML = "";

    actividades.forEach((actividad) => {
        const fila = document.createElement("tr");

        fila.innerHTML = `
            <td>${actividad.actividad_nombre}</td>
            <td>${actividad.consigna}</td>
            <td>${actividad.fecha_limite}</td>
            <td>${actividad.curso_nombre}</td>
            <td>
                <button class="btn btn-warning btn-editar" data-id="${actividad.id}">Editar</button>
                <button class="btn btn-danger btn-eliminar" data-id="${actividad.id}">Eliminar</button>
            </td>
        `;

        tbody.appendChild(fila);
    });
}

// Cargar cursos en el select del formulario
async function cargarCurso() {
    try {
        const response = await fetch("Modulos/selectCursos.php");
        const data = await response.json();

        if (data.status === "success") {
            const selectCursos = document.getElementById("curso-select");
            data.data.forEach((curso) => {
                const option = document.createElement("option");
                option.value = curso.id;
                option.textContent = curso.nombre;
                selectCursos.appendChild(option);
            });
        } else {
            console.log(data.message);
        }
    } catch (error) {
        console.error("Error al cargar cursos:", error);
    }
}

// Crear una nueva actividad
async function crearActividad() {
    const nombre = document.getElementById("nombre-actividad").value;
    const consigna = document.getElementById("consigna-actividad").value;
    const fechaLimite = document.getElementById("fecha-limite").value;
    const cursoId = document.getElementById("curso-select").value;

    try {
        const response = await fetch("Modulos/crearActividades.php", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
            },
            body: JSON.stringify({ nombre, consigna, fecha_limite: fechaLimite, id_curso: cursoId }),
        });
        const data = await response.json();

        if (data.status === "success") {
            // Recargar actividades tras crear una nueva
            cargarActividades();
            document.getElementById("form-crear-actividad").reset();
            mostrarAlerta('success', data.message); // Mensaje de éxito
        } else {
            mostrarAlerta('danger', data.message); // Mensaje de error
            console.log(data.message);
        }
    } catch (error) {
        console.error("Error al crear actividad:", error);
        mostrarAlerta('danger', 'Ocurrió un error inesperado al crear la actividad');
    }
}

// Función para mostrar el mensaje de alerta
function mostrarAlerta(tipo, mensaje) {
    const alertContainer = document.getElementById('alert-container');
    alertContainer.innerHTML = `
    <div class="alert alert-${tipo} alert-dismissible fade show" role="alert">
    ${mensaje}
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    `;
    // Ocultar la alerta después de 3 segundos
    setTimeout(() => {
        alertContainer.innerHTML = ''; // Limpiar el contenedor de alertas
    }, 3000); // 3000 milisegundos = 3 segundos
}

async function eliminarActividad(idActividad) {
    if (!confirm("¿Estás seguro de que deseas eliminar esta actividad?")) return;

    try {
        const response = await fetch('Modulos/eliminarActividad.php', {
            method: 'POST',
            headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
            body: new URLSearchParams({ idActividad })
        });
        const result = await response.json();
        alert(result.message);
        cargarActividades();
    } catch (error) {
        console.error('Error al eliminar actividad:', error);
    }
}
