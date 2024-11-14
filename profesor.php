<?php
session_start();

  // Incluimos el navbar-base que cargará el navbar-logueado o navbar-visitante según la sesión
  include 'navbar-base.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Profesor</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/estilos_profesor.css">
    <script defer src="scripts/profesor.js"></script>
    <script defer src="scripts/actividades.js"></script>

</head>
<body class="text-dark">   
    <div class="container-fluid">
        <div class="row">
          <!-- Menú lateral -->
          <nav class="col-md-2 d d-none d-md-block bg-light">
            <div class="card-body bg-light text-dark border-0">
              <div class="card-body">

                <ul class="nav flex-column">
                  <li class="nav-item mb-2 ">
                    <button id="btn-inicio" class="btn btn-menu">Inicio</button>
                  </li>
                  <li class="nav-item mb-2 ">
                    <button id="btn-actividades" class="btn btn-menu">Actividades</button>
                  </li>
                  <li class="nav-item mb-2 ">
                    <button id="btn-asistencia" class="btn btn-menu">Asistencia</button>
                  </li>
                  <li class="nav-item mb-2 ">
                    <button id="btn-calificaciones" class="btn btn-menu">Calificaciones</button>
                  </li>
                  <li class="nav-item mb-2 ">
                    <button id="btn-mensaje" class="btn btn-menu">Mensajes</button>
                  </li>
                  <li class="nav-item mb-2 ">
                    <button id="btn-perfil" class="btn btn-menu">Editar Perfil</button>
                  </li>
                </ul>
              </div>
            </div>
          </nav>
    
    <!--  AREA PRINCIPAL  -->
    
    <section id="seccion-inicio"  class=" col-md-10 ms-sm-auto col-lg-10 px-4 bg-light">
        <h1 class="my-4 titulo">Inicio</h1>
        <hr>
        <p>Cards de cursos a cargo.</p>
    </section>
    
    <!--  SECCION ACTIVIDADES -->
    <section id="seccion-actividades"  class="d-none col-md-10 ms-sm-auto col-lg-10 px-4 bg-light">
          <h1 class="my-4 titulo">Actividades</h1>
          <hr>
          <div id="actividades-container">
      <!-- Tabla de Actividades -->
              <h2>Lista de Actividades</h2>
              <table id="tabla-actividades" class="table table-bordered">
                  <thead>
                      <tr>
                          <th>Nombre de Actividad</th>
                          <th>Consigna</th>
                          <th>Fecha Límite</th>
                          <th>Curso</th>
                          <th>Acciones</th>
                      </tr>
                  </thead>
                  <tbody>
                      <!-- Aquí se insertarán las filas de actividades dinámicamente -->
                  </tbody>
              </table>

              <!-- Formulario para Crear Actividad -->
              <div id="alert-container"></div>
              <h3>Crear Nueva Actividad</h3>
              <form id="form-crear-actividad">
                  <div class="form-group">
                      <label for="nombre-actividad">Nombre de la Actividad</label>
                      <input type="text" id="nombre-actividad" class="form-control" required>
                  </div>
                  <div class="form-group">
                      <label for="consigna-actividad">Consigna</label>
                      <textarea id="consigna-actividad" class="form-control" rows="3" required></textarea>
                  </div>
                  <div class="form-group">
                      <label for="fecha-limite">Fecha Límite</label>
                      <input type="date" id="fecha-limite" class="form-control" required>
                  </div>
                  <div class="form-group">
                      <label for="curso-select">Curso</label>
                      <select id="curso-select" class="form-control" required>
                          <!-- Opciones de cursos cargadas dinámicamente -->
                      </select>
                  </div>
                  <button type="submit" class="btn btn-primary">Crear Actividad</button>
              </form>

          </div>
    </section>
 
    <!--  SECCION DE ASISTENCIAS  -->
    <section id="seccion-asistencias"  class="d-none col-md-10 ms-sm-auto col-lg-10 px-4 bg-light">
        <h1 class="my-4 titulo">Asistencias</h1>
        <hr>
        <p>Asistencias de cursos a cargo.</p>
    <!--  Aca se deberia tomar asistencia a los Estdiantes. Se me ocurre traer de la bbdd la lista de
      cursos a cargo del prefesor segun id de la session, luego elegir un curso, que se elija desde un 
      input la fecha para registrar la asistencia y que se despliege una tabla 
      con la lista de los estudiantes en una columna, en otras 2 columnas un checkbutton para "presente" 
      y otro para "ausente" y un boton "registrar asistencia", quizas se te ocurre algo mas a ti para agregar-->
    </section>

        <!--  SECCION DE CALIFICACIONES  -->
        <section id="seccion-calificaciones"  class="d-none col-md-10 ms-sm-auto col-lg-10 px-4 bg-light">
        <h1 class="my-4 titulo">Calificaciones</h1>
        <hr>
        <p>calificaciones de cursos a cargo.</p>
    <!--  Aca se deberan traer las actividades entregadas por los Estudiantes desde la bbdd, segun
      id del curso, y luego un input para insertar la calificacion numerica, y por ultimo un boton "calificar",
      que opinas aqui? -->
    </section>

    <!--  SECCION DEL Mensajes  -->
    
    <section id="seccion-mensajes" class="d-none col-md-10 ms-sm-auto col-lg-10 px-4 bg-light d-none">
        <h1 class="my-4 titulo">Mensajes</h1>
<hr>

  <div class="row">   
    <div class="col-md-6">
      <h2>Mensajes Recibidos</h2>
      <div id="lista-mensajes" class="d-none list-group">
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

<section id="seccion-perfil" class="d-none col-md-10 ms-sm-auto col-lg-10 px-4 bg-light d-none">
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