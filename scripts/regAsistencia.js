// Función para cargar los cursos
async function cargarCursos() {
    try {
        const response = await fetch('Modulos/listaCursosProfesor.php');
        const data = await response.json();
        const errorDiv = document.getElementById('errorAsistencias');

        if (data.status === 'success') {
            mostrarCursos(data.cursos);
        } else if (data.status === 'error') {
            errorDiv.textContent = data.message; // Asigna el mensaje de error
            errorDiv.classList.remove('d-none'); // Muestra el div eliminando la clase d-none
            errorDiv.classList.add('alert', 'alert-danger'); // Añade las clases de alerta
        }

        // else {
        //     console.error(data.message || 'No se encontraron cursos');
        // }
    } catch (error) {
        console.error(`Error en la conexión: ${error.message}`);
    }
}

// Función para mostrar los cursos
function mostrarCursos(cursos) {
    const tablaCursos = document.getElementById('tablaCursos').getElementsByTagName('tbody')[0];

    // Filtrar los cursos únicos usando Set
    const cursosUnicos = [...new Set(cursos.map(curso => curso.id))];  // Filtra los cursos por ID únicos

    // Iterar sobre los cursos únicos y agregar las filas
    cursosUnicos.forEach(idCurso => {
        const curso = cursos.find(c => c.id === idCurso);  // Encuentra el curso completo por ID
        const row = tablaCursos.insertRow();
        row.innerHTML = `
            <td>${curso.nombre}</td>
            <td>${curso.descripcion}</td>
            <td><button class="mb-2 btn btn-primary" onclick="mostrarAlumnos(${curso.id})">Tomar asistencia</button>
            <button class="mb-2 btn btn-warning" onclick="abrirModalModificarAsistencia(${curso.id})">Modificar Asistencia</button><br></td>
            
        `;
    });
}

// Función para cargar los alumnos de un curso
function mostrarAlumnos(idCurso) {
    const errorDiv = document.getElementById('errorTomarAsistencias');
    fetch('Modulos/cargarAlumnosPorCurso.php', {
        method: 'POST',
        body: JSON.stringify({ id_curso: idCurso }),  // Enviar JSON
        headers: { 'Content-Type': 'application/json' }
    })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                mostrarFormularioAsistencia(data.data, idCurso);
                // Abre el modal
                $('#modalAsistencia').modal('show');
            } else if (data.status === 'error') {
                errorDiv.textContent = data.message; // Asigna el mensaje de error
                errorDiv.classList.remove('d-none'); // Muestra el div eliminando la clase d-none
                errorDiv.classList.add('alert', 'alert-danger'); // Añade las clases de alerta
                $('#modalAsistencia').modal('show');
            }

            else {
                console.error(data.message);
            }
        })
        .catch(error => console.error('Error al cargar los alumnos:', error));
}

// Cargar todas las asistencias de un curso y mostrarlas en el modal
async function cargarAsistencias(idCurso) {
    try {
        const response = await fetch(`Modulos/obtenerAsistencias.php?idCurso=${idCurso}`);
        const data = await response.json();
        const errorDiv = document.getElementById('errorModalAsistencias');

        if (data.status === 'error') {
            errorDiv.textContent = data.message; // Asigna el mensaje de error
            errorDiv.classList.remove('d-none'); // Muestra el div eliminando la clase d-none
            errorDiv.classList.add('alert', 'alert-danger'); // Añade las clases de alerta
            // alert(data.message);
            return;
        }

        // Mostrar las asistencias en el modal
        mostrarAsistencias(data.data); // Pasar la lista de asistencias desde la clave `data`
    } catch (error) {
        console.error('Error al cargar las asistencias:', error);
    }
}

// Mostrar la lista de asistencias en el modal
function mostrarAsistencias(asistencias) {
    const listaAsistencias = document.getElementById('listaAsistencias');
    listaAsistencias.innerHTML = ''; // Limpiar la lista antes de agregar nuevas asistencias

    asistencias.forEach(asistencia => {
        const item = document.createElement('div');
        item.classList.add('list-group-item', 'd-flex', 'justify-content-between', 'align-items-center');
        item.innerHTML = `
            <span>
                <strong>Fecha:</strong> ${asistencia.fecha} <br>
                <strong>Curso:</strong> ${asistencia.nombre_curso}
            </span>
            <button class="btn btn-secondary btn-sm" onclick="editarAsistencia(${asistencia.id_asistencia})">Modificar</button>
        `;
        listaAsistencias.appendChild(item);
    });
}

// Abrir el modal para listar asistencias
function abrirModalModificarAsistencia(idCurso) {
    cargarAsistencias(idCurso); // Cargar datos del curso
    $('#modalModificarAsistencia').modal('show'); // Mostrar el modal
}

// Editar una asistencia específica
async function editarAsistencia(idAsistencia) {
    try {
        // Obtener los datos de la asistencia desde el backend
        const response = await fetch(`Modulos/obtenerAsistenciaPorId.php?idAsistencia=${idAsistencia}`);
        const data = await response.json();

        if (data.status === 'error') {
            alert(data.message);
            return;
        }

        // Precargar los datos en el modal
        const fechaAsistencia = document.getElementById('fechaAsistencia');
        fechaAsistencia.value = data.data.fecha; // Asignar fecha de asistencia
        fechaAsistencia.dataset.idCurso = data.data.id_curso; // Asignar ID del curso al dataset
        document.getElementById('idAsistencia').value = data.data.id_asistencia; // Asignar ID de asistencia

        // Precargar lista de alumnos con su estado
        const listaAlumnos = document.getElementById('listaAlumnos');
        listaAlumnos.innerHTML = ''; // Limpiar antes de rellenar

        // Resetear el estado de los radios para asegurarse de que no quede ninguno seleccionado
        listaAlumnos.querySelectorAll('input[type="radio"]').forEach(radio => {
            radio.checked = false; // Resetear el estado de los radios
        });

        data.data.alumnos.forEach(alumno => {
            const row = crearFilaEditar(alumno, alumno.estado); // Pasar estado para preseleccionar
            listaAlumnos.appendChild(row);
        });

        // Cambiar el texto del botón
        const btnGuardar = document.getElementById('guardarAsistenciaBtn');
        btnGuardar.innerText = 'Guardar Cambios';

        // Cerrar modal de modificación y abrir el modal de registro
        $('#modalModificarAsistencia').modal('hide');
        $('#modalAsistencia').modal('show');
    } catch (error) {
        console.error('Error al cargar la asistencia para edición:', error);
    }
}

// Mostrar el formulario de asistencia
function mostrarFormularioAsistencia(alumnos, idCurso) {
    const listaAlumnos = document.getElementById('listaAlumnos');
    listaAlumnos.innerHTML = ''; // Limpiar lista de alumnos

    // Guardar el id del curso en el campo de fecha
    const fechaAsistencia = document.getElementById('fechaAsistencia');
    fechaAsistencia.dataset.idCurso = idCurso;

    // Agregar cada alumno a la tabla usando la función auxiliar
    alumnos.forEach(alumno => {
        const row = crearFilaRegistrar(alumno, null); // Pasar null como estado
        listaAlumnos.appendChild(row);
    });

    // Limpiar el campo de fecha para la nueva sesión
    fechaAsistencia.value = '';
}

function crearFilaEditar(alumno, estado = null) {
    const row = document.createElement('tr');
    const estadoPresente = estado === 'Presente' ? 'checked' : '';
    const estadoAusente = estado === 'Ausente' ? 'checked' : '';

    row.innerHTML = `
        <td>${alumno.nombre} ${alumno.apellido || ''}</td>
        <td>
            <input type="radio" name="asistencia-${alumno.id_alumno}" value="Presente" ${estadoPresente}> Presente
        </td>
        <td>
            <input type="radio" name="asistencia-${alumno.id_alumno}" value="Ausente" ${estadoAusente}> Ausente
        </td>
    `;
    return row;
}

