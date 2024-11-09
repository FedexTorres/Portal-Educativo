<?php
session_start();

  // Incluimos el navbar-base que cargará el navbar-logueado o navbar-visitante según la sesión
  include 'navbar-base.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profesor</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="scripts/profesor2.js"></script>
    <link href='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.7/index.global.min.css' rel='stylesheet' />
    <script src='https://cdn.jsdelivr.net/npm/fullcalendar@6.1.7/index.global.min.js'></script>
    <link rel="stylesheet" href="css/estilo_administrador.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.bundle.min.js"></script>

</head>
<body class="text-dark">
    
    <div class="container-fluid">
        <div class="row">
          <!-- Menú lateral -->
          <nav class="col-md-2 d-none d-md-block bg-light">
            <div class="card-body bg-light text-dark border-0">
              <div class="card-body">
                <br>
                <h4 class="card-title">Menú</h4>
                <br>
                <ul class="nav flex-column">
                  <li class="nav-item mb-2 row">
                    <button id="btn-espacio" class="btn btn-menu">Inicio</button>
                  </li>
                  <li class="nav-item mb-2 row">
                    <button id="btn-calendario" class="btn btn-menu">Calendario</button>
                  </li>
                  <li class="nav-item mb-2 row">
                    <button id="btn-cursos" class="btn btn-menu">Cursos</button>
                  </li>
                  <li class="nav-item mb-2 row">
                    <button id="mensaje-btn" class="btn btn-menu">Mensajes</button>
                  </li>
                  <li class="nav-item mb-2 row">
                    <button id="perfil-btn" class="btn btn-menu">Editar Perfil</button>
                  </li>
                </ul>
              </div>
            </div>
          </nav>
    
    <!--  AREA PRINCIPAL  -->
    
      <section id="mi-espacio"  class="col-md-9 ms-sm-auto col-lg-10 px-4 bg-light">
        <h1 class="my-4 titulo">Inicio</h1>
        <hr>
        <p>hay que pensar que poner aca.</p>
      </section>
    
    <!--  SECCION CALENDARIO -->
    
    <section id="calendar" class="col-md-9 ms-sm-auto col-lg-10 px-4 bg-light d-none">

      
      


    </section>
    
    <!--  SECCION DE CURSOS  -->
    
    <section id="filtros-cursos" class="col-md-9 ms-sm-auto col-lg-10 px-4 bg-light d-none">
      <h1 class="my-4 titulo">Gestion de Cursos</h1>
      <hr>
      <div class="mb-3">
        <input type="text" id="filtro-curso" class="form-control" placeholder="Filtrar por nombre de curso">
      </div>
      <div class="mb-3">
        <input type="text" id="filtro-nivel" class="form-control" placeholder="Filtrar por nivel">
      </div>
      <div class="mb-3">
        <input type="text" id="filtro-tipo" class="form-control" placeholder="Filtrar por tipo">
      </div>
      <!-- Tabla similar a Excel -->
      <table class="table table-bordered">
        <thead>
          <tr>
            <th>Nombre del Curso</th>
            <th>Nivel</th>
            <th>Tipo</th>
            <th>Acciones</th>
          </tr>
        </thead>
        <tbody id="tabla-cursos">
          <!-- Aquí se agregarán dinámicamente los cursos -->
        </tbody>
      </table>
    </section>
    
    <!--  SECCION DEL Mensajes  -->
    
    <section id="seccion-mensajes" class="col-md-9 ms-sm-auto col-lg-10 px-4 bg-light d-none">
        <h1 class="my-4 titulo">Mensajes</h1>
<hr>

  <div class="row">   
    <div class="col-md-6">
      <h2>Mensajes Recibidos</h2>
      <div id="lista-mensajes" class="list-group">
        <a href="#" class="list-group-item list-group-item-action">
          <strong>Remitente:</strong> Juan Pérez <br>
          <strong>Mensaje:</strong> Hola, ¿cómo estás? <br>
          <small>Fecha: 26/09/2024</small>
        </a>
        <a href="#" class="list-group-item list-group-item-action">
          <strong>Remitente:</strong> Dani García <br>
          <strong>Mensaje:</strong> Recuerda la reunión mañana. <br>
          <small>Fecha: 25/09/2024</small>
        </a>
      </div>
    </div>

    <div class="col-md-6">
      <h2>Enviar Mensaje</h2>
      <form action="" method="POST" id="form-enviar-mensaje">
        <div class="mb-3">
          <label for="destinatario" class="form-label">Destinatario</label>
          <input type="text" class="form-control" id="destinatario" placeholder="Nombre del destinatario" required>
        </div>
        <div class="mb-3">
          <label for="mensaje" class="form-label">Mensaje</label>
          <textarea class="form-control" id="mensaje" rows="3" required></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Enviar</button>
      </form>
      <br>
    </div>
  </div>
    </section>
    
    <!--  SECCION DEL PERFIL  -->

<section id="seccion-perfil" class="col-md-9 ms-sm-auto col-lg-10 px-4 bg-light d-none">
    <h1 class="my-4 titulo">Perfil del Estudiante</h1>
    <hr>
    <form action="" method="POST" id="form-perfil">
      <div class="mb-3">
        <label for="nombre" class="form-label">Nombre</label>
        <input type="text" class="form-control" id="nombre" value="Juan" required>
        <p class="error" id="errorNombre" > </p>
      </div>
      <div class="mb-3">
        <label for="apellido" class="form-label">Apellido</label>
        <input type="text" class="form-control" id="apellido" value="Pérez" required>
        <p class="error" id="errorApellido" > </p>
      </div>
      <div class="mb-3">
        <label for="correo" class="form-label">Correo Electrónico</label>
        <input type="email" class="form-control" id="correo" value="juan.perez@example.com" required>
        <p class="error" id="errorCorreo"> </p>
      </div>
      <div class="mb-3">
        <label for="fecha-nacimiento" class="form-label">Fecha de Nacimiento</label>
        <input type="date" class="form-control" id="fecha-nacimiento" value="2000-01-01" required>
        <p class="error" id="errorFecha"> </p>
      </div>
      <div class="mb-3">
        <label for="clave" class="form-label">Contraseña</label>
        <input type="password" class="form-control" id="clave" required>
      </div>
      <div class="mb-3">
        <label for="claveConfirmacion" class="form-label">Repetir Contraseña</label>
        <input type="password" class="form-control" id="claveConfirmacion" required>
        <p class="error" id="errorClave"> </p>
    </div>
      <button type="submit" class="btn btn-primary">Guardar Cambios</button>
    </form>
    <br>
  </section>
    
    </div>
    </div>



</body>
</html>