<?php

// Incluir la conexión a la base de datos
require './conexion_bbdd.php';
header('Content-Type: application/json');

// Verificar si la solicitud es POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nombre = isset($_POST["UserNewNombre"]) ? trim($_POST["UserNewNombre"]) : null;
    $apellido = isset($_POST["UserNewApellido"]) ? trim($_POST["UserNewApellido"]) : null;
    $correo = isset($_POST["UserNewEmail"]) ? trim($_POST["UserNewEmail"]) : null;
    $fecha_nacimiento = isset($_POST["UserNewFecha"]) ? trim($_POST["UserNewFecha"]) : null;
    $contraseña = isset($_POST["UserNewContra"]) ? trim($_POST["UserNewContra"]) : null;
    $confirmarPassword = isset($_POST["UserNewContra2"]) ? trim($_POST["UserNewContra2"]) : null;

    $tipo = isset($_POST["UserNewTipo"]) ? trim($_POST["UserNewTipo"]) : null;

    $idUsurioActual = $_POST["userId"];
    // Variables para almacenar errores
    $errores = [];

    // Validaciones
    // Verificar que el nombre no esté vacío y que solo tenga letras y espacios
    if (empty($nombre)) {
        $errores[] = "El nombre no puede estar vacío.";
    } elseif (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚ ]+$/', $nombre)) {
        $errores[] = "El nombre solo puede contener letras y espacios.";
    }

    // Verificar que el apellido no esté vacío y que solo tenga letras y espacios
    if (empty($apellido)) {
        $errores[] = "El apellido no puede estar vacío.";
    } elseif (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚ ]+$/', $apellido)) {
        $errores[] = "El apellido solo puede contener letras y espacios.";
    }

    // Verificar que el correo no esté vacío y que sea válido
    if (empty($correo)) {
        $errores[] = "El correo no puede estar vacío.";
    } elseif (!preg_match('/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $correo)) {
        $errores[] = "El correo no es válido.";
    }

    // Verificar que la fecha de nacimiento no esté vacía, sea válida y que el usuario tenga al menos 16 años
    if (empty($fecha_nacimiento)) {
        $errores[] = "La fecha de nacimiento no puede estar vacía.";
    } else {
        $fechaNacimientoDate = new DateTime($fecha_nacimiento);
        $hoy = new DateTime();
        $edad = $hoy->diff($fechaNacimientoDate)->y;
        
        // Verificar si la fecha es futura
        if ($fechaNacimientoDate > $hoy) {
            $errores[] = "La fecha de nacimiento no puede ser del futuro.";
        } elseif ($edad < 16) {
            $errores[] = "Debes tener al menos 16 años.";
        }
    }

    // Validar contraseña: no puede estar vacía, debe tener al menos 4 caracteres y deben coincidir
    if (empty($contraseña) || strlen($contraseña) < 4) {
        $errores[] = "La contraseña debe tener al menos 4 caracteres.";
    }

    if ($contraseña !== $confirmarPassword) {
        $errores[] = "Las contraseñas no coinciden.";
    }

    try {
        $queryUsuario = "SELECT COUNT(*) FROM usuarios WHERE correo = :correo AND id != :id";
        $stmUsuario = $conn->prepare($queryUsuario);
        $stmUsuario->bindParam(':correo', $correo);
        $stmUsuario->bindParam(':id', $idUsurioActual, PDO::PARAM_INT);
        $stmUsuario->execute();
        if ($stmUsuario->fetchColumn() > 0) {
            $errores[] = "El correo ya está en uso.";
        }
    } catch (PDOException $e) {
        $errores[] = "Error al verificar el correo en la base de datos.";
    }


    // Si hay errores, devolverlos
    if (!empty($errores)) { 
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => $errores]);
        exit();
    }

    // Si no hay errores, proceder a registrar
    $contraseñaHash = password_hash($contraseña, PASSWORD_BCRYPT); // Hash de la contraseña

    // Prepara la consulta para insertar el nuevo estudiante
        
        $stmt = $conn->prepare("UPDATE usuarios SET nombre = :nombre, apellido = :apellido, correo = :correo, fecha_nacimiento = :fecha_nacimiento, contrasena=:clave WHERE id=:idusuario");
        $stmt->bindParam(':nombre', $nombre); 
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':fecha_nacimiento', $fecha_nacimiento); // Cambiado para usar la variable correcta
        $stmt->bindParam(':clave', $contraseñaHash);

        $stmt->bindParam(':idusuario', $idUsurioActual);

        
        $resultado = $stmt->execute();


        $rolAsig = $conn->prepare("UPDATE roles_usuarios SET id_rol=:rol WHERE id_usuario=:idusuario");
        $rolAsig->bindParam(":rol", $tipo);
        $rolAsig->bindParam(":idusuario", $idUsurioActual);

        $rolAsig->execute();

     if ($resultado) {
        header('Content-Type: application/json');
         echo json_encode(['status' => 'success', 'message' => 'Registro exitoso.']);
     } else {
        header('Content-Type: application/json');
         echo json_encode(['status' => 'error', 'message' => 'Error al registrar el usuario.']);
     }
 } else {
    header('Content-Type: application/json');
     echo json_encode(['status' => 'error', 'message' => 'Método de solicitud no válido.']);
 }