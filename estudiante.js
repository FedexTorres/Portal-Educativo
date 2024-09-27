function validarFormuMensaje(e) {
    e.preventDefault();

    // Obtener valores del formulario
    //const destinatario = document.getElementById('destinatario').value; // variable para guardar el destino
    const mensaje = document.getElementById('mensaje').value;

    // Aquí se deberia agregar codigo para conectar con php?

    // Añadir el mensaje a la lista de mensajes recibidos
    const listaMensajes = document.getElementById('lista-mensajes');
    const nuevoMensaje = document.createElement('a');
    nuevoMensaje.className = 'list-group-item list-group-item-action';
    nuevoMensaje.innerHTML = `<strong>Remitente:</strong> Yo <br>
                              <strong>Mensaje:</strong> ${mensaje} <br>
                              <small>Fecha: ${new Date().toLocaleDateString()}</small>`;
    listaMensajes.appendChild(nuevoMensaje);

    // Limpiar el formulario
    this.reset();
}

function mostrarMenus(sections) {
    // Escuchar clics en los botones del menú
    document.querySelectorAll('.btn-menu').forEach(boton => {
        boton.addEventListener('click', function () {
            const botonId = this.id;   //segun el boton escuchado, se pasa el id a la variable botonId

            // Ocultar todas las secciones primero
            sections.forEach(section => section.classList.add('d-none'));

            // Mostrar la sección correspondiente según el botón clickeado
            switch (botonId) {
                case 'inicio-btn':
                    document.getElementById('seccion-inicio').classList.remove('d-none');
                    break;
                case 'mis-cursos-btn':
                    document.getElementById('seccion-mis-cursos').classList.remove('d-none');
                    break;
                case 'mensajes-btn':
                    document.getElementById('seccion-mensajes').classList.remove('d-none');
                    break;
                case 'perfil-btn':
                    document.getElementById('seccion-perfil').classList.remove('d-none');
                    break;
                default:
                    console.log('Botón no reconocido');
                    break;
            }
        });
    });

}

function manejarSubSecciones() {
    // Botones para mostrar sub-secciones
    const btnConsultarAsistencia = document.querySelector('.btn-consultar-asistencia');
    const btnConsultarCalificacion = document.querySelector('.btn-consultar-calificacion');
    const btnRealizarExamen = document.querySelector('.btn-realizar-examen');
    const btnSubirTrabajo = document.querySelector('.btn-subir-trabajo');
    const btnFiltrarAsistencia = document.querySelector('.btn-filtrar-asistencia');

    const btnVolver = document.querySelectorAll('.btn-volver');

    const subSeccionAsistencia = document.getElementById('sub-seccion-asistencia');
    const subSeccionCalificacion = document.getElementById('sub-seccion-calificacion');
    const subSeccionExamen = document.getElementById('sub-seccion-examen');
    const subSeccionSubirTrabajo = document.getElementById('sub-seccion-subir-trabajo');
    const subSeccionFiltrarAsistencia = document.getElementById('sub-seccion-filtrar-asistencia');
    const seccionMisCursos = document.getElementById('seccion-mis-cursos');

    // Mostrar sub-sección de Asistencia
    btnConsultarAsistencia.addEventListener('click', function () {
        subSeccionAsistencia.classList.remove('d-none');
        seccionMisCursos.querySelector('.card').classList.add('d-none'); // Ocultar card de curso
    });

    // Mostrar sub-sección de Calificación
    btnConsultarCalificacion.addEventListener('click', function () {
        subSeccionCalificacion.classList.remove('d-none');
        seccionMisCursos.querySelector('.card').classList.add('d-none'); // Ocultar card de curso
    });

    // Mostrar sub-sección de Examen
    btnRealizarExamen.addEventListener('click', function () {
        subSeccionExamen.classList.remove('d-none');
        seccionMisCursos.querySelector('.card').classList.add('d-none'); // Ocultar card de curso
    });

    // Mostrar sub-sección para Subir Trabajo Práctico
    btnSubirTrabajo.addEventListener('click', function () {
        subSeccionSubirTrabajo.classList.remove('d-none');
        seccionMisCursos.querySelector('.card').classList.add('d-none'); // Ocultar card de curso
    });

    // Mostrar sub-sección para Filtrar Asistencia
    btnFiltrarAsistencia.addEventListener('click', function () {
        subSeccionFiltrarAsistencia.classList.remove('d-none');
        seccionMisCursos.querySelector('.card').classList.add('d-none'); // Ocultar card de curso
    });

    // Volver a la vista principal de "Mis Cursos"
    btnVolver.forEach(boton => {
        boton.addEventListener('click', function () {
            subSeccionAsistencia.classList.add('d-none');
            subSeccionCalificacion.classList.add('d-none');
            subSeccionExamen.classList.add('d-none');
            subSeccionSubirTrabajo.classList.add('d-none');
            subSeccionFiltrarAsistencia.classList.add('d-none');
            seccionMisCursos.querySelector('.card').classList.remove('d-none'); // Mostrar card de curso
        });
    });

    // Manejar el envío del examen (solo un ejemplo)
    const formExamen = document.getElementById('form-realizar-examen');
    formExamen.addEventListener('submit', function (event) {
        event.preventDefault();
        alert('Respuesta enviada!'); // Aquí podria tambien mostrar un modal, que opinan profes?
        subSeccionExamen.classList.add('d-none');
        seccionMisCursos.querySelector('.card').classList.remove('d-none'); // Volver a la card
    });

    // Manejar el envío del trabajo práctico (solo un ejemplo)
    const formSubirTrabajo = document.getElementById('form-subir-trabajo');
    formSubirTrabajo.addEventListener('submit', function (event) {
        event.preventDefault();
        alert('Trabajo práctico subido exitosamente!'); // Aquí lo mismo, pongo un modal profe?
        subSeccionSubirTrabajo.classList.add('d-none');
        seccionMisCursos.querySelector('.card').classList.remove('d-none'); // Volver a la card
    });
}

function manejarPerfil(e) {
    e.preventDefault();
    errorClave.innerHTML = ""; // Limpiar mensajes anteriores
    errorNombre.innerHTML = ""; // Limpiar mensajes anteriores
    errorApellido.innerHTML = ""; // Limpiar mensajes anteriores
    errorFecha.innerHTML = ""; // Limpiar mensajes anteriores
    errorCorreo.innerHTML = ""; // Limpiar mensajes anteriores

    // Aquí se enviarian los datos a la base de datos o simular el guardado.

    let esValido = true; // Asumimos que es válido al inicio

    // Validar el nombre    
    let expresionRegular = /^[a-zA-ZáéíóúÁÉÍÓÚ ]+$/;
    const nombre = document.getElementById('nombre').value;
    if (!expresionRegular.test(nombre)) {
        errorNombre.innerHTML = "<b>No se permiten caracteres especiales</b>";
        esValido = false;
    }

    // Validar el apellido
    const apellido = document.getElementById('apellido').value;
    if (!expresionRegular.test(apellido)) {
        errorApellido.innerHTML = "<b>No se permiten caracteres especiales</b>";
        esValido = false;
    }

    // Validar el correo electrónico 
    //No se muestra el mensaje de error ya que en el html se puso type=email, si se cambia a text, si se muestra el error.
    const correo = document.getElementById('correo').value;
    const expresionCorreo = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (!expresionCorreo.test(correo)) {
        errorCorreo.innerHTML = "<b>El correo electrónico no es válido</b>";
        esValido = false;
    }

    // Validar que la fecha no sea en el futuro
    const fechaNacimiento = document.getElementById('fecha-nacimiento').value;
    const hoy = new Date();
    const fechaNacimientoDate = new Date(fechaNacimiento);

    if (fechaNacimientoDate > hoy) {
        errorFecha.innerHTML = "<b>La fecha de nacimiento no puede ser en el futuro</b>";
        esValido = false;
    } else {
        // Solo validar edad mínima si la fecha no es en el futuro
        const edadMinima = 16;
        const diferencia = hoy.getFullYear() - fechaNacimientoDate.getFullYear();
        const mesDiferencia = hoy.getMonth() - fechaNacimientoDate.getMonth();
        const diaDiferencia = hoy.getDate() - fechaNacimientoDate.getDate();

        if (diferencia < edadMinima || (diferencia === edadMinima && (mesDiferencia < 0 || (mesDiferencia === 0 && diaDiferencia < 0)))) {
            errorFecha.innerHTML = "<b>Debes ser mayor de 16 años</b>";
            esValido = false;
        }
    }

    // Validar contraseña
    const clave = document.getElementById('clave').value;
    const claveConfirmacion = document.getElementById("claveConfirmacion").value;
    if (clave !== claveConfirmacion) {
        errorClave.innerHTML = "<b>Las contraseñas no coinciden</b>";
        esValido = false;
    }

    if (esValido) {
        // Simulación de guardar datos
        console.log("es valido");
        alert(`Datos guardados:\nNombre: ${nombre}\nApellido: ${apellido}\nCorreo: ${correo}\nFecha de Nacimiento: ${fechaNacimiento}\nContraseña:${clave}`);
    }
};


function inicio() {
    const sections = document.querySelectorAll('section');
    document.getElementById('seccion-inicio').classList.remove('d-none');// Mostrar solo la sección de Inicio al cargar la página
    mostrarMenus(sections);
    document.getElementById('form-enviar-mensaje').addEventListener('submit', validarFormuMensaje);
    manejarSubSecciones();
    document.getElementById('form-perfil').addEventListener('submit', manejarPerfil); // Maneja la sección de perfil

}

window.onload = inicio;

