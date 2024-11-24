document.addEventListener("DOMContentLoaded", () => {
    cargarEntregas(); // Llamamos a la función principal al cargar el DOM
});

// Cargar entregas en la tabla al iniciar
async function cargarEntregas() {
    try {
        // Realizar la petición para obtener las entregas del profesor
        const response = await fetch("Modulos/obtenerEntregas.php");
        const data = await response.json();
        const errorDiv = document.getElementById('errorCalificaciones');

        if (data.status === "success") {
            const entregas = data.data;

            // Renderizamos las entregas en la tabla
            renderizarTablaEntregas(entregas);

            // Extraemos los cursos de las entregas y los mostramos en el select
            llenarSelectCursos(entregas);

        } else if (data.status === 'error') {
            errorDiv.textContent = data.message; // Asigna el mensaje de error
            errorDiv.classList.remove('d-none'); // Muestra el div eliminando la clase d-none
            errorDiv.classList.add('alert', 'alert-danger'); // Añade las clases de alerta
        }

    } catch (error) {
        console.error("Error al cargar las entregas:", error);
        alert("Hubo un error al intentar cargar las entregas.");
    }
}

// Llenar el select con los cursos obtenidos
function llenarSelectCursos(cursos) {
    // Crear el select y el contenedor del formulario
    const div = document.createElement("div");
    div.classList.add("mb-3");

    const label = document.createElement("label");
    label.setAttribute("for", "curso-select");
    label.classList.add("form-label");
    label.textContent = "Curso:";

    const cursoSelect = document.createElement("select");
    cursoSelect.id = "curso-select";
    cursoSelect.classList.add("form-select");

    // Limpiar cualquier opción previa
    cursoSelect.innerHTML = "<option value=''>Seleccionar curso</option>";

    // Crear un Set para evitar duplicados y luego convertirlo en un array
    const cursosUnicos = [...new Set(cursos.map(curso => curso.curso))];

    // Agregar las opciones al select
    cursosUnicos.forEach(cursoNombre => {
        const option = document.createElement("option");
        option.value = cursoNombre; // Valor de la opción
        option.textContent = cursoNombre; // Texto visible
        cursoSelect.appendChild(option);
    });

    // Agregar el label y el select al contenedor
    div.appendChild(label);
    div.appendChild(cursoSelect);

    // Añadir todo al contenedor de formulario donde quieras insertar este select
    document.getElementById("form-container").appendChild(div);
}

// Renderizar la tabla de entregas
function renderizarTablaEntregas(entregas) {
    const tablaEntregas = document.querySelector("#tabla-entregas tbody");
    tablaEntregas.innerHTML = ""; // Limpiamos la tabla antes de cargar nuevas entregas

    entregas.forEach(entrega => {
        const fila = document.createElement("tr");

        fila.innerHTML = `
            <td>${entrega.id_entrega}</td>
            <td>${entrega.actividad}</td>
            <td>${entrega.fecha_entrega}</td>
            <td><button class="btn btn-primary" onclick="descargarArchivo(${entrega.id_entrega})">Descargar</button></td>
            <td><button class="btn btn-success" onclick="mostrarModalCalificar(${entrega.id_entrega})">Calificar</button></td>
        `;

        tablaEntregas.appendChild(fila);
    });
}

// Función para descargar el archivo de la entrega
function descargarArchivo(idEntrega) {
    window.location.href = `Modulos/descargarArchivo.php?id_entrega=${encodeURIComponent(idEntrega)}`;
}

// Mostrar el modal para calificar una entrega
function mostrarModalCalificar(idEntrega) {
    const modal = document.getElementById("modalCalificar");
    modal.setAttribute("data-id-entrega", idEntrega); // Establece el ID en el modal
    const bootstrapModal = new bootstrap.Modal(modal);
    bootstrapModal.show();
}

// Guardar la calificación desde el modal

async function guardarCalificacionModal() {
    const nota = document.getElementById("inputCalificacion").value;
    const idEntrega = document.getElementById("modalCalificar").getAttribute("data-id-entrega");
    const errorDiv = document.getElementById('errorModalCalificaciones');

    if (!nota) {
        alert("Por favor, ingrese una calificación válida.");
        return;
    }

    try {
        const response = await fetch("Modulos/guardarCalificacion.php", {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ id_entrega: idEntrega, calificacion: nota })
        });

        const data = await response.json();

        if (data.status === "success") {
            alert("Calificación guardada exitosamente");
            cargarEntregas(); // Recargar la tabla para mostrar la calificación actualizada
            // Cerrar el modal solo si la calificación se guardó con éxito
            const modal = bootstrap.Modal.getInstance(document.getElementById("modalCalificar"));
            modal.hide();
        } else if (data.status === 'error') {
            // Mostrar el mensaje de error
            errorDiv.textContent = data.message; // Asigna el mensaje de error
            errorDiv.classList.remove('d-none'); // Muestra el div eliminando la clase d-none
            errorDiv.classList.add('alert', 'alert-danger'); // Añade las clases de alerta

            // Esperar 3 segundos antes de cerrar el modal
            setTimeout(() => {
                const modal = bootstrap.Modal.getInstance(document.getElementById("modalCalificar"));
                modal.hide(); // Cierra el modal
                errorDiv.classList.add('d-none'); // Oculta el mensaje de error
                errorDiv.classList.remove('alert', 'alert-danger'); // Elimina las clases de alerta
                errorDiv.textContent = ''; // Limpia el mensaje de error
            }, 2000);
        }

    } catch (error) {
        console.error("Error al guardar la calificación:", error);
        alert("Hubo un error al intentar guardar la calificación.");
    }
}


