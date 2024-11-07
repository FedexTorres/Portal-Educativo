<?php
session_start();
require 'conexion_bbdd.php';
header('Content-Type: application/json');

// Verificar que el usuario esté logueado
if (!isset($_SESSION['usuario']['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No estás logueado']);
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nombre = isset($_POST["nombre"]) ? trim($_POST["nombre"]) : null;
    $apellido = isset($_POST["apellido"]) ? trim($_POST["apellido"]) : null;
    $correo = isset($_POST["correo"]) ? trim($_POST["correo"]) : null;
    $fecha_nacimiento = isset($_POST["fechaNacimiento"]) ? trim($_POST["fechaNacimiento"]) : null;
    $contraseña = isset($_POST["clave"]) ? trim($_POST["clave"]) : null;
    $confirmarPassword = isset($_POST["claveConfirmacion"]) ? trim($_POST["claveConfirmacion"]) : null;
    $id_usuario = $_SESSION['usuario']['id'];

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

    // Validación de contraseña si se quiere actualizar
    if (!empty($contraseña) && strlen($contraseña) < 4) {
        $errores[] = "La contraseña debe tener al menos 4 caracteres.";
    }
    if (!empty($contraseña) && $contraseña !== $confirmarPassword) {
        $errores[] = "Las contraseñas no coinciden.";
    }

    // Verificar si el correo ya existe
    try {
        $queryUsuario = "SELECT COUNT(*) FROM usuarios WHERE correo = :correo AND id != :id";
        $stmUsuario = $conn->prepare($queryUsuario);
        $stmUsuario->bindParam(':correo', $correo);
        $stmUsuario->bindParam(':id', $id_usuario, PDO::PARAM_INT);
        $stmUsuario->execute();
        if ($stmUsuario->fetchColumn() > 0) {
            $errores[] = "El correo ya está en uso.";
        }
    } catch (PDOException $e) {
        $errores[] = "Error al verificar el correo en la base de datos.";
    }

    if (!empty($errores)) {
        echo json_encode(['status' => 'error', 'message' => $errores]);
        exit();
    }

    try {
        $query = "UPDATE usuarios SET nombre = :nombre, apellido = :apellido, correo = :correo, fecha_nacimiento = :fecha_nacimiento";
        if (!empty($contraseña)) {
            $query .= ", contrasena = :contrasena";
            $contraseñaHash = password_hash($contraseña, PASSWORD_DEFAULT);
        }
        $query .= " WHERE id = :id";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(':nombre', $nombre);
        $stmt->bindParam(':apellido', $apellido);
        $stmt->bindParam(':correo', $correo);
        $stmt->bindParam(':fecha_nacimiento', $fecha_nacimiento);
        $stmt->bindParam(':id', $id_usuario);

        if (!empty($contraseña)) {
            $stmt->bindParam(':contrasena', $contraseñaHash);
        }

        $resultado = $stmt->execute();

        if ($resultado) {
            echo json_encode(['status' => 'success', 'message' => 'Perfil actualizado exitosamente.']);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'Error al actualizar el perfil.']);
        }
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error al actualizar en la base de datos.']);
    }

} else {
    echo json_encode(['status' => 'error', 'message' => 'Método de solicitud no válido.']);
}
?>
