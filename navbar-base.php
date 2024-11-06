<!-- Barra de navegación superior -->
<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <div class="container-fluid">
<!-- Logo del sitio -->
<a class="navbar-brand" href="#">
    <img src="image/logo-educativo.png" alt="Logo" class="logo-navbar" >
</a>

    <!-- Botón de hamburguesa para pantallas pequeñas -->
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>

    <!-- Opciones de menú (se colapsan en el botón de hamburguesa) -->
    <div class="collapse navbar-collapse" id="navbarContent">
      <ul class="navbar-nav ms-auto">
        <?php
        if (isset($_SESSION['usuario'])) {
            include 'navbar-logueado.php';
        } else {
            include 'navbar-visitante.php';
        }
        ?>
      </ul>
    </div>
  </div>
</nav>