function crearFilaRegistrar(alumno, estado = null) {
    const row = document.createElement('tr');
    const estadoPresente = estado === 'Presente' ? 'checked' : '';
    const estadoAusente = estado === 'Ausente' ? 'checked' : '';

    row.innerHTML = `
        <td>${alumno.nombre} ${alumno.apellido || ''}</td>
        <td>
            <input type="radio" name="asistencia-${alumno.id}" value="Presente" ${estadoPresente}> Presente
        </td>
        <td>
            <input type="radio" name="asistencia-${alumno.id}" value="Ausente" ${estadoAusente}> Ausente
        </td>
    `;
    return row;
}

// Función para registrar o actualizar asistencia
async function guardarAsistencia() {
    const idAsistencia = document.getElementById('idAsistencia').value;
    const fecha = document.getElementById('fechaAsistencia').value;
    const idCurso = document.getElementById('fechaAsistencia').dataset.idCurso;

    // Validar fecha
    if (!fecha) {
        alert('Por favor, selecciona una fecha válida.');
        return;
    }

    // Validar que idCurso esté presente
    if (!idCurso) {
        alert('No se ha seleccionado un curso válido.');
        return;
    }

    // Recolectar datos de asistencia de los alumnos
    const asistencias = [];
    const listaAlumnos = document.querySelectorAll('#listaAlumnos tr');
    let valid = true;

    listaAlumnos.forEach(row => {
        const radios = row.querySelectorAll('input[type="radio"]');
        const idAlumno = row.querySelector('input[type="radio"]').name.split('-')[1];
        const seleccionado = Array.from(radios).some(radio => radio.checked);

        if (!seleccionado) {
            valid = false;
            row.classList.add('error-highlight');
        } else {
            row.classList.remove('error-highlight');
            const estado = Array.from(radios).find(radio => radio.checked).value;
            asistencias.push({ id: idAlumno, estado });
        }
    });

    if (!valid) {
        alert('Por favor, marca "Presente" o "Ausente" para todos los alumnos.');
        return;
    }

    try {
        // Primero, determino el endpoint en función de si estoy actualizando o registrando asistencia
        const urlDestino = idAsistencia ? 'Modulos/actualizarAsistencia.php' : 'Modulos/registrarAsistencia.php';
        // Preparo el objeto 'datos' con los datos necesarios, como el id del curso, fecha y las asistencias
        const datos = { idCurso, fecha, asistencias };
        // Si estoy actualizando una asistencia, añado el id de la asistencia al obj datos
        if (idAsistencia) {
            datos.idAsistencia = idAsistencia; // Solo añadir si existe
        }

        const response = await fetch(urlDestino, {
            method: 'POST',
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(datos),
        });

        const data = await response.json();
        if (data.status === 'success') {
            alert('Asistencia guardada exitosamente');
            $('#modalAsistencia').modal('hide');
        } else {
            alert(data.message || 'Error al guardar la asistencia');
        }
    } catch (error) {
        console.error('Error al guardar la asistencia:', error);
        alert('Ocurrió un problema al guardar la asistencia. Inténtalo más tarde.');
    }
}

// Limpiar valores residuales cuando el modal se cierra
$('#modalAsistencia').on('hidden.bs.modal', function () {
    document.getElementById('fechaAsistencia').dataset.idCurso = ''; // Limpia el dataset.idCurso
});


document.addEventListener('DOMContentLoaded', function () {
    const guardarBtn = document.getElementById('guardarAsistenciaBtn');

    // Escuchar el evento de click del botón de guardar (registro o edición)
    guardarBtn.removeEventListener('click', guardarAsistencia); // Elimina cualquier evento previo
    guardarBtn.addEventListener('click', function (e) {
        e.preventDefault();  // Prevenir comportamiento predeterminado
        guardarAsistencia(); // Llamar a la función correcta
    });

    cargarCursos(); // Cargar los cursos al cargar la página
});

