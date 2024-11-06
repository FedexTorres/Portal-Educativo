<?php

// Incluir la conexión a la base de datos
require './conexion_bbdd.php';
header('Content-Type: application/json');

// Verificar si la solicitud es POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nombre = isset($_POST["nombre"]) ? trim($_POST["nombre"]) : null;
    $apellido = isset($_POST["apellido"]) ? trim($_POST["apellido"]) : null;
    $correo = isset($_POST["correo"]) ? trim($_POST["correo"]) : null;
    $fecha_nacimiento = isset($_POST["fechaNacimiento"]) ? trim($_POST["fechaNacimiento"]) : null;
    $contraseña = isset($_POST["clave"]) ? trim($_POST["clave"]) : null;
    $confirmarPassword = isset($_POST["confirmClave"]) ? trim($_POST["confirmClave"]) : null;

    // Variables para almacenar errores
    $errores = [];

    // Validaciones
    if (empty($nombre)) {
        $errores[] = "El nombre no puede estar vacío.";
    } elseif (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚ ]+$/', $nombre)) {
        $errores[] = "El nombre solo puede contener letras y espacios.";
    }

    if (empty($apellido)) {
        $errores[] = "El apellido no puede estar vacío.";
    } elseif (!preg_match('/^[a-zA-ZáéíóúÁÉÍÓÚ ]+$/', $apellido)) {
        $errores[] = "El apellido solo puede contener letras y espacios.";
    }

    if (empty($correo)) {
        $errores[] = "El correo no puede estar vacío.";
    } elseif (!preg_match('/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $correo)) {
        $errores[] = "El correo no es válido.";
    }

    if (empty($fecha_nacimiento)) {
        $errores[] = "La fecha de nacimiento no puede estar vacía.";
    } else {
        $fechaNacimientoDate = new DateTime($fecha_nacimiento);
        $hoy = new DateTime();
        $edad = $hoy->diff($fechaNacimientoDate)->y;
        
        if ($fechaNacimientoDate > $hoy) {
            $errores[] = "La fecha de nacimiento no puede ser del futuro.";
        } elseif ($edad < 16) {
            $errores[] = "Debes tener al menos 16 años.";
        }
    }

    if (empty($contraseña) || strlen($contraseña) < 4) {
        $errores[] = "La contraseña debe tener al menos 4 caracteres.";
    }

    if ($contraseña !== $confirmarPassword) {
        $errores[] = "Las contraseñas no coinciden.";
    }

    // Verificar si el correo ya existe
    try {
        $queryUsuario = "SELECT COUNT(*) FROM usuarios WHERE correo = :correo";
        $stmUsuario = $conn->prepare($queryUsuario);
        $stmUsuario->bindParam(':correo', $correo);
        $stmUsuario->execute();
        $existeUsuario = $stmUsuario->fetchColumn();

        if ($existeUsuario > 0) {
            $errores[] = "El correo ya está en uso.";
        }
    } catch (PDOException $e) {
        $errores[] = "Error al verificar el correo en la base de datos.";
    }

    // Si hay errores, devolverlos
    if (!empty($errores)) { 
        echo json_encode(['status' => 'error', 'message' => $errores]);
        exit();
    }

    // Si no hay errores, proceder a registrar
    $contraseñaHash = password_hash($contraseña, PASSWORD_BCRYPT);

    try {
        // Insertar el nuevo estudiante en la tabla de usuarios
        $stmt = $conn->prepare("INSERT INTO usuarios (nombre, apellido, correo, fecha_nacimiento, contrasena) VALUES (:nombre, :apellido, :correo, :fecha_nacimiento, :clave)");
        $stmt->bindParam(':nombre', $nombre); 
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':fecha_nacimiento', $fecha_nacimiento);
        $stmt->bindParam(':clave', $contraseñaHash);
        
        $resultado = $stmt->execute();

        if ($resultado) {
            // Obtener el ID del usuario recién insertado
            $id_usuario = $conn->lastInsertId();

            // Asignar el rol de 'estudiante' al usuario
            $id_rol_estudiante = 3;  // ID del rol de estudiante según tu tabla de roles
            $stmtRol = $conn->prepare("INSERT INTO roles_usuarios (id_rol, id_usuario) VALUES (:id_rol, :id_usuario)");
            $stmtRol->bindParam(':id_rol', $id_rol_estudiante);
            $stmtRol->bindParam(':id_usuario', $id_usuario);
            $stmtRol->execute();

            echo json_encode(['status' => 'success', 'message' => 'Registro exitoso.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al registrar el usuario.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error al registrar en la base de datos.']);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Método de solicitud no válido.']);
}

?>
