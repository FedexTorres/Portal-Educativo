<?php
session_start();
require './conexion_bbdd.php'; 
require_once './permisos.php';

if (!isset($_SESSION['usuario']['id'])) {
    header('Location: login.php');  // redirige al login si no hay sesión
    exit;
}

// Validar el permiso "Enviar mensajes"
if (!Permisos::tienePermiso('Enviar mensajes', $_SESSION['usuario']['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No tienes permiso para enviar mensajes']);
    exit;
}

// Validar que se reciban los parámetros requeridos
if (isset($_POST['destinatario_id']) && isset($_POST['mensaje'])) {
    $destinatarioId = trim($_POST['destinatario_id']);
    $mensaje = trim($_POST['mensaje']);
    $remitenteId = $_SESSION['usuario']['id']; // ID del remitente desde la sesión

    try {
        $query = "INSERT INTO mensajes (contenido, id_remitente, id_destinatario) VALUES (:contenido, :remitente_id, :destinatario_id)";
        $stmt = $conn->prepare($query);

        // Vincular parámetros
        $stmt->bindParam(':contenido', $mensaje);
        $stmt->bindParam(':remitente_id', $remitenteId, PDO::PARAM_INT); 
        $stmt->bindParam(':destinatario_id', $destinatarioId, PDO::PARAM_INT);       
        $stmt->execute(); // Ejecuta la consulta

        // Respuesta exitosa
        echo json_encode(['status' => 'success', 'message' => 'Mensaje enviado correctamente, cerrando...']);
    } catch (PDOException $e) {
        // Error en la base de datos
        echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
    }
} else {
    // Falta algún dato requerido
    echo json_encode(['status' => 'error', 'message' => 'Faltan datos requeridos.']);
}
?>
