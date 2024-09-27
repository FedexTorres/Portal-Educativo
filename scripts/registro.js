
function validarFormularioRegistro(e) {
    e.preventDefault(); // Prevenir el envío del formulario

    errorNombre.innerHTML = ""; // Limpiar mensajes anteriores
    errorApellido.innerHTML = ""; // Limpiar mensajes anteriores
    errorCorreo.innerHTML = ""; // Limpiar mensajes anteriores
    errorFecha.innerHTML = ""; // Limpiar mensajes anteriores
    errorClave.innerHTML = ""; // Limpiar mensajes anteriores

    let esValido = true; // Bandera para indicar si el formulario es válido o no

    // Validación de nombre
    const nombre = document.getElementById('nombre').value.trim();
    const regexNombreApellido = /^[a-zA-ZáéíóúÁÉÍÓÚ ]+$/;
    if (!regexNombreApellido.test(nombre)) {
        errorNombre.innerHTML = "<b>El nombre solo debe contener letras y espacios</b>";
        esValido = false;
    }

    // Validación de apellido
    const apellido = document.getElementById('apellido').value.trim();
    if (!regexNombreApellido.test(apellido)) {
        errorApellido.innerHTML = "<b>El apellido solo debe contener letras y espacios</b>";
        esValido = false;
    }

    // Validación de correo
    //No se muestra el mensaje de error ya que en el html se puso type=email, si se cambia a text, si se muestra el error.

    const correo = document.getElementById('correo').value.trim();
    const regexCorreo = /^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
    if (!regexCorreo.test(correo)) {
        errorCorreo.innerHTML = "<b>El correo no es válido</b>";
        esValido = false;
    }

    // Validación de fecha de nacimiento
    const fechaNacimiento = document.getElementById('fechaNacimiento').value;
    const hoy = new Date();
    const fechaNacimientoDate = new Date(fechaNacimiento);

    if (fechaNacimientoDate > hoy) {
        errorFecha.innerHTML = "<b>La fecha de nacimiento no puede ser en el futuro</b>";
        esValido = false;
    } else {
        // Validar que el usuario tenga al menos 18 años
        const edadMinima = 16;
        const diferencia = hoy.getFullYear() - fechaNacimientoDate.getFullYear();
        const mesDiferencia = hoy.getMonth() - fechaNacimientoDate.getMonth();
        const diaDiferencia = hoy.getDate() - fechaNacimientoDate.getDate();

        if (diferencia < edadMinima || (diferencia === edadMinima && (mesDiferencia < 0 || (mesDiferencia === 0 && diaDiferencia < 0)))) {
            errorFecha.innerHTML = "<b>Debes tener al menos 16 años</b>";
            esValido = false;
        }
    }

    // Validación de contraseñas
    const contraseña = document.getElementById('clave').value;
    const confirmPassword = document.getElementById('confirmClave').value;

    if (contraseña !== confirmPassword) {
        errorClave.innerHTML = "<b>Las contraseñas no coinciden</b>";
        esValido = false;
    }

    // Mostrar alerta si todo es válido
    if (esValido) {
        alert("Registro exitoso. Bienvenido a la plataforma educativa!");
        // Aquí podríamos agregar código para enviar los datos al servidor o guardarlos en la base de datos simulada
    }
}


function inicio() {
    document.getElementById('registroForm').addEventListener('submit', validarFormularioRegistro);
}

window.onload = inicio;
