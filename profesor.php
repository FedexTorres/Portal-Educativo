<?php
session_start();
require_once ('Modulos/permisos.php');
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
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="css/estilos_profesor.css">
    <script defer src="scripts/mensajes.js"></script>
    <script defer src="scripts/profesor.js"></script>
    <script defer src="scripts/actividades.js"></script>
    <script defer src="scripts/editarPerfil.js"></script>
    <script defer src="scripts/calificaciones.js"></script>
    <script defer src="scripts/profesor-inicio.js"></script>
    <script defer src="scripts/regAsistencia.js"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>

</head>
<body class="text-dark">   
    <div class="container-fluid">
        <div class="row">
          <!-- Menú lateral -->
          <nav class="col-md-2 d d-none d-md-block bg-light">
            <div class="card-body bg-light text-dark border-0">
              <div class="card-body">
              <?php if (isset($_SESSION['usuario']) && Permisos::tienePermiso('Ver pagina profesor', $_SESSION['usuario']['id'])) { ?>
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
                <?php } ?>
              </div>
            </div>
          </nav>
    
    <!--  AREA PRINCIPAL  -->
    
    <section id="seccion-inicio"  class=" col-md-10 ms-sm-auto col-lg-10 px-4 bg-light">
        <h1 class="my-4 titulo">Inicio</h1>
        <hr>
        <div id="errorGlobalProfesor"></div>
        <div id="misCursosTraidos"></div>
    </section>
 
  <!-- ############################################# SECCION DE ACTIVIDADES ############################################# --> 
    <section id="seccion-actividades"  class="d-none col-md-10 ms-sm-auto col-lg-10 px-4 bg-light">
  <h1 class="my-4 titulo">Actividades</h1>
  <hr>
    <div class="row">
          <div class="col-md-7" id="actividades-container">
          <div id="errorListarActividad" class="d-none"></div>
          <div id="eliminarExito" class="d-none"></div>
          <div id="errorActividad" class="d-none"></div>
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
          </div>
              <!-- Formulario para Crear Actividad -->
          <div class="col-md-5" >
            <h3>Crear Nueva Actividad</h3>
            <div id="alert-container"></div>
            <div id="errorEditarActividad" class="d-none"></div>
            <div id="errorCrearActividad" class="d-none"></div>
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
                  <br>
                  <button type="submit" class="btn btn-primary">Crear Actividad</button>
                  
              </form>
        </div>
      </div>
    </section>
 
  <!-- ############################################# SECCION DE ASISTENCIAS ############################################# -->
    
    <section id="seccion-asistencias"  class="d-none col-md-10 ms-sm-auto col-lg-10 px-4 bg-light">
        <h1 class="my-4 titulo">Asistencias</h1>
        <hr>
        <div id="errorAsistencias" class="d-none"></div>
        <!-- Parte para mostrar los cursos -->
          <table id="tablaCursos" class="table table-bordered">
              <thead>
                  <tr>
                      <th>Curso</th>
                      <th>Descripción</th>
                      <th>Acciones</th>
                  </tr>
              </thead>
              <tbody>
                  <!-- Aquí se insertarán dinámicamente los cursos -->
              </tbody>
          </table>

<!-- Modal para tomar o editar asistencia -->
<div class="modal fade" id="modalAsistencia" tabindex="-1" aria-labelledby="modalAsistenciaLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalAsistenciaLabel">Tomar Asistencias</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <form id="formAsistencia">
          <!-- Campo oculto para ID de asistencia -->
          <input type="hidden" id="idAsistencia" value="">
                    <div id="errorTomarAsistencias" class="d-none"></div>
                    <!-- Input para la fecha de asistencia -->
                    <div class="form-group mb-3">
                        <label for="fechaAsistencia">Fecha de Asistencia:</label>
                        <input type="date" id="fechaAsistencia" class="form-control">
                    </div>

                    <!-- Tabla de alumnos -->
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Alumno</th>
                                <th>Presente</th>
                                <th>Ausente</th>
                            </tr>
                        </thead>
                        <tbody id="listaAlumnos">
                            <!-- Filas generadas dinámicamente -->
                        </tbody>
                    </table>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                <button type="button" class="btn btn-primary" id="guardarAsistenciaBtn">Guardar Asistencia</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal para Modificar Asistencia -->
<div class="modal fade" id="modalModificarAsistencia" tabindex="-1" aria-labelledby="modalModificarAsistenciaLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
              <h5 class="modal-title" id="modalModificarAsistenciaLabel">Modificar Asistencias</h5>
              <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
              <div id="listaAsistencias" class="list-group">
                  <div id="errorModalAsistencias" class="d-none"></div>
                    <!-- Aquí se cargarán dinámicamente las asistencias -->
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
            </div>
        </div>
    </div>
</div>

</section>

  <!-- ############################################# SECCION DE CALIFICACIONES ############################################# -->
    <section id="seccion-calificaciones"  class="d-none col-md-10 ms-sm-auto col-lg-10 px-4 bg-light">
        <h1 class="my-4 titulo">Calificaciones de Entregas</h1>
        <div id="calificaciones-container" class="container mt-4">       
        <!-- Filtros de curso y profesor  -->
        <div id="form-container"></div>
        <div id="errorCalificaciones" class="d-none"></div>
        <!-- Tabla de entregas -->
            <table class="table table-striped" id="tabla-entregas">
                <thead>
                    <tr>
                        <th>ID Entrega</th>
                        <th>Actividad</th>
                        <th>Fecha de Entrega</th>
                        <th>Descargar</th>
                        <th>Calificar</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Aquí se van a renderizar las filas de las entregas con calificaciones.js -->
                </tbody>
            </table>
    </div>

    <!-- Modal para calificar actividad -->
          <div class="modal fade" id="modalCalificar" tabindex="-1" aria-labelledby="modalCalificarLabel" aria-hidden="true">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h5 class="modal-title" id="modalCalificarLabel">Calificar Actividad</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                  </div>
                  <div class="modal-body">
                    <form id="form-calificacion">
                      <div class="mb-3">
                        <label for="inputCalificacion" class="form-label">Calificación:</label>
                        <input type="number" id="inputCalificacion" class="form-control" min="1" max="10" required>
                      </div>
                      <div id="errorModalCalificaciones" class="d-none"></div>
                          </form>
                      </div>
                      <div class="modal-footer">
                          <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                          <button type="button" class="btn btn-primary" onclick="guardarCalificacionModal()">Guardar</button>
                      </div>
                  </div>
              </div>
          </div>
        <hr>
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
    
  <!-- ############################################# SECCION PERFIL ############################################# -->

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
      </div>
    </div>
    <div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModal" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCursoLabel"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form enctype="multipart/form-data" id="form1">
                    <div class="modal-body">
                          
                            <div class="form-group">
                                <label for="title">Titulo</label>
                                <input type="text" class="form-control" id="title" name="title" required>
                            </div>
                            <div class="form-group">
                                <label for="description">Descripcion</label>
                                <input type="text" class="form-control" id="description" name="description" required>
                            </div>
                            <div class="form-group">
                                <label for="file">archivo</label>
                                <input type="file" class="form-control" id="file" name="file" required>
                            </div>
                        
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cerrar</button>
                        <button type="submit" class="btn btn-success" id="guardarMaterial" data-bs-dismiss="modal">Guardar</button>
                    </div>
                    </form>
                </div>
            </div>
    </div>
</body>
</html>



