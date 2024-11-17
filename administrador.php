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
    <title>Administrador Principal</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.8.1/font/bootstrap-icons.min.css" rel="stylesheet">

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <link rel="stylesheet" href="css/estilo_administrador.css">
    <script src="scripts/administrador.js" defer></script>
    <script src="scripts/administradorGestorDeUsuarios.js" defer></script>
    <script src="scripts/administradorGestorDeCursos.js" defer></script>
    <script defer src="scripts/mensajes.js"></script>
    <script defer src="scripts/editarPerfil.js"></script>


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
                    <button id="inicio-btn" class="btn btn-menu">Inicio</button>
                  </li>
                  <li class="nav-item mb-2 row">
                    <button id="cursos-btn" class="btn btn-menu">Gestion De Cursos</button>
                  </li>
                  <li class="nav-item mb-2 row">
                    <button id="usuarios-btn" class="btn btn-menu">Gestion de Usuarios</button>
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
    
      <section id="seccion-inicio"  class="col-md-9 ms-sm-auto col-lg-10 px-4 bg-light">
        <h1 class="my-4 titulo">Inicio</h1>
        <hr>
        <p>hay que pensar que poner aca.</p>
      </section>
    
    <!--  SECCION MIS CURSOS -->
    
    <section id="seccion-mis-cursos" class="col-md-9 ms-sm-auto col-lg-10 px-4 bg-light d-none">
      <h1 class="my-4 titulo">Cursos</h1>
      <hr>
      
      <button type="button" class="btn btn-success" id="botonCreacionCurso">Crear curso nuevo</button>
      <br>

      <div class="row" id="courses-section">
      <!--Aca se agregan los cursos-->
      </div>
      <br>
      <div id="errorGlobal" class="alert alert-danger d-none"></div>


    </section>

    <!-- modal de ajustes de cursos -->
    <div class="modal fade" id="modalCursoAjuste" tabindex="-1" aria-labelledby="modalCursoAjusteLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-dialog-scrollable">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCursoAjusteLabel"></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>


                    <form action="" method="POST" id="form-guardar-ajuste">

                    <div class="modal-body">                   
                          <div class="mb-3 autocompletar">
                          <label for="profesor" class="form-label">Asignar Profesor al curso</label>
                          <input type="text" class="form-control" id="profesor" name="profesor" placeholder="Ingrese el nombre del profesor" >
                          </div>

                          <div class="mb-3">
                          <label for="vacante" class="form-label">Ingrese cantidad de vacantes disponibles</label>
                          <input type="number" class="form-control" id="vacante" name="vacante" placeholder="0" >
                          </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancelar</button>
                        <button type="submit" class="btn btn-success" data-bs-dismiss="modal" id="guardarAjustes">Guardar</button>
                    </div>

                    </form>

                </div>
            </div>
        </div>

    
    <!--  SECCION DE Usuarios  -->
    
    <section id="seccion-usuarios" class="col-md-9 ms-sm-auto col-lg-10 px-4 bg-light d-none">
      <h1 class="my-4 titulo">Gestion de Usuarios</h1>
      <hr>
      <div class="mb-3">
        <button type="button" class="btn btn-success" id="botonCrearusuario">Crear Usuario</button>
      </div>
      <div class="mb-3">
        <input type="text" id="filtro-buscar" class="form-control" placeholder="Buscar usuario">
      </div>
      <div class="mb-3">
        <table class="table table-bordered" id="tablaUsuarios">
          <thead>
            <tr>
              <th>Nombre</th>
              <th>Mail</th>
              <th>Tipo</th>
              <th>Accion</th>
            </tr>
          </thead>
          <tbody>




          </tbody>
        </table>
      </div>
    </section>
    
    <!--  SECCION DEL Mensajes  -->
    
    <section id="seccion-mensajes" class="col-md-9 ms-sm-auto col-lg-10 px-4 bg-light seccion d-none">
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
    
    <!--  SECCION DEL PERFIL  -->

    <section id="seccion-perfil" class="col-md-9 ms-sm-auto col-lg-10 px-4 bg-light seccion d-none">
      <h1 class="my-4 titulo">Perfil del Estudiante</h1>
      <hr>
      
      <!-- Contenedor de Errores Globales -->
      
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
    
    </div>
    </div>



<!-- Modal para creacion de curso -->

<div class="modal" id="myModalCreacionCurso">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Creacion de curso nuevo</h4>
      </div>

      <!-- Modal body -->
      <form action="" id="crearCurso" enctype="multipart/form-data" class="was-validated">
      <div class="modal-body">     
          <label for="nombrecrear" class="form-label">Nombre</label>
          <input type="text" name="nombrecrear" id="nombrecrear" class="form-control" required>
          <div class="invalid-feedback">El campo no puede estar vacio.</div>
          <br>
          <label for="textCrearCurso" class="form-label">Descripcion:</label>
          <textarea id="textCrearCurso" name="textCrearCurso" class="form-control" required></textarea>
          <div class="invalid-feedback">El campo no puede estar vacio.</div>
          <br>
          <label for="programaCrearCurso" class="form-label">Programa de estudios:</label>
          <textarea id="programaCrearCurso" name="programaCrearCurso" class="form-control" required></textarea>
          <div class="invalid-feedback">El campo no puede estar vacio.</div>
          <br>
          <label for="fechaInicioCrearCurso" class="form-label">Fecha Inicio:</label>
          <input type="date" id="fechaInicioCrearCurso" name="fechaInicioCrearCurso" class="form-control" required>
          <div class="invalid-feedback">El campo no puede estar vacio.</div>
          <br>
          <label for="fechaFinCrearCurso" class="form-label">Programa de estudios:</label>
          <input type="date" id="fechaFinCrearCurso" name="fechaFinCrearCurso" class="form-control" required>
          <div class="invalid-feedback">El campo no puede estar vacio.</div>
          <br>
          <label for="archivoCrear" class="form-label">Imagen</label>
          <input type="file" id="archivoCrear" name="archivoCrear" class="form-control" required>  
          <div class="invalid-feedback">El campo no puede estar vacio.</div>   
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Guardar</button>
        <button type="button" class="btn btn-danger" id="cancelarCreacionCurso">Cancelar</button>
      </div>
      </form>
    </div>
  </div>
</div>


<!-- Modal para Editar curso -->

<div class="modal" id="myModal2">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Editar Modal</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal body -->
      <form action="" id="editarCurso">
      <div class="modal-body">      
          <label for="texteditar" class="form-label">Nombre</label>
          <input type="text" name="texteditar" id="texteditar" class="form-control" placeholder="Titulo" required>
          <br>
          <label for="textEditarcurso" class="form-label">Descripcion</label>
          <textarea name="textEditarcurso" id="textEditarcurso" class="form-control" placeholder="Profesor: none | Período: none" required></textarea>
          <label for="imgeditar" class="form-label">Imagen</label>
          <input type="file" id="imgeditar" name="imgeditar" class="form-control" required>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Guardar Cambios</button>
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancelar</button>
      </div>
      </form>
    </div>
  </div>
</div>


<!-- Modal para Eliminar curso -->

<div class="modal" id="myModal3">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Eliminar Curso</h4>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>

      <!-- Modal body -->
      <form action="" id="EliminarCurso">
      <div class="modal-body">      
          <h3>Seguro desea eliminar el curso?</h3>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="submit" class="btn btn-success">Si</button>
        <button type="button" class="btn btn-danger" data-bs-dismiss="modal">No</button>
      </div>
      </form>
    </div>
  </div>
</div>

<!-- Modal para Crear usuario -->

<div class="modal" id="Crearusuario">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title" id="tituloFormUsuarios">Crear Usuario</h4>
      </div>

      <!-- Modal body -->
      <form action="" id="CrearUsuarioForm">
        <div class="modal-body"> 
          <div class="mb-3">  
          <label for="UserNewNombre" class="form-label">Nombre:</label>
          <input type="text" id="UserNewNombre" name="UserNewNombre" class="form-control" required>
          <div id="errorNombre" class="invalid-feedback text-danger"></div> <!-- Div para mostrar el error -->
          </div>
          <div class="mb-3">  
          <label for="UserNewApellido" class="form-label">Apellido:</label>
          <input type="text" id="UserNewApellido" name="UserNewApellido" class="form-control" required>
          <div id="errorApellido" class="invalid-feedback text-danger"></div> <!-- Div para mostrar el error -->
          </div>
          <div class="mb-3">  
          <label for="UserNewFecha" class="form-label">Fecha de nacimiento:</label>
          <input type="date" id="UserNewFecha" name="UserNewFecha" class="form-control" required>
          <div id="errorFecha" class="invalid-feedback text-danger"></div> <!-- Div para mostrar el error -->
          </div>
          <div class="mb-3">  
          <label for="UserNewEmail" class="form-label">Email:</label>
          <input type="email" id="UserNewEmail" name="UserNewEmail" class="form-control" required>
          <div id="errorEmail" class="invalid-feedback text-danger"></div> <!-- Div para mostrar el error -->
          </div>
          <div class="mb-3">  
          <label for="UserNewContra" class="form-label">Contraseña:</label>
          <input type="text" id="UserNewContra" name="UserNewContra" class="form-control" required>     
          <div id="errorContra" class="invalid-feedback text-danger"></div> <!-- Div para mostrar el error -->
          </div>
          <div class="mb-3">  
          <label for="UserNewContra2" class="form-label">Repetir Contraseña:</label>
          <input type="text" id="UserNewContra2" name="UserNewContra2" class="form-control" required>
          <div id="errorContra2" class="invalid-feedback text-danger"></div> <!-- Div para mostrar el error -->
          </div>
          <div class="mb-3">  
          <label for="UserNewTipo">Tipo usuario:</label>
          <select id="UserNewTipo" name="UserNewTipo" class="form-select">
            <option></option>
            
          </select>
          <div id="errorTipo" class="invalid-feedback text-danger"></div> <!-- Div para mostrar el error -->
          </div>

        </div>
        <!-- Modal footer -->
        <div class="modal-footer">
          <button type="submit" class="btn btn-success">Crear</button>
          <button type="button" class="btn btn-danger" id="cerrarmodalcrearuser">No</button>
        </div>
      </form>
    </div>
  </div>
</div>



<!-- The Modal elimnar usuario -->
<div class="modal" id="elimarusuariomodal">
  <div class="modal-dialog">
    <div class="modal-content">

      <!-- Modal Header -->
      <div class="modal-header">
        <h4 class="modal-title">Eliminar usuario</h4>
      </div>

      <!-- Modal body -->
      <div class="modal-body">
        <h3>Seguro desea eliminar el usuario: </h3>
        <h2 id="eluserchau"></h2>
      </div>

      <!-- Modal footer -->
      <div class="modal-footer">
        <button type="button" class="btn btn-success" id="AdiosUser">Si</button>
        <button type="button" class="btn btn-danger" id="NoEliminar">No</button>
      </div>

    </div>
  </div>
</div>



</body>
</html>