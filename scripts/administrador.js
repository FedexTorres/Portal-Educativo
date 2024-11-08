
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
            console.log(resultado);

            if (resultado.status === 'success') {
                console.log("Exito");

                modalcrearcurso.classList.remove("mostrar");
                cargarCursos();


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


//codigo de Gestion de Usuarios//////////////////////////////////////////////////


const mymodal = document.getElementById("Crearusuario");
const nuevoUsuario = document.getElementById("botonCrearusuario");
const nonuevoUsuario = document.getElementById("cerrarmodalcrearuser");

let modaleliminar = document.getElementById("elimarusuariomodal");
let noeliminar = document.getElementById("NoEliminar");

nuevoUsuario.addEventListener("click", function(){mymodal.classList.add('mostrar');})
nonuevoUsuario.addEventListener("click", function(){mymodal.classList.remove("mostrar");})

noeliminar.addEventListener("click", function(){modaleliminar.classList.remove("mostrar");})

window.addEventListener("click", function(event) {
    if (event.target === mymodal) {
        mymodal.classList.remove("mostrar"); // Ocultar el modal si haces clic fuera
    }
    else if (event.target === modaleliminar){
        modaleliminar.classList.remove("mostrar");
    }
    else if(event.target === modalcrearcurso){
        modalcrearcurso.classList.remove("mostrar");
    }
});


async function traerUsuarios() {
    
    let url = 'modulos/getUsuarios.php';
    
    try {
        let response = await fetch(url, {
            method: 'post', 
        });
        procesarInformacion(await response.json());
        //console.log(await response.json());
    } catch (error) {
        console.log('FALLO FETCH!');
        console.log(error);
    }
}

const procesarInformacion = function(data) {

    let elTabla = document.getElementById('tablaUsuarios');

    if (data.length == 0) {
        // mostrar warning
        console.log("error de algo");
    } else {
        // mostrar tabla
        let elTBody = elTabla.querySelector('tbody');
        elTBody.innerHTML = '';
        for (let user of data) {
            let newElTr = document.createElement('tr');
            newElTr.innerHTML = '<td>'+user.nombre+'</td>';
            newElTr.innerHTML += '<td>'+user.correo+'</td>';
            newElTr.innerHTML += '<td>'+user.rol+'</td>';
            newElTr.innerHTML += '<td><button type="button" value="'+user.id+'" class="btn btn-info">Editar</button>       <button type="button" id="'+user.nombre+'" value="'+user.id+'" class="btn btn-danger btn-Eliminar-Usuario">Eliminar</button></td>';

            elTBody.appendChild(newElTr);

        }
        asignarbotones();
    }
}

//Funciones para crear usuarios nuevos

function mostrarError(campo, mensajeElemento, mensaje) {
    campo.classList.add('is-invalid'); // Añadir la clase de error al campo
    mensajeElemento.innerHTML = mensaje; // Mostrar el mensaje de error
}

function limpiarErrores(campo, mensajeElemento) {
    campo.classList.remove('is-invalid');
    mensajeElemento.innerHTML = "";
}

async function UsurioNuevo(e){
    e.preventDefault();
    
    // Obtener los elementos de DOM y sus valores
    const nombre = document.getElementById('UserNewNombre');
    const apellido = document.getElementById('UserNewApellido');
    const correo = document.getElementById('UserNewEmail');
    const fechaNacimiento = document.getElementById('UserNewFecha');
    const contraseña = document.getElementById('UserNewContra');
    const confirmPassword = document.getElementById('UserNewContra2');

    let tipo = document.getElementById("UserNewTipo");

    const errorNombre = document.getElementById('errorNombre');
    const errorApellido = document.getElementById('errorApellido');
    const errorEmail = document.getElementById('errorEmail');
    const errorFecha = document.getElementById('errorFecha');
    const errorContra = document.getElementById('errorContra');
    const errorContra2 = document.getElementById('errorContra2');


    // Limpiar mensajes de error previos
    limpiarErrores(nombre, errorNombre);
    limpiarErrores(apellido, errorApellido);
    limpiarErrores(correo, errorEmail);
    limpiarErrores(fechaNacimiento, errorFecha);
    limpiarErrores(contraseña, errorContra);
    limpiarErrores(confirmPassword, errorContra2);
    limpiarErrores(tipo, errorTipo);

    // Variables de validación
    let esValido = true;

    // Validaciones de cada campo
    const regexNombreApellido = /^[a-zA-ZáéíóúÁÉÍÓÚ ]+$/;
    if (nombre.value.trim() === '') {
        mostrarError(nombre, errorNombre, "El campo 'Nombre' no puede estar vacío.");
        esValido = false;
    } else if (!regexNombreApellido.test(nombre.value.trim())) {
        mostrarError(nombre, errorNombre, "El nombre solo debe contener letras y espacios.");
        esValido = false;
    }

    if (apellido.value.trim() === '') {
        mostrarError(apellido, errorApellido, "El campo 'Apellido' no puede estar vacío.");
        esValido = false;
    } else if (!regexNombreApellido.test(apellido.value.trim())) {
        mostrarError(apellido, errorApellido, "El apellido solo debe contener letras y espacios.");
        esValido = false;
    }

    const regexCorreo = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (correo.value.trim() === '') {
        mostrarError(correo, errorEmail, "El campo 'Correo' no puede estar vacío.");
        esValido = false;
    } else if (!regexCorreo.test(correo.value.trim())) {
        mostrarError(correo, errorEmail, "El correo no es válido.");
        esValido = false;
    }

    // Validación de fecha de nacimiento
    if (fechaNacimiento.value.trim() === '') {
        mostrarError(fechaNacimiento, errorFecha, "El campo 'Fecha de Nacimiento' no puede estar vacío.");
        esValido = false;
    } else {
        const hoy = new Date();
        const fechaNacimientoDate = new Date(fechaNacimiento.value);
        if (fechaNacimientoDate > hoy) {
            mostrarError(fechaNacimiento, errorFecha, "<b>La fecha de nacimiento no puede ser en el futuro</b>");
            esValido = false;
        } else {
            // Validar que el usuario tenga al menos 16 años
            const edadMinima = 16;
            const diferencia = hoy.getFullYear() - fechaNacimientoDate.getFullYear();
            const mesDiferencia = hoy.getMonth() - fechaNacimientoDate.getMonth();
            const diaDiferencia = hoy.getDate() - fechaNacimientoDate.getDate();

            if (diferencia < edadMinima || (diferencia === edadMinima && (mesDiferencia < 0 || (mesDiferencia === 0 && diaDiferencia < 0)))) {
                mostrarError(fechaNacimiento, errorFecha, "Debes tener al menos 16 años.");
                esValido = false;
            }
        }
    }
    // Validación de contraseña
    if (contraseña.value.trim() === '') {
        mostrarError(contraseña, errorContra, 'La contraseña no puede estar vacía.');
        esValido = false;
    } else if (contraseña.value.length < 4) {
        mostrarError(contraseña, errorContra, 'La contraseña debe tener al menos 4 caracteres.');
        esValido = false;
    }

    // Confirmar que las contraseñas coinciden
    if (confirmPassword.value.trim() === '') {
        mostrarError(confirmPassword, errorContra2, 'Debes confirmar tu contraseña.');
        esValido = false;
    } else if (contraseña.value !== confirmPassword.value) {
        mostrarError(confirmPassword, errorContra2, "Las contraseñas no coinciden.");
        esValido = false;
    }

    // confirmar tipo de usuario
    if (tipo.value != 1){
        if (tipo.value != 2) {
            if (tipo.value != 3) {
                console.log("algo fallo");
                console.log(tipo.value);
                esValido = false;
                mostrarError(tipo, errorTipo, "Tiene que ser un tipo valido de usuario");

            }
        }
    }


    // Si es válido, enviar datos al servidor
    if (esValido) {
        //alert("Registro exitoso. Bienvenido a la plataforma educativa!");
        try {
            const datos = new FormData(e.target);
            const response = await fetch('Modulos/insertUsuarios.php', {
                method: 'POST',
                body: datos,
            });
            // Verificar si la respuesta es exitosa
            if (!response.ok) {
                throw new Error('Error en la respuesta del servidor');
            }

            const resultado = await response.json();
            console.log(resultado);

            if (resultado.status === 'success') {
                // mostrarMensajeExito("Registro exitoso. Redirigiendo...");
                // setTimeout(() => {
                //     window.location.href = './index-login.html';
                // }, 2500);
                console.log("Exito");

                mymodal.classList.remove("mostrar");
                traerUsuarios();

            } else if (resultado.status === 'error') {
                // mostrarErrorGlobal(resultado.message);
                console.log("Fallo: ".resultado.message);
            }
        } catch (error) {
            // mostrarErrorGlobal(`Error en la conexión: ${error.message}`);
            console.log("Fallo: ".error.message);
        }
    }

}



function asignarbotones(){
    document.querySelectorAll('.btn-Eliminar-Usuario').forEach(boton => {
        boton.addEventListener('click', function () {
            const userAeliminar = this.value;
    
            modaleliminar.classList.add("mostrar");
            document.getElementById("eluserchau").innerHTML = this.id;
            document.getElementById("AdiosUser").value = userAeliminar;
        })
    })
}




async function eliminarUsuario(id){
    
    let elid = id.target.value;
    try {
        const datos = elid;
        const url = "Modulos/deletUsuarios.php?id=" + datos;
        const response = await fetch(url, {
            method: 'GET',          
        });
        // Verificar si la respuesta es exitosa
        if (!response.ok) {
            throw new Error('Error en la respuesta del servidor');
        }

        const resultado = await response.json();

        if (resultado.status === 'success') {

            modaleliminar.classList.remove("mostrar");
            traerUsuarios();
            alert("Usuario eliminado");


        } else if (resultado.status === 'error') {
            console.log("Fallo: ".resultado.message);
        }
    } catch (error) {
        console.log(error.message);
    }
}


window.onload = function() {
    const sections = document.querySelectorAll('section');

    mostrarMenus(sections);

    traerUsuarios();

    document.getElementById("CrearUsuarioForm").addEventListener('submit', UsurioNuevo);

    let numUser = document.getElementById("AdiosUser");
    numUser.addEventListener("click",eliminarUsuario);

    cargarCursos();
};