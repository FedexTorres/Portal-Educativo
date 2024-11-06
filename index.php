<?php
session_start();

  // Incluimos el navbar-base que cargará el navbar-logueado o navbar-visitante según la sesión
  include 'navbar-base.php';
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
    <script defer src="scripts/estudiante-manejarPerfil.js"></script>
    <script defer src="scripts/estudiante-menus.js"></script>
    <script defer src="scripts/estudiante-misCursos.js"></script>
    <script defer src="scripts/mensajes.js"></script>
  
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
            <?php if (isset($_SESSION['usuario']) && $_SESSION['usuario']['rol'] == 'estudiante') { ?>
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

  <section id="seccion-inicio"  class="col-md-9 ms-sm-auto col-lg-10 px-4 bg-light">
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
      <div class="col-md-4 mb-4">
        <div class="card bg-secondary text-light border-0">
          <img src="image/curso1.jpg" class="card-img-top card-image" alt="Curso 1">
          <div class="card-body">
            <h5 class="card-title">Electricidad</h5>
            <p class="card-text"><b>Descripción:</b> <hr>
              Este curso brinda los conocimientos esenciales para trabajar en instalaciones eléctricas tanto en el ámbito residencial como comercial. 
              Se tratan temas sobre circuitos eléctricos, instalación de sistemas, y manejo de herramientas específicas, enfatizando en la seguridad eléctrica.</p>
            <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modalCurso1">Ver Detalles</button>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-4">
        <div class="card bg-secondary text-light border-0">
          <img src="image/curso2.jpg" class="card-img-top card-image" alt="Curso 2">
          <div class="card-body">
            <h5 class="card-title">Cocinero</h5>
            <p class="card-text">El curso de cocinero está diseñado para formar profesionales en el arte culinario, brindando conocimientos sobre técnicas de cocina, manejo de alimentos, y elaboración de diferentes tipos de platos. 
              Se enfoca en desarrollar habilidades prácticas y la creatividad en la cocina.</p>
            <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modalCurso2">Ver Detalles</button>
          </div>
        </div>
      </div>
      <div class="col-md-4 mb-4">
        <div class="card bg-secondary text-light border-0">
          <img src="image/curso3.jpg" class="card-img-top card-image" alt="Curso 3">
          <div class="card-body">
            <h5 class="card-title">Refrigeración</h5>
            <p class="card-text"><b>Descripción:</b> <hr>
              Este curso está dirigido a aquellas personas interesadas en adquirir conocimientos teóricos y prácticos en el mantenimiento y reparación de sistemas de refrigeración y aire acondicionado. 
              Se abordan conceptos sobre los tipos de refrigerantes, el funcionamiento de los sistemas, y técnicas para diagnosticar y solucionar problemas.</p>
            <button class="btn btn-info" data-bs-toggle="modal" data-bs-target="#modalCurso3">Ver Detalles</button>
          </div>
        </div>
      </div>
    </div>
  </section>

<!-- ############################################# SECCION MIS CURSOS #############################################-->

<section id="seccion-mis-cursos" class="col-md-9 ms-sm-auto col-lg-10 px-4 bg-light d-none">
  <h1 class="my-4 titulo">Mis Cursos</h1>
  <hr>
  <p>Lista de cursos en los que estás inscrito.</p>

  <!-- Card del Curso Refrigeración a modo de ejemplo-->
  <div class="card mb-4">
    <div class="card-body">
      <h5 class="card-title">Refrigeración</h5>
      <h6 class="card-subtitle mb-2 text-muted">Profesor: Ing. Carlos López | Período: 2023-2024</h6>
      <p class="card-text">Cantidad de clases: 16 | Cantidad de exámenes: 3</p>

      <!-- Botones de opciones para el curso -->
      <button class="btn btn-primary btn-consultar-asistencia">Consultar Asistencia</button>
      <button class="btn btn-secondary btn-consultar-calificacion">Consultar Calificación</button>
      <button class="btn btn-info btn-realizar-examen">Realizar Examen</button>
      <button class="btn btn-success btn-subir-trabajo">Subir Trabajo Práctico</button>
      <button class="btn btn-warning btn-filtrar-asistencia">Filtrar Asistencia</button>
    </div>
  </div>

  <!-- Sub-secciones dentro de la vista de Mis Cursos -->
  <div id="sub-seccion-asistencia" class="d-none">
    <h4>Asistencia del Curso de Refrigeración</h4>
    <ul>
      <li>Clase 1: Presente</li>
      <li>Clase 2: Ausente</li>
      <li>Clase 3: Presente</li>
      <li>Clase 4: Presente</li>
    </ul>
    <button class="btn btn-secondary btn-volver">Volver</button>
  </div>

  <div id="sub-seccion-calificacion" class="d-none">
    <h4>Calificaciones del Curso de Refrigeración</h4> <!-- Aqui se debera consultar la BBDD -->
    <ul>
      <li>Examen Parcial 1: 8</li>
      <li>Examen Parcial 2: 7</li>
      <li>Examen Final: 9</li>
    </ul>
    <button class="btn btn-secondary btn-volver">Volver</button>
  </div>

  <!-- Sub-sección para Realizar Examen -->

  <div id="sub-seccion-examen" class="d-none">
    <h4>Realizar Examen de Refrigeración</h4>
    <p>Examen: ¿Cuál es la función de un compresor?</p>
    <form action="" method="POST" id="form-realizar-examen">
      <div class="form-check">
        <input class="form-check-input" type="radio" name="respuesta" id="respuesta1" value="a">
        <label class="form-check-label" for="respuesta1">A) Refrigerar el aire</label>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="radio" name="respuesta" id="respuesta2" value="b">
        <label class="form-check-label" for="respuesta2">B) Calentar el aire</label>
      </div>
      <div class="form-check">
        <input class="form-check-input" type="radio" name="respuesta" id="respuesta3" value="c">
        <label class="form-check-label" for="respuesta3">C) Regular la presión</label>
      </div>
      <br>
      <button type="submit" class="btn btn-primary">Enviar Respuesta</button>
    </form>
    <br>
    <button class="btn btn-secondary btn-volver">Volver</button>
  </div>
  <br>

  <!-- Sub-sección para Subir Trabajo Práctico -->

  <div id="sub-seccion-subir-trabajo" class="d-none">
    <h4>Subir Trabajo Práctico</h4>
    <form action="" method="POST" id="form-subir-trabajo">
      <div class="mb-3">
        <label for="archivo" class="form-label">Selecciona el archivo</label>
        <input type="file" class="form-control" id="archivo" required>
      </div>
      <br>
      <button type="submit" class="btn btn-primary">Subir Trabajo</button>
    </form>
    <br>
    <button class="btn btn-secondary btn-volver">Volver</button>
  </div>
  <br>

  <!-- Sub-sección para Filtrar Asistencia -->

  <div id="sub-seccion-filtrar-asistencia" class="d-none"> <!-- Aqui se debera consultar la BBDD -->
      <h4>Filtrar Asistencia</h4>
      <label for="estado-asistencia">Selecciona el estado:</label>
      <select id="estado-asistencia" class="form-select mb-3">
          <option value="todos">Todos</option>
          <option value="presente">Presentes</option>
          <option value="ausente">Ausentes</option>
      </select>
      <button class="btn btn-primary">Filtrar</button>
      <button class="btn btn-secondary btn-volver">Volver</button>
  </div>
  <br>
