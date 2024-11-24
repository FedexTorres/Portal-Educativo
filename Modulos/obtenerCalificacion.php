<?php
session_start();
require './conexion_bbdd.php';
require_once './permisos.php';

// Verificar que el usuario está logueado
if (!isset($_SESSION['usuario']['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No estás logueado']);
    exit;
}
// Verificar que el usuario tiene permiso para consultar calificaciones
if (!Permisos::tienePermiso('Consultar calificacion estudiante', $_SESSION['usuario']['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No tienes permiso para consultar calificaciones']);
    exit;
}

$id_curso = $_GET['cursoId'] ?? null;

if (!$id_curso) {
    echo json_encode(['status' => 'error', 'message' => 'ID de curso no proporcionado']);
    exit;
}

try {
    // Actualizamos la consulta para obtener también el número de entrega
    $query = "
        SELECT a.nombre AS nombre_actividad, c.calificacion, e.numero_entrega
        FROM calificaciones c
        JOIN actividades a ON c.id_actividad = a.id
        JOIN entregas e ON c.id_entrega = e.id
        WHERE a.id_curso = :id_curso AND c.id_usuario = :id_usuario
    ";
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_curso', $id_curso, PDO::PARAM_INT);
    $stmt->bindParam(':id_usuario', $_SESSION['usuario']['id'], PDO::PARAM_INT);
    $stmt->execute();
    $calificaciones = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($calificaciones) {
        echo json_encode(['status' => 'success', 'data' => $calificaciones]);
    } else {
        echo json_encode(['status' => 'info', 'message' => 'No hay calificaciones disponibles para este curso']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Ocurrió un error al cargar las calificaciones']);
}
?>
