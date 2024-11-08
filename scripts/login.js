// Validar el formulario de login
async function validarLogin(e) {
    e.preventDefault();
    let isValid = true;

    // Validar usuario
    const username = document.getElementById('correo').value;
    const nomUsuarioError = document.getElementById('nomUsuarioError');
    if (username === '') {
        nomUsuarioError.classList.remove('d-none');
        isValid = false;
    } else {
        nomUsuarioError.classList.add('d-none');
    }

    // Validar contraseña
    const clave = document.getElementById('clave').value;
    const claveError = document.getElementById('claveError');
    if (clave === '' || /[^\w\s]/.test(clave)) {
        claveError.classList.remove('d-none');
        isValid = false;
    } else {
        claveError.classList.add('d-none');
    }

    if (isValid) {
        try {
            // Construimos los datos para enviar
            const formulario = document.getElementById('loginForm');
            const datos = new FormData(formulario);
            const response = await fetch('Modulos/validarLogin.php', {
                method: 'POST',
                body: datos,
            });

            const resultado = await response.json(); // Procesamos la respuesta como JSON

            if (resultado.status === 'success') {
                //alert("login exitoso");
                // Si el login es exitoso, redirigir a la URL específica según el rol
                window.location.href = resultado.data.redirect; // Usamos la URL recibida del servidor
            } else {
                mostrarErrorGlobal(resultado.message); // Mostrar el mensaje de error en caso de fallo
            }
        } catch (error) {
            console.log('Error en la petición:', error);
            mostrarErrorGlobal('Ocurrió un problema al iniciar sesión.');
        }
    }
}

// Funciones para mostrar errores globales
function mostrarErrorGlobal(mensaje) {
    const errorGlobal = document.getElementById('errorGlobal');
    errorGlobal.classList.remove('d-none');
    errorGlobal.innerHTML = mensaje;
}

// Mostrar formulario de recuperación de contraseña
function mostrarRecuperacionContrasena() {
    document.getElementById('login-card').classList.add('d-none');
    document.getElementById('olvidoClave').classList.remove('d-none');
}

// Volver al formulario de login desde recuperación de contraseña
function volverALogin() {
    document.getElementById('olvidoClave').classList.add('d-none');
    document.getElementById('login-card').classList.remove('d-none');
}

// Validar el formulario de recuperación de contraseña
function validarRecuperacionContrasena(e) {
    e.preventDefault();
    const email = document.getElementById('email').value;
    const emailError = document.getElementById('email-error');

    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        emailError.classList.remove('d-none');
    } else {
        emailError.classList.add('d-none');
        alert(`Se han enviado las instrucciones de recuperación a: ${email}`);
    }
}

// Manejar eventos de la página
function manejarEventosLogin() {
    // Validar login
    document.getElementById('loginForm').addEventListener('submit', validarLogin);

    // Mostrar formulario de recuperación de contraseña
    document.getElementById('olvidoClaveLink').addEventListener('click', (e) => {
        e.preventDefault();
        mostrarRecuperacionContrasena();
    });

    // Volver al login desde la recuperación
    document.getElementById('volverLogin').addEventListener('click', (e) => {
        e.preventDefault();
        volverALogin();
    });

    // Validar recuperación de contraseña
    document.getElementById('olvidoClaveForm').addEventListener('submit', validarRecuperacionContrasena);
}

// Inicializar eventos al cargar la página
document.addEventListener('DOMContentLoaded', manejarEventosLogin);
