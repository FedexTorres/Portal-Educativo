<?php
session_start();
require 'conexion_bbdd.php';

if (!isset($_SESSION['usuario']['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No estás logueado']);
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
    // Consulta para obtener los alumnos inscritos en el curso, excluyendo al usuario logueado
    $query = "SELECT u.id, u.nombre, u.apellido 
              FROM usuarios u 
              JOIN cursos_usuarios cu ON u.id = cu.id_usuario 
              WHERE cu.id_curso = :id_curso AND u.id != :id_usuario";

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

