<?php
require './conexion_bbdd.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = json_decode(file_get_contents('php://input'), true);
    $correo = $data['email'] ?? '';

    if (!filter_var($correo, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(['status' => 'error', 'message' => 'Correo inválido.']);
        exit;
    }

    // Verificar si el correo existe en la base de datos
    $query = $conn->prepare("SELECT id FROM usuarios WHERE correo = :correo");
    $query->bindParam(':correo', $correo, PDO::PARAM_STR);
    $query->execute();

    if ($query->rowCount() === 0) {
        echo json_encode(['status' => 'error', 'message' => 'Correo no encontrado.']);
        exit;
    }

    // Generar token único y fecha de expiración
    $usuario = $query->fetch(PDO::FETCH_ASSOC);
    $token = bin2hex(random_bytes(32));
    $expiracion = date('Y-m-d H:i:s', strtotime('+1 hour'));

    // Guardar el token en la base de datos
    $insertToken = $conn->prepare("
    INSERT INTO tokens_recuperacion (id_usuario, token, fecha_expiracion) 
    VALUES (:id_usuario, :token, :fecha_expiracion)
    ");
    $insertToken->bindParam(':id_usuario', $usuario['id'], PDO::PARAM_INT);
    $insertToken->bindParam(':token', $token, PDO::PARAM_STR);
    $insertToken->bindParam(':fecha_expiracion', $expiracion, PDO::PARAM_STR);
    $insertToken->execute();


    echo json_encode(['status' => 'success', 'message' => 'Token generado correctamente.', 'token' => $token]);
    exit;
}
?>
