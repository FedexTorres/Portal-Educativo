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

function inicio() {

    const sections = document.querySelectorAll('section');
    document.getElementById('seccion-inicio').classList.remove('d-none');// Mostrar solo la sección de Inicio al cargar la página
    mostrarMenus(sections);
    manejarSubSecciones();

}

window.onload = inicio;