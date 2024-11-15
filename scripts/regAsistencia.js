// Función para cargar los cursos
async function cargarCursos() {
    console.log("Llamando a cargarCursos()");
    try {
        const response = await fetch('Modulos/getMisCursosProfesor.php');
        const data = await response.json();

        if (data.status === 'success') {
            mostrarCursos(data.cursos);
        } else {
            console.error(data.message || 'No se encontraron cursos');
        }
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
            <td><button class="btn btn-primary" onclick="mostrarAlumnos(${curso.id})">Tomar asistencia</button></td>
        `;
    });
}

// Función para cargar los alumnos de un curso

function mostrarAlumnos(idCurso) {
    console.log(idCurso);
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
            } else {
                console.error(data.message);
            }
        })
        .catch(error => console.error('Error al cargar los alumnos:', error));
}

// Mostrar el formulario de asistencia
function mostrarFormularioAsistencia(alumnos, idCurso) {
    const listaAlumnos = document.getElementById('listaAlumnos');
    listaAlumnos.innerHTML = '';  // Limpiar lista de alumnos

    // Mostrar el curso en el modal (opcional)
    const fechaAsistencia = document.getElementById('fechaAsistencia');
    fechaAsistencia.dataset.idCurso = idCurso;  // Guardamos el idCurso en el input de la fecha como un dato adicional

    // Mostrar los alumnos con botones de asistencia
    alumnos.forEach(alumno => {
        const listItem = document.createElement('div');
        listItem.classList.add('list-group-item');
        listItem.innerHTML = `
            ${alumno.nombre} ${alumno.apellido} 
            <input type="radio" name="asistencia-${alumno.id}" value="Presente"> Presente
            <input type="radio" name="asistencia-${alumno.id}" value="Ausente"> Ausente
        `;
        listaAlumnos.appendChild(listItem);
    });

    // Limpiar el campo de fecha de asistencia para la nueva sesión
    document.getElementById('fechaAsistencia').value = '';
}


// Función para registrar la asistencia
async function registrarAsistencia() {
    const fecha = document.getElementById('fechaAsistencia').value;
    const alumnos = document.querySelectorAll('#listaAlumnos input[type="radio"]:checked');
    const asistencias = [];
    const idCurso = document.getElementById('fechaAsistencia').dataset.idCurso; // Obtener el idCurso

    alumnos.forEach(alumno => {
        asistencias.push({
            id_alumno: alumno.name.split('-')[1], // Obtener id del alumno
            estado: alumno.value
        });
    });

    const response = await fetch('Modulos/registrarAsistencia.php', {
        method: 'POST',
        body: JSON.stringify({ idCurso, fecha, asistencias }),
        headers: { 'Content-Type': 'application/json' }
    });
    const data = await response.json();

    if (data.status === 'success') {
        alert('Asistencia registrada con éxito');
    } else {
        alert('Error al registrar la asistencia');
    }
}

document.addEventListener('DOMContentLoaded', function () {
    const formAsistencia = document.getElementById('formAsistencia');

    // Escuchar el evento de submit del formulario
    formAsistencia.addEventListener('submit', function (e) {
        e.preventDefault();  // Prevenir que el formulario se envíe de manera tradicional
        registrarAsistencia();  // Llamar a la función para registrar asistencia
    });

    cargarCursos(); // Cargar los cursos al cargar la página
});
