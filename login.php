<?php   
session_start();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Plataforma Educativa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/login.css">
    <script defer src="scripts/login.js"></script>
</head>
<body class="bg-light">
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <div id="login-card" class="card shadow p-4 text-center" style="width: 22rem;">
            <!-- Logo -->
            <img src="image/logo-educativo.png" alt="Logo" class="logo-navbar" >

<!-- ############################################ Formulario de Login ############################################### -->

            <h2 class="text-center mb-4">Iniciar Sesión</h2>
            <div id="errorGlobal" class="alert alert-danger d-none"></div>
            <form id="loginForm" method="POST">
                <div class="mb-3">
                    <label for="correo" class="form-label">Usuario</label>
                    <input type="text" class="form-control" id="correo" name="correo" placeholder="Ingrese su correo electrónico" >
                    <small id="nomUsuarioError" class="text-danger d-none">El campo usuario no puede estar vacío.</small>
                </div>
                <div class="mb-3">
                    <label for="clave" class="form-label">Contraseña</label>
                    <input type="password" class="form-control" id="clave" name="clave" placeholder="Ingrese su contraseña">
                    <small id="claveError" class="text-danger d-none">El campo contraseña no puede estar vacío.</small>
                </div>
                <button type="submit" class="btn btn-primary w-100">Ingresar</button>
                <!-- Simulación de autenticación en el backend -->
            </form>
            <div class="text-center mt-3">
                <a href="#" id="olvidoClaveLink">¿Olvidaste tu contraseña?</a><br>
                <a href="registro.html" class="mt-2">¿No tienes cuenta? Regístrate aquí</a>
            </div>
        </div>

<!-- ############################### Formulario de Recuperación de Contraseña ######################################### -->

        <div id="olvidoClave" class="card shadow p-4 text-center d-none" style="width: 22rem;">
            <img src="image/logo.jpg" alt="Logo de la plataforma" class="logo mb-3"><!-- Logo -->
            <img src="logo.jpg" alt="Logo de la plataforma" class="logo mb-3">
            <h1 class="mb-4">PORTAL EDUCATIVO</h1>

<!-- ######################################## Formulario de Recuperación ####################################################### -->
 
            <h2 class="text-center mb-4">Recuperar Contraseña</h2>
            <form id="olvidoClaveForm">
                <div class="mb-3">
                    <label for="email" class="form-label">Correo Electrónico</label>
                    <input type="email" class="form-control" id="email" placeholder="Ingrese su correo registrado" required>
                    <small id="email-error" class="text-danger d-none">Por favor ingrese un correo electrónico válido.</small>
                </div>
                <button type="submit" class="btn btn-primary w-100">Enviar Instrucciones</button>
            </form>
            <div class="text-center mt-3">
                <a href="#" id="volverLogin">Volver al Login</a>
            </div>
        </div>
    </div>
</body>
</html>
