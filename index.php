<?php
session_start();
// Incluimos el navbar-base que cargará el navbar-logueado o navbar-visitante según la sesión
include 'navbar-base.php';
require_once ('Modulos/permisos.php');
?>

<!doctype html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Plataforma Educativa</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="css/index.css">
    <link rel="stylesheet" href="css/scroll_mensajes.css">
    <script defer src="scripts/estudiante-misCursos.js"></script>
    <script defer src="scripts/estudiante-menus.js"></script>
    <script defer src="scripts/editarPerfil.js"></script>
    <script defer src="scripts/mensajes.js"></script>
    <script defer src="scripts/cargarCursos.js"></script>
    <script defer src="scripts/inscripcionCurso.js"></script>  
</head>

<!-- ############################################# MENU LATERAL IZQUIERDO ############################################# -->

<body class="text-dark">
<div class="container-fluid">
    <div class="row">
      <nav class="col-md-2 d d-md-block bg-light">
        <div class="card-body bg-light text-dark border-0">
          <div class="card-body">
            <br>
            <br>
            <!-- Menú lateral solo visible si el usuario está logueado como Estudiante -->
            
            <?php if (isset($_SESSION['usuario']) && Permisos::tienePermiso('Ver menu estudiante', $_SESSION['usuario']['id'])) { ?>
              <!-- Menú lateral solo se muestra si el usuario es Estudiante -->
              <ul class="nav flex-column">
                <li class="nav-item mb-2">
                  <button id="inicio-btn" class="btn btn-menu">Inicio</button>
                </li>
                <li class="nav-item mb-2">
                  <button id="mis-cursos-btn" class="btn btn-menu">Mis Cursos</button>
                </li>
                <li class="nav-item mb-2">
                  <button id="mensajes-btn" class="btn btn-menu">Mensajes</button>
                </li>
                <li class="nav-item mb-2">
                  <button id="perfil-btn" class="btn btn-menu">Editar Perfil</button>
                </li>
              </ul>
              <?php } ?>
            </div>
          </div>
        </nav>
<!-- ############################################# AREA PRINCIPAL ############################################# -->

  <section id="seccion-inicio"  class="col-md-10 ms-sm-auto col-lg-10 px-4 bg-light">
    <h1 class="my-4 titulo">Cursos Disponibles</h1>
    <hr>
    <!-- Carrusel -->
    <div id="carouselExample" class="carousel slide mb-4" data-bs-ride="carousel">
      <div class="carousel-inner">
        <div class="carousel-item active">
          <img src="image/curso1.jpg" class="d-block w-100 carousel-image" alt="Curso 1">
        </div>
        <div class="carousel-item">
          <img src="image/curso2.jpg" class="d-block w-100 carousel-image" alt="Curso 2">
        </div>
        <div class="carousel-item">
          <img src="image/curso3.jpg" class="d-block w-100 carousel-image" alt="Curso 3">
        </div>
      </div>
      <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
        <span class="carousel-control-prev-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Anterior</span>
      </button>
      <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Siguiente</span>
      </button>
    </div>
    <!-- Cards de cursos -->
    <div class="row" id="courses-section">

        <!-- Aqui se cargan automaticamete lasCards de cursos desde la bbdd -->

    </div>
  </section>

