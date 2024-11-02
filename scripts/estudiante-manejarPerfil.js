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
    document.getElementById('form-perfil').addEventListener('submit', manejarPerfil); // Maneja la sección de perfil

}

window.onload = inicio;