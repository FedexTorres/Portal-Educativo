// Función para mostrar errores en campos específicos
function mostrarError(campo, mensajeElemento, mensaje) {
    campo.classList.add('is-invalid'); // Añadir la clase de error al campo
    mensajeElemento.innerHTML = mensaje; // Mostrar el mensaje de error
}

// Función para mostrar errores globales
function mostrarErrorGlobal(mensaje) {
    const errorGlobal = document.getElementById('errorGlobal');
    errorGlobal.classList.remove('d-none'); // Muestra el contenedor de error

    // Verificamos si `mensaje` es un array
    if (Array.isArray(mensaje)) {
        // Si es un array, construimos la lista de errores
        const listaErrores = mensaje.map(error => `<li>${error}</li>`).join('');
        errorGlobal.innerHTML = `<ul>${listaErrores}</ul>`;
    } else {
        // Si es una cadena de texto, la mostramos directamente
        errorGlobal.innerHTML = `<p>${mensaje}</p>`;
    }
}

// Función para limpiar mensajes de error
function limpiarErrores(campo, mensajeElemento) {
    campo.classList.remove('is-invalid');
    mensajeElemento.innerHTML = '';
}

// Función para mostrar mensajes de éxito
function mostrarMensajeExito(mensaje) {
    const mensajeExito = document.getElementById('mensajeExito');
    mensajeExito.classList.remove('d-none');
    mensajeExito.innerHTML = mensaje;
}

// Función principal para validar el formulario de registro
async function validarFormularioRegistro(e) {
    e.preventDefault(); // Prevenir el envío del formulario
    // Obtener los elementos de DOM y sus valores
    const nombre = document.getElementById('nombre');
    const apellido = document.getElementById('apellido');
    const correo = document.getElementById('correo');
    const fechaNacimiento = document.getElementById('fechaNacimiento');
    const contraseña = document.getElementById('clave');
    const confirmPassword = document.getElementById('confirmClave');

    // Limpiar mensajes de error previos
    limpiarErrores(nombre, errorNombre);
    limpiarErrores(apellido, errorApellido);
    limpiarErrores(correo, errorCorreo);
    limpiarErrores(fechaNacimiento, errorFecha);
    limpiarErrores(contraseña, errorRegistroClave);
    limpiarErrores(confirmPassword, errorClave);

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
        mostrarError(correo, errorCorreo, "El campo 'Correo' no puede estar vacío.");
        esValido = false;
    } else if (!regexCorreo.test(correo.value.trim())) {
        mostrarError(correo, errorCorreo, "El correo no es válido.");
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
        mostrarError(contraseña, errorRegistroClave, 'La contraseña no puede estar vacía.');
        esValido = false;
    } else if (contraseña.value.length < 4) {
        mostrarError(contraseña, errorRegistroClave, 'La contraseña debe tener al menos 4 caracteres.');
        esValido = false;
    }

    // Confirmar que las contraseñas coinciden
    if (confirmPassword.value.trim() === '') {
        mostrarError(confirmPassword, errorClave, 'Debes confirmar tu contraseña.');
        esValido = false;
    } else if (contraseña.value !== confirmPassword.value) {
        mostrarError(confirmPassword, errorClave, "Las contraseñas no coinciden.");
        esValido = false;
    }
    // Si es válido, enviar datos al servidor
    if (esValido) {
        try {
            const datos = new FormData(e.target);
            const response = await fetch('Modulos/registro_Estudiante.php', {
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
                mostrarMensajeExito("Registro exitoso. Redirigiendo...");
                setTimeout(() => {
                    window.location.href = './login.php';
                }, 2500);
            } else if (resultado.status === 'error') {
                mostrarErrorGlobal(resultado.message);
            }
        } catch (error) {
            mostrarErrorGlobal(`Error en la conexión: ${error.message}`);
        }
    }
}
// Inicializar la validación al cargar la página
function inicio() {
    document.getElementById('registroForm').addEventListener('submit', validarFormularioRegistro);
}

document.addEventListener('DOMContentLoaded', inicio);

