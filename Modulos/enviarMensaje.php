<?php
require './conexion_bbdd.php'; // Asegúrate de que este archivo exista

// Validar que se reciban los parámetros requeridos
if (isset($_POST['destinatario_id']) && isset($_POST['mensaje']) && isset($_POST['remitente_id'])) {
    $destinatarioId = trim($_POST['destinatario_id']);
    $mensaje = trim($_POST['mensaje']);
    $remitenteId = trim($_POST['remitente_id']); // ID del remitente

    try {
        // Preparar la consulta SQL
        $stmt = $conn->prepare("INSERT INTO mensajes (contenido, id_remitente, id_destinatario) VALUES (:contenido, :remitente_id, :destinatario_id)");

        // Vincular parámetros
        $stmt->bindParam(':contenido', $mensaje);
        $stmt->bindParam(':remitente_id', $remitenteId, PDO::PARAM_INT);
        $stmt->bindParam(':destinatario_id', $destinatarioId, PDO::PARAM_INT);

        // Ejecutar la consulta
        $stmt->execute();

        // Respuesta exitosa
        echo json_encode(['status' => 'success', 'message' => 'Mensaje enviado correctamente.']);
    } catch (PDOException $e) {
        // Error en la base de datos
        echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
    }
} else {
    // Falta algún dato requerido
    echo json_encode(['status' => 'error', 'message' => 'Faltan datos requeridos.']);
}
?>
