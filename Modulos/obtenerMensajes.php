<?php
require './conexion_bbdd.php'; // Asegúrate de que este archivo exista

session_start();
if (!isset($_SESSION['usuario']['id'])) {
    header('Location: index.php');  // redirige al login si no hay sesión
    exit;
}

// Obtener el ID del destinatario desde la sesión
$destinatarioId = $_SESSION['usuario']['id'];

try {
    // Preparar la consulta SQL
    $query = "SELECT m.contenido, m.fecha, u.nombre AS remitente 
              FROM mensajes m 
              JOIN usuarios u ON m.id_remitente = u.id 
              WHERE m.id_destinatario = :destinatario_id
              ORDER BY m.fecha DESC";
    $stmt = $conn->prepare($query);
    
    // Vincular parámetros
    $stmt->bindParam(':destinatario_id', $destinatarioId, PDO::PARAM_INT);

    // Ejecutar la consulta
    $stmt->execute();
    $mensajes = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Devolver la respuesta exitosa con los mensajes
    echo json_encode(['status' => 'success', 'mensajes' => $mensajes]);
} catch (PDOException $e) {
    // Error en la base de datos
    echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
}
?>
