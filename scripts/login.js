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

// Funciones para mostrar errores globales
function mostrarMensajeGlobal(tipo, mensaje) {
    const mensajeGlobal = document.getElementById('mensajeGlobal');
    mensajeGlobal.classList.remove('d-none', 'alert-danger', 'alert-success');
    mensajeGlobal.classList.add(`alert-${tipo}`);
    mensajeGlobal.innerHTML = `
        <span>${mensaje}</span>
         <button type="button" class="btn btn-${tipo} d-block mx-auto mt-3" aria-label="Close" onclick="cerrarMensajeGlobal()">
            <i class="bi bi-x-lg"></i> Cerrar
        </button>
    `;
}

function cerrarMensajeGlobal() {
    alternarFormulario('resetPassword');
    const mensajeGlobal = document.getElementById('mensajeGlobal');
    mensajeGlobal.classList.add('d-none');
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
async function recuperarContrasena(e) {
    e.preventDefault();
    const email = document.getElementById('email').value;
    const emailError = document.getElementById('email-error');

    if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
        emailError.classList.remove('d-none');
    } else {
        emailError.classList.add('d-none');
        try {
            const response = await fetch('Modulos/recuperarContrasena.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ email })
            });

            const result = await response.json();
            if (result.status === 'success') {
                mostrarMensajeGlobal('success', `
                    <strong>Copie este token y cierre el mensaje:</strong><br>
                    <span class="token">${result.token}</span>
                `);
            } else {
                mostrarMensajeGlobal('danger', result.message);
            }
        } catch (error) {
            console.error('Error:', error);
            mostrarMensajeGlobal('danger', 'Ocurrió un problema al procesar la solicitud.');
        }
    }
}

async function restablecerContrasena(e) {
    e.preventDefault();

    const token = document.getElementById('token').value;
    const newPassword = document.getElementById('newPassword').value;
    const confirmPassword = document.getElementById('confirmPassword').value;

    const tokenError = document.getElementById('token-error');
    const newPasswordError = document.getElementById('newPassword-error');
    const confirmPasswordError = document.getElementById('confirmPassword-error');

    let isValid = true;

    // Validar token
    if (token === '') {
        tokenError.classList.remove('d-none');
        isValid = false;
    } else {
        tokenError.classList.add('d-none');
    }

    // Validar nueva contraseña
    if (newPassword.length < 4) {
        newPasswordError.classList.remove('d-none');
        isValid = false;
    } else {
        newPasswordError.classList.add('d-none');
    }

    // Validar confirmación de contraseña
    if (newPassword !== confirmPassword) {
        confirmPasswordError.classList.remove('d-none');
        isValid = false;
    } else {
        confirmPasswordError.classList.add('d-none');
    }

    if (isValid) {
        try {
            const response = await fetch('Modulos/restablecerContrasena.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ token, newPassword, confirmPassword })
            });

            const result = await response.json();
            if (result.status === 'success') {
                mostrarMjeGlobal('success', result.message);
            } else {
                mostrarMjeGlobal('danger', result.message);
            }
        } catch (error) {
            console.error('Error:', error);
            mostrarMensajeGlobal('danger', 'Ocurrió un problema al procesar la solicitud.');
        }
    }
}

function mostrarMjeGlobal(tipo, mensaje) {
    const mensajeGlobal = document.getElementById('mjeGlobal');
    mensajeGlobal.classList.remove('d-none', 'alert-danger', 'alert-success');
    mensajeGlobal.classList.add(`alert-${tipo}`);
    mensajeGlobal.innerHTML = `
        <span>${mensaje}</span>
    `;
    // Mostrar la leyenda de redirección después del mensaje
    setTimeout(() => {
        mensajeGlobal.innerHTML = `
            <span>Redirigiendo al login...</span>
        `;
        setTimeout(() => {
            // Redirigir al login después de 2 segundos
            alternarFormulario('login-card');
        }, 2000); // Este es el tiempo antes de redirigir
    }, 2000); // Este es el tiempo del mensaje de éxito antes de mostrar la leyenda "redirigiendo"
}


function alternarFormulario(formularioAMostrar) {
    const formularios = ['login-card', 'olvidoClave', 'resetPassword'];
    formularios.forEach((formulario) => {
        const form = document.getElementById(formulario);
        if (formulario === formularioAMostrar) {
            form.classList.remove('d-none');
        } else {
            form.classList.add('d-none');
        }
    });
}


// Manejar eventos de la página
function manejarEventosLogin() {
    // Eventos para los formularios
    document.getElementById('olvidoClaveForm').addEventListener('submit', recuperarContrasena);
    document.getElementById('resetPasswordForm').addEventListener('submit', restablecerContrasena);
    document.getElementById('loginForm').addEventListener('submit', validarLogin);

    // Navegación entre formularios
    document.getElementById('olvidoClaveLink').addEventListener('click', (e) => {
        e.preventDefault();
        alternarFormulario('olvidoClave');
    });

    document.getElementById('volverLogin').addEventListener('click', (e) => {
        e.preventDefault();
        alternarFormulario('login-card');
    });

    document.getElementById('volverLoginDesdeReset').addEventListener('click', (e) => {
        e.preventDefault();
        alternarFormulario('login-card');
    });
}

// Inicializar eventos al cargar la página
document.addEventListener('DOMContentLoaded', manejarEventosLogin);