<?php
session_start();

require './conexion_bbdd.php';  
require_once './permisos.php';

// Verifica si el usuario está logueado
if (!isset($_SESSION['usuario']['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No estás autenticado']);
    exit();
}
// Verificar permisos
if (!Permisos::tienePermiso('Tomar asistencia', $_SESSION['usuario']['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No tienes permiso para tomar asistencia.']);
    exit;
}

// Recibimos el JSON desde el frontend
$data = json_decode(file_get_contents('php://input'), true);

// Verificamos que el id_curso esté presente
if (!isset($data['id_curso'])) {
    echo json_encode(['status' => 'error', 'message' => 'Falta el parámetro id_curso']);
    exit;
}

$id_usuario = $_SESSION['usuario']['id']; // ID del usuario logueado (el profesor)
$id_curso = $data['id_curso'];  // Curso seleccionado

try {
    $query = "SELECT u.id, u.nombre, u.apellido 
            FROM usuarios u 
            JOIN inscripciones i ON u.id = i.id_usuario
            WHERE i.id_curso = :id_curso AND u.id != :id_usuario";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_curso', $id_curso, PDO::PARAM_INT);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();
    $alumnos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($alumnos) {
        echo json_encode(['status' => 'success', 'data' => $alumnos]);
    } else {
        echo json_encode(['status' => 'info', 'message' => 'No hay alumnos inscritos en este curso']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error al cargar los alumnos', 'error_details' => $e->getMessage()]);
}
?>