<!-- ############################################# SECCION MIS CURSOS #############################################-->

    <section id="seccion-mis-cursos" class="col-md-10 ms-sm-auto col-lg-10 px-4 bg-light seccion d-none">
      <h1 class="my-4 titulo">Mis Cursos</h1>
      <hr>
      <h3 class="my-4 titulo">Lista de cursos en los que estás inscripto</h3>
      <div id="contenido-scroll" class="contenido-scroll">
         <!-- <p>Lista de cursos en los que estás inscrito.</p> -->
    </div>
     

    </section>

  <!-- ############################################# SECCION DE MENSAJES ############################################# -->

  <section id="seccion-mensajes" class="col-md-10 ms-sm-auto col-lg-10 px-4 bg-light seccion d-none">
  <h1 class="my-4 titulo">Mensajes</h1>
  <hr>
  <div id="alerta" class="alert alert-danger d-none" role="alert"></div>
  <div id="exito" class="alert alert-success d-none"></div>
  <div id="errorEnvio" class="d-none"></div> 

  
  <div class="row">
      <!-- Panel para Enviar Mensajes -->
      <div class="col-md-12">
        <h2>Enviar Mensaje</h2>
        <form action="" method="POST" id="form-enviar-mensaje">
          <div class="mb-3">
              <label for="destinatario" class="form-label">Destinatario</label>
              <input type="text" class="form-control" id="destinatario" name="destinatario" placeholder="Ingrese el nombre del destinatario" >
              <!-- Aquí se agrega la lista de destinatarios sugeridos con jquery -->
          </div>
          <div class="mb-3">
              <label for="mensaje" class="form-label">Mensaje</label>
              <textarea class="form-control" name="mensaje" id="mensaje" rows="4" ></textarea>
          </div>
          <button type="submit" class="btn btn-primary">Enviar</button>
      </form>
        <br>
        <hr>
      </div>
      <br>
      <div class="col-md-6">
        <h2>Mensajes Recibidos</h2>
        <div id="lista-mensajes" class="list-group"></div>
        <div id="errorMjRecibido" class="d-none"></div> 
      </div>

      <!-- Nueva sección para los mensajes enviados -->
      <div class="col-md-6">
      <h2>Mensajes Enviados</h2>
      <div id="mensajes-enviados" class="list-group"></div>
      <div id="errorMjEnviado" class="d-none"></div>    
          <!-- Aquí se agregarán los mensajes enviados dinámicamente -->   
      </div>
    </div>  
  </section>

<!-- ############################################# SECCION DEL PERFIL ############################################# -->

    <section id="seccion-perfil" class="col-md-10 ms-sm-auto col-lg-10 px-4 bg-light seccion d-none">
      <h1 class="my-4 titulo">Editar Perfil</h1>
      <hr>
      
      <!-- Contenedor de Errores Globales -->
      <div id="errorGlobal" class="alert alert-danger d-none"></div>
      <div id="errorPerfil" class="d-none"></div>
        <!-- Mensaje de éxito -->
        <div id="mensajeExito" class="alert alert-success d-none"></div>

      <form action="" method="POST" id="perfilForm">
        <div class="mb-3">
          <label for="nombre" class="form-label">Nombre</label>
          <input type="text" class="form-control" id="nombre" name="nombre"  >
          <p class="error" id="errorNombre"></p>
        </div>
        <div class="mb-3">
          <label for="apellido" class="form-label">Apellido</label>
          <input type="text" class="form-control" id="apellido" name="apellido"  >
          <p class="error" id="errorApellido"></p>
        </div>
        <div class="mb-3">
          <label for="correo" class="form-label">Correo Electrónico</label>
          <input type="email" class="form-control" id="correo" name="correo"  >
          <p class="error" id="errorCorreo"></p>
        </div>
        <div class="mb-3">
          <label for="fechaNacimiento" class="form-label">Fecha de Nacimiento</label>
          <input type="date" class="form-control" id="fechaNacimiento" name="fechaNacimiento" aria-busy="">
          <p class="error" id="errorFecha"></p>
        </div>
        <div class="mb-3">
          <label for="clave">Nueva contraseña (opcional):</label>
          <input type="password" id="clave" name="clave" class="form-control">
          <small id="contraseñaHelp" class="form-text">
              ¡Atención! Si no deseas cambiar la contraseña, déjala en blanco.
          </small>
          <p class="error" id="errorRegistroClave"></p>
        </div>
        <div class="mb-3">
          <label for="claveConfirmacion">Confirmar nueva contraseña:</label>
          <input type="password" id="claveConfirmacion" name="claveConfirmacion" class="form-control">
          <p class="error" id="errorClave"></p>
        </div>
        
        <button type="submit" class="btn btn-primary">Guardar Cambios</button>
      </form>
      <br>
    </section>
    <br>  
    <hr>
    <br>
  <!-- Footer -->
  <footer class="bg-dark text-light py-4">
    <div class="container text-center">
      <p>© 2024 Desafíos Educativos. Todos los derechos reservados.</p>
      <p>Contacto: info@desafioseducativos.com | Tel: 123-456-789</p>
    </div>
  </footer>

<!-- ############################################# Modales de los cursos ############################################# -->

        <!-- Aqui se cargan automaticamete los modales de cursos desde la bbdd -->


    </div>
  </body>
</html>