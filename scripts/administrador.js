
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
                case 'cursos-btn':
                    document.getElementById('seccion-mis-cursos').classList.remove('d-none');
                    break;               
                case 'usuarios-btn':
                    document.getElementById('seccion-usuarios').classList.remove('d-none');


                    break;
                case 'mensaje-btn':
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

//codigo para mostrar cursos

function mostrarCursos(cursos) {
    const contenedorCursos = document.getElementById('courses-section');
    contenedorCursos.innerHTML = ''; // Limpiar el contenedor antes de agregar las cards
    cursos.forEach(curso => {
        // Generar el HTML para cada card
        const cardHTML = `
        <div class="col-lg-6 col-md-12">
        <div class="card mt-4">
        <div class="card-header">
          <h5 class="card-title">${curso.nombre}</h5>
        </div>
        <div class="card-body">
          <img src="Portal-Educativo/${curso.imagen_url}" alt="${curso.nombre}" class="card-img imgadmin">
          <p class="card-text">Descripcion</p>
          <p class="card-text">${curso.descripcion}</p>
          <h6 class="card-subtitle mb-2 text-muted">Fecha de Inicio: ${curso.fecha_inicio}| Fecha de Fin: ${curso.fecha_fin}</h6>
          <h6 class="card-subtitle mb-2 text-muted">Vacantes disponibles: ${curso.vacantes}</h6>

        </div>
        <div class="card-footer">
          <button type="button" class="btn btn-info">Editar Curso</button>
          <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#modalCursoEliminar${curso.id}">Eliminar Curso</button>
          <button type="button" class="btn btn-success">Agregar Alumno</button>

        </div>
        </div>
        </div>
    `;
        contenedorCursos.innerHTML += cardHTML;

        // Generar el HTML para cada modal
        const modalHTML = `
        <div class="modal fade" id="modalCursoEliminar${curso.id}" tabindex="-1" aria-labelledby="modalCursoEliminar${curso.id}Label" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCurso${curso.id}Label">${curso.nombre}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <b>Seguro desea eliminar el curso?</b><br><br>
                        <br><br>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="button" class="btn btn-danger">Si</button>
                    </div>
                </div>
            </div>
        </div>
    `;
        contenedorCursos.innerHTML += modalHTML; // Esto agregará el modal dentro de la misma sección
    });

}

async function cargarCursos() {
    try {
        const response = await fetch('Modulos/obtenerCursosAdmin.php');
        const data = await response.json();

        if (data.status === 'success') {
            mostrarCursos(data.cursos);
            const errorGlobal = document.getElementById('errorGlobal');
            limpiarErrores(errorGlobal, errorGlobal);

        } else {
            mostrarErrorGlobal(data.message || 'No se encontraron cursos disponibles');
            
        }
    } catch (error) {
        mostrarErrorGlobal(`Error en la conexión: ${error.message}`);
    }
}




//codigo para la creacion de los cursos
let botoncrearcurso = document.getElementById("botonCreacionCurso");
let modalcrearcurso = document.getElementById("myModalCreacionCurso");
let nocrearcurso = document.getElementById("cancelarCreacionCurso");



let form = document.getElementById("crearCurso");
form.addEventListener("submit",creaciondecurso);
botoncrearcurso.addEventListener("click",function(){modalcrearcurso.classList.add('mostrar');});
nocrearcurso.addEventListener("click",function(){modalcrearcurso.classList.remove("mostrar");});


async function creaciondecurso(e){
    e.preventDefault();

    let nombreC = document.getElementById("nombrecrear");
    let descripcionC = document.getElementById("textCrearCurso");
    let programaC = document.getElementById("programaCrearCurso");
    let fechaInicioC = document.getElementById("fechaInicioCrearCurso");
    let fechaFinC = document.getElementById("fechaFinCrearCurso");
    let imeganC = document.getElementById("archivoCrear");

    let cursoValido = true;

    if (nombreC.value.trim() === '') {
        console.log("El nombre no puede estar vacio");
        cursoValido = false;
    }

    if (descripcionC.value.trim() === '') {
        console.log("La descipcion no puede estar vacio");
        cursoValido = false;
    }

    if (programaC.value.trim() === '') {
        console.log("El programa de estudios no puede estar vacio");
        cursoValido = false;
    }

    if (fechaInicioC.value.trim() === '') {
        console.log("La fecha de inicio no puede estar vacio");
        cursoValido = false;
    }

    if (fechaFinC.value.trim() === '') {
        console.log("La fecha fin no puede estar vacio");
        cursoValido = false;
    }

    const fechaInicioCDate = new Date(fechaInicioC.value);
    const fechaFinCDate = new Date(fechaFinC.value);
    if (fechaInicioCDate > fechaFinCDate) {
        console.log("La fecha de inicio no puede ser despues de la de inicio");
        cursoValido = false;
    }

    if (imeganC.value.trim() === '') {
        console.log("Elija una imagen valida");
        cursoValido = false;
    }


    //subida de informacion
    if (cursoValido) {

        try {
            const datos = new FormData(e.target);
            const response = await fetch('Modulos/insertCurso.php', {
                method: 'POST',
                body: datos,
            });
            // Verificar si la respuesta es exitosa
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }

            const resultado = await response.json();

            if (resultado.status === 'success') {

                modalcrearcurso.classList.remove("mostrar");
                cargarCursos();
                limpiarCampos(nombreC);
                limpiarCampos(descripcionC);
                limpiarCampos(programaC);
                limpiarCampos(fechaInicioC);
                limpiarCampos(fechaFinC);
                limpiarCampos(imeganC);


            } else if (resultado.status === 'error') {
                // mostrarErrorGlobal(resultado.message);
                console.log("Fallo: ".resultado.message);
            }
        } catch (error) {
            // mostrarErrorGlobal(`Error en la conexión: ${error.message}`);
            console.error("Fallo:", error);
        }
    }

}


function limpiarCampos(objeto){
    objeto.value="";
}





















//codigo para la edicion de los cursos
let form2 = document.getElementById("editarCurso");
form2.addEventListener("submit",ediciondecurso);

function ediciondecurso(e){
    e.preventDefault();
    alert("se edito el curso");
}

//codigo para la eliminacion de los cursos
let form3 = document.getElementById("EliminarCurso");
form3.addEventListener("submit",eliminaciondecurso);

function eliminaciondecurso(e){
    e.preventDefault();
    alert("se elimino el curso");
}







window.onload = function() {
    const sections = document.querySelectorAll('section');

    mostrarMenus(sections);


    cargarCursos();
};