<?php
session_start();
require 'conexion_bbdd.php';
require_once './permisos.php';

header('Content-Type: application/json');

if (!isset($_SESSION['usuario']['id'])) {
    header('Location: index.php');  // redirige al login si no hay sesión
    exit;
}

// Validar el permiso "Leer mensajes"
if (!Permisos::tienePermiso('Leer mensajes', $_SESSION['usuario']['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No tienes permiso para leer los mensajes']);
    exit;
}

try {
    // Obtener el ID del remitente desde la sesión
    $remitenteId = $_SESSION['usuario']['id'];
    
    $query = "SELECT m.contenido AS mensaje, u.nombre AS destinatario_nombre, m.fecha 
              FROM mensajes m
              JOIN usuarios u ON m.id_destinatario = u.id 
              WHERE m.id_remitente = :remitente_id 
              ORDER BY m.fecha DESC"; 
              
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':remitente_id', $remitenteId, PDO::PARAM_INT);
    $stmt->execute();

    $mensajesEnviados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'mensajes' => $mensajesEnviados
    ]);
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Error en la base de datos: ' . $e->getMessage()
    ]);
}
?>
