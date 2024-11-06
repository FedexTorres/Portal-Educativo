<?php
require 'conexion_bbdd.php';

session_start();
if (!isset($_SESSION['usuario']['id'])) {
    header('Location: index.php');  // redirige al login si no hay sesión
    exit;
}

header('Content-Type: application/json');

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
