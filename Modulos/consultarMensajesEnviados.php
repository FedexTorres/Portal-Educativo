<?php
require 'conexion_bbdd.php';
header('Content-Type: application/json');

try {
    // Suponiendo que tienes una forma de obtener el id del remitente (harcodeado por ahora)
    $remitenteId = 1; // Cambia esto cuando implementes sesiones
    $query=  "SELECT m.contenido AS mensaje, u.nombre AS destinatario_nombre, m.fecha 
          FROM mensajes m
          JOIN usuarios u ON m.id_destinatario = u.id 
          WHERE m.id_remitente = :remitente_id 
          ORDER BY m.fecha DESC"; 
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':remitente_id', $remitenteId);
    $stmt->execute();

    $mensajesEnviados = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode([
        'status' => 'success',
        'mensajes' => $mensajesEnviados
    ]);
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => $e->getMessage()
    ]);
}