</section>

  <!-- ############################################# SECCION DE MENSAJES ############################################# -->

  <section id="seccion-mensajes" class="col-md-9 ms-sm-auto col-lg-10 px-4 bg-light d-none">
  <h1 class="my-4 titulo">Mensajes</h1>
  <hr>
  <div id="alerta" class="alert alert-danger d-none" role="alert"></div>
  <div id="exito" class="alert alert-success d-none"></div>

  
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
      </div>

      <!-- Nueva sección para los mensajes enviados -->
      <div class="col-md-6">
      <h2>Mensajes Enviados</h2>
      <div id="mensajes-enviados" class="list-group"></div>
          <!-- Aquí se agregarán los mensajes enviados dinámicamente -->       
      </div>
    </div>  
  </section>

<!-- ############################################# SECCION DEL PERFIL ############################################# -->

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
<br>
<hr>
<br>
  <!-- Footer -->
  <footer class="bg-dark text-light py-4">
    <div class="container text-center">
      <p>© 2024 Plataforma Educativa. Todos los derechos reservados.</p>
      <p>Contacto: info@plataformaeducativa.com | Tel: 123-456-789</p>
    </div>
  </footer>

<!-- ############################################# Modales de los cursos ############################################# -->

    <div class="modal fade" id="modalCurso1" tabindex="-1" aria-labelledby="modalCurso1Label" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalCurso1Label">Electricidad - Detalles</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <b>Programa de Estudios:</b><br><br> 

              1- Conceptos Básicos de Electricidad:<br> 

              Corriente eléctrica, voltaje y resistencia.
              Ley de Ohm y su aplicación.
              Tipos de circuitos: serie y paralelo.<br><br>

              2- Instalaciones Eléctricas Residenciales:<br>  
              Tipos de cables y conductores.
              Instalación de interruptores, tomacorrientes y luminarias.
              Cálculo de carga y distribución de circuitos.<br><br>

              3- Seguridad Eléctrica:<br> 
              Identificación de riesgos eléctricos.
              Uso de equipos de protección personal (EPP).
              Procedimientos para trabajos en altura y espacios confinados.<br><br> 
              <b>Dias y Horarios:</b><br> 
              Lunes, miercoles y viernes de 12:00 a 16:00hs.
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary">Inscribirse</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modalCurso2" tabindex="-1" aria-labelledby="modalCurso2Label" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalCurso2Label">Cocinero - Detalles</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <b>Programa de Estudios:</b><br><br> 

              1- Técnicas Culinarias Básicas:<br> 

              Cortes de vegetales y manejo de cuchillos.
              Métodos de cocción: asado, hervido, salteado, entre otros.
              Elaboración de salsas y fondos.<br><br>

              2- Cocina Internacional y Nacional:<br>  
              Preparación de platos típicos de diferentes regiones.
              Cocina gourmet y presentación de platos.
              Repostería básica y avanzada.<br><br>

              3- Manipulación y Seguridad Alimentaria:<br> 
              Normativas de higiene en la cocina.
              Conservación y almacenamiento de alimentos.
              Prevención de contaminación cruzada y uso de utensilios adecuados.<br><br> 
              <b>Dias y Horarios:</b><br> 
              Martes y jueves de 13:00 a 17:00hs.
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary">Inscribirse</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="modalCurso3" tabindex="-1" aria-labelledby="modalCurso3Label" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="modalCurso3Label">Refrigeración - Detalles</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body">
            <b>Programa de Estudios:</b><br><br> 

              1- Fundamentos de la Refrigeración:<br> 

              Principios de termodinámica.
              Tipos de refrigerantes y sus características.
              Componentes básicos: compresores, condensadores, evaporadores.<br><br>
              2- Mantenimiento y Reparación:<br>  

              Diagnóstico de fallas comunes en sistemas de refrigeración.
              Reemplazo de componentes y carga de refrigerante.
              Limpieza y mantenimiento preventivo.<br><br>

              3- Refrigeración Comercial y Doméstica:<br> 

              Instalación y ajuste de equipos comerciales.
              Manejo de equipos de medición y diagnóstico.
              Normativas y seguridad en la manipulación de refrigerantes.<br><br> 
              <b>Dias y Horarios:</b><br> 
              Lunes, miercoles y viernes de 08:00 a 12:00hs.
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            <button type="button" class="btn btn-primary">Inscribirse</button>
          </div>
        </div>
      </div>
    </div>
  </body>
</html>