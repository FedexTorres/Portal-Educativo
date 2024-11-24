<?php
session_start();

require './conexion_bbdd.php';  
require_once './permisos.php';
header('Content-Type: application/json');

// Verifica si el usuario está logueado
if (!isset($_SESSION['usuario']['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No estás autenticado']);
    exit();
}
// Verificar permisos
if (!Permisos::tienePermiso('Calificar', $_SESSION['usuario']['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No tienes permiso para ver las entregas.']);
    exit;
}

// Obtener el ID del profesor desde la sesión
$idProfesor = isset($_SESSION['usuario']['id']) ? (int)$_SESSION['usuario']['id'] : 0;

if ($idProfesor === 0) {
    echo json_encode(['status' => 'error', 'message' => 'ID de profesor no proporcionado']);
    exit;
}

try {
    // Consulta para obtener todas las actividades asociadas a los cursos del profesor logueado
    $query = "SELECT e.id AS id_entrega, a.nombre AS actividad, e.fecha_entrega, e.ruta_archivo, c.nombre AS curso
          FROM entregas e
          JOIN actividades a ON e.id_actividad = a.id
          JOIN cursos c ON a.id_curso = c.id
          JOIN cursos_usuarios cu ON c.id = cu.id_curso
          WHERE cu.id_usuario = :idProfesor";

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':idProfesor', $idProfesor, PDO::PARAM_INT);
    $stmt->execute();

    $entregas = $stmt->fetchAll(PDO::FETCH_ASSOC);

    echo json_encode(['status' => 'success', 'data' => $entregas]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error al obtener las entregas: ' . $e->getMessage()]);
}
