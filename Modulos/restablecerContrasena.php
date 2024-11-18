<?php
require './conexion_bbdd.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $token = $data['token'] ?? '';
    $nuevaContrasena = $data['newPassword'] ?? '';

    if (empty($token) || empty($nuevaContrasena)) {
        echo json_encode(['status' => 'error', 'message' => 'Token o contraseña inválidos.']);
        exit;
    }

    // Validar el formato del token
    if (!ctype_xdigit($token) || strlen($token) !== 64) {
        echo json_encode(['status' => 'error', 'message' => 'Formato de token inválido.']);
        exit;
    }
    // Validación de la nueva contraseña
    $errores = [];

    // La nueva contraseña debe tener al menos 4 caracteres
    if (strlen($nuevaContrasena) < 4) {
        $errores[] = "La contraseña debe tener al menos 4 caracteres.";
    }

    // Si la nueva contraseña no coincide con la confirmación (asumí que el campo 'confirmarPassword' también se recibe)
    $confirmarPassword = $data['confirmPassword'] ?? '';
    if ($nuevaContrasena !== $confirmarPassword) {
        $errores[] = "Las contraseñas no coinciden.";
    }

    // Si hay errores en las validaciones
    if (!empty($errores)) {
        echo json_encode(['status' => 'error', 'message' => $errores]);
        exit;
    }
    // Validar que el token sea válido y no haya expirado
    $query = $conn->prepare("
        SELECT t.id_usuario 
        FROM tokens_recuperacion t
        WHERE t.token = :token AND t.fecha_expiracion > NOW()
    ");
    $query->bindParam(':token', $token, PDO::PARAM_STR);
    $query->execute();

    if ($query->rowCount() === 0) {
        echo json_encode(['status' => 'error', 'message' => 'Token inválido o expirado.']);
        exit;
    }

    $usuario = $query->fetch(PDO::FETCH_ASSOC);

    // Encriptar la nueva contraseña
    $hash = password_hash($nuevaContrasena, PASSWORD_BCRYPT);

    // Manejo de la transacción
    $conn->beginTransaction();
    try {
        // Actualizar contraseña
        $update = $conn->prepare("UPDATE usuarios SET contrasena = :contrasena WHERE id = :id");
        $update->bindParam(':contrasena', $hash, PDO::PARAM_STR);
        $update->bindParam(':id', $usuario['id_usuario'], PDO::PARAM_INT);
        $update->execute();
    
        // Eliminar token
        $deleteToken = $conn->prepare("DELETE FROM tokens_recuperacion WHERE token = :token");
        $deleteToken->bindParam(':token', $token, PDO::PARAM_STR);
        $deleteToken->execute();
    
        $conn->commit();
        echo json_encode(['status' => 'success', 'message' => 'Contraseña actualizada correctamente.']);
    } catch (Exception $e) {
        $conn->rollBack();
        error_log("Error en restablecerContrasena: " . $e->getMessage());
        echo json_encode(['status' => 'error', 'message' => 'Ocurrió un problema al actualizar la contraseña.']);
    }
    exit;
}
?>
