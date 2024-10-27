// Validar el formulario de login
function validarLogin(e) {
    e.preventDefault();
    let isValid = true;

    // Validar usuario
    const username = document.getElementById('nombreUsuario').value;
    const nomUsuarioError = document.getElementById('nomUsuarioError');
    if (username === '' || !/^[a-zA-Z0-9]+$/.test(username)) {
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
        alert('Formulario válido. Enviando a la base de datos para autenticación.');
    }
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
window.onload = manejarEventosLogin;
