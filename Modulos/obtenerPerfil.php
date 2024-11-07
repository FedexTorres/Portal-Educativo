<?php
session_start();
header("Content-Type: application/json; charset=UTF-8");
require 'conexion_bbdd.php';
// Verificar que el usuario esté logueado
if (!isset($_SESSION['usuario']['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No estás logueado']);
    exit;
}

try {
    // Preparar la consulta
    $query = "SELECT nombre, apellido, correo, fecha_nacimiento FROM usuarios WHERE id = :id"; //Aqui no se debe traer la contraseña tambien?
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id', $_SESSION['usuario']['id'], PDO::PARAM_INT); // Acceso correcto al ID
    $stmt->execute();

    // Obtener el resultado
    $usuario = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($usuario) {
        // Devolver los datos en formato JSON
        echo json_encode(['status' => 'success', 'usuario' => $usuario]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No se encontró el perfil']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos']);
}
?>
