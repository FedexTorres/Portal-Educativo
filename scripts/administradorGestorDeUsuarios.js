const mymodal = document.getElementById("Crearusuario");
const nuevoUsuario = document.getElementById("botonCrearusuario");
let esCreacion = true;
let idModificar = "";

const nonuevoUsuario = document.getElementById("cerrarmodalcrearuser");

let modaleliminar = document.getElementById("elimarusuariomodal");
let noeliminar = document.getElementById("NoEliminar");

let errorDiv = document.getElementById('errorGlobalAdmin');
let errorDivUser = document.getElementById('errorUsuarios');


nuevoUsuario.addEventListener("click", function(){
    mymodal.classList.add('mostrar');
    esCreacion = true;
    //limpiarCampos()

    limpiarCampos(document.getElementById('UserNewNombre'));
    limpiarCampos(document.getElementById('UserNewApellido'));
    limpiarCampos(document.getElementById('UserNewEmail'));
    limpiarCampos(document.getElementById('UserNewFecha'));
    limpiarCampos(document.getElementById('UserNewContra'));
    limpiarCampos(document.getElementById('UserNewContra2'));

    document.getElementById("tituloFormUsuarios").innerHTML="Crear Usuario";
})




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

    } catch (error) {

        errorDiv.textContent = error.message; // Asigna el mensaje de error
        errorDiv.classList.remove('d-none'); // Muestra el div eliminando la clase d-none
        errorDiv.classList.add('alert', 'alert-danger');
        
    }
}

const procesarInformacion = function(data) {

    let elTabla = document.getElementById('tablaUsuarios');
    if (data.status === 'error') {
        // mostrar warning
        errorDiv.textContent = data.message; // Asigna el mensaje de error
        errorDiv.classList.remove('d-none'); // Muestra el div eliminando la clase d-none
        errorDiv.classList.add('alert', 'alert-danger');
    } else {
        // mostrar tabla
        let elTBody = elTabla.querySelector('tbody');
        elTBody.innerHTML = '';
        for (let user of data) {
            let newElTr = document.createElement('tr');
            newElTr.innerHTML = '<td>'+user.nombre+'</td>';
            newElTr.innerHTML += '<td>'+user.correo+'</td>';
            newElTr.innerHTML += '<td>'+user.rol+'</td>';

            newElTr.innerHTML += '<td><button type="button" value="'+user.id+'" class="btn btn-info btn-Editar-Usuario">Editar</button>       <button type="button" id="'+user.nombre+'" value="'+user.id+'" class="btn btn-danger btn-Eliminar-Usuario">Eliminar</button></td>';

            elTBody.appendChild(newElTr);
        }
        asignarbotones();
    };
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


        if (esCreacion) {
            try {
                const datos = new FormData(e.target);
                const response = await fetch('Modulos/insertUsuarios.php', {
                    method: 'POST',
                    body: datos,
                });
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
    
                const resultado = await response.json();
                console.log(resultado);
    
                if (resultado.status === 'success') {
                    
                    mymodal.classList.remove("mostrar");
                    traerUsuarios();
    
                } else if (resultado.status === 'error') {

                    errorDivUser.textContent = resultado.message; // Asigna el mensaje de error
                    errorDivUser.classList.remove('d-none'); // Muestra el div eliminando la clase d-none
                    errorDivUser.classList.add('alert', 'alert-danger');

                }
            } catch (error) {
                
                errorDivUser.textContent = error.message; // Asigna el mensaje de error
                errorDivUser.classList.remove('d-none'); // Muestra el div eliminando la clase d-none
                errorDivUser.classList.add('alert', 'alert-danger');
            }
    
        } else{
            try {
                const datos = new FormData(e.target);
                datos.append("userId", idModificar);
                const response = await fetch('Modulos/editarUsuario.php', {
                    method: 'POST',
                    body: datos,
                });
                if (!response.ok) {
                    throw new Error('Error en la respuesta del servidor');
                }
    
                const resultado = await response.json();
    
                if (resultado.status === 'success') {
                    
                    mymodal.classList.remove("mostrar");
                    traerUsuarios();
    
                } else if (resultado.status === 'error') {

                    errorDivUser.textContent = resultado.message; // Asigna el mensaje de error
                    errorDivUser.classList.remove('d-none'); // Muestra el div eliminando la clase d-none
                    errorDivUser.classList.add('alert', 'alert-danger');

                }
            } catch (error) {
                errorDivUser.textContent = error.message; // Asigna el mensaje de error
                errorDivUser.classList.remove('d-none'); // Muestra el div eliminando la clase d-none
                errorDivUser.classList.add('alert', 'alert-danger');
            }
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

    document.querySelectorAll('.btn-Editar-Usuario').forEach(boton => {
        boton.addEventListener('click', function () {
            esCreacion=false;
            TraerDatosUsuarioModificar(this.value);    
            mymodal.classList.add('mostrar');
            document.getElementById("tituloFormUsuarios").innerHTML="Editar Usuario";
            idModificar=this.value;
        })
    })

}

async function TraerDatosUsuarioModificar(id){

    const formData = new FormData();
    formData.append("userId", id);
    let url = 'modulos/getUsuarioAhEditar.php';
    
    try {
        let response = await fetch(url, {
            method: 'post',
            body: formData, 
        });
        traerDatosAlForm(await response.json());
    } catch (error) {
        console.log('FALLO FETCH!');
        console.log(error);
    }
}

function traerDatosAlForm(datos){
    
    let nombre = document.getElementById('UserNewNombre');
    let apellido = document.getElementById('UserNewApellido');
    let correo = document.getElementById('UserNewEmail');
    let fechaNacimiento = document.getElementById('UserNewFecha');
    let contraseña = document.getElementById('UserNewContra');
    let confirmPassword = document.getElementById('UserNewContra2');

    if (datos.length == 0) {
        // mostrar warning
        console.log("error de algo");
    } else {
        nombre.value="";
        apellido.value="";
        correo.value="";
        fechaNacimiento.value="";
        contraseña.value="";
        confirmPassword.value="";
        for (let user of datos) {
            nombre.value=user.nombre;
            apellido.value=user.apellido;
            correo.value=user.correo;
            fechaNacimiento.value=user.fecha_nacimiento;
        }
    }
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
            errorDivUser.textContent = resultado.message; // Asigna el mensaje de error
            errorDivUser.classList.remove('d-none'); // Muestra el div eliminando la clase d-none
            errorDivUser.classList.add('alert', 'alert-danger');

        }
    } catch (error) {
        errorDivUser.textContent = error.message; // Asigna el mensaje de error
        errorDivUser.classList.remove('d-none'); // Muestra el div eliminando la clase d-none
        errorDivUser.classList.add('alert', 'alert-danger');
    }
}

async function obtenerRoles(){
    let url = 'modulos/getRoles.php';
    
    try {
        let response = await fetch(url, {
            method: 'post', 
        });
        procesarRoles(await response.json());
    } catch (error) {
        console.log('FALLO FETCH!');
        console.log(error);
    }
}

function procesarRoles(data){
    let roles = document.getElementById('UserNewTipo');

    if (data.length == 0) {
        // mostrar warning
        console.log("error de algo");
    } else {
        
        for (let role of data) {
            let op = document.createElement('option');
            op.innerHTML = role.nombre;
            op.value=role.id;
            roles.appendChild(op);

        }
    }
}



$(document).ready(function () {
    traerUsuarios();
    document.getElementById("CrearUsuarioForm").addEventListener('submit', UsurioNuevo);

    let numUser = document.getElementById("AdiosUser");
    numUser.addEventListener("click",eliminarUsuario);

    obtenerRoles();
});