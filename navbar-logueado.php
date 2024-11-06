<nav class="navbar navbar-expand-lg navbar-light bg-light">
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto"> <!-- ms-auto para alinear a la derecha -->
            <li class="nav-item dropdown">
                <a class="nav-link dropdown-toggle" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                    Hola, <?php echo $_SESSION['usuario']['nombre'] . ' ' . $_SESSION['usuario']['apellido']; ?>
                </a>
                <ul class="dropdown-menu" aria-labelledby="userDropdown">
                    <li><a class="dropdown-item" href="logout.php">Cerrar sesión</a></li>
                </ul>
            </li>
            <?php if ($_SESSION['usuario']['rol'] == 'administrador'): ?>
                <li class="nav-item">
                    <a class="nav-link" href="administrador.html"></a>
                </li>
            <?php elseif ($_SESSION['usuario']['rol'] == 'profesor'): ?>
                <li class="nav-item">
                    <a class="nav-link" href="vista-profesor.html"></a>
                </li>
            <?php elseif ($_SESSION['usuario']['rol'] == 'estudiante'): ?>
                <li class="nav-item">
                    <a class="nav-link" href="index.php"></a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</nav>
