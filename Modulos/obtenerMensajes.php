<?php
session_start();
require './conexion_bbdd.php';
require_once './permisos.php';


if (!isset($_SESSION['usuario']['id'])) {
    header('Location: index.php');  // redirige al login si no hay sesión
    exit;
}

// Validar el permiso "Leer mensajes"
if (!Permisos::tienePermiso('Leer mensajes', $_SESSION['usuario']['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No tienes permiso para leer los mensajes']);
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
    echo json_encode(['status' => 'info', 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
}
?>
