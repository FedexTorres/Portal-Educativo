<?php
session_start();
require './conexion_bbdd.php';

if (!isset($_SESSION['usuario']['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No estás logueado']);
    exit;
}

$id_curso = $_GET['cursoId'] ?? null;  // Obtiene el id del curso desde la solicitud GET

if (!$id_curso) {
    echo json_encode(['status' => 'error', 'message' => 'ID de curso no proporcionado']);
    exit;
}

try {
    // Consulta para obtener las actividades del curso específico
    $query = "SELECT id, consigna, fecha_limite 
              FROM actividades 
              WHERE id_curso = :id_curso";
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_curso', $id_curso, PDO::PARAM_INT);
    $stmt->execute();
    $actividades = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($actividades) {
        echo json_encode(['status' => 'success', 'data' => $actividades]);
    } else {
        echo json_encode(['status' => 'info', 'message' => 'No hay actividades disponibles para este curso']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Ocurrió un error al cargar las actividades']);
}
?>
