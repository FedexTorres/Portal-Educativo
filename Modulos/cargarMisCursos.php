<?php
session_start();
require './conexion_bbdd.php';

if (!isset($_SESSION['usuario']['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No estás logueado']);
    exit;
}

$id_usuario = $_SESSION['usuario']['id'];

try {
    // Consulta para obtener los cursos donde el usuario está inscrito
    $query = "SELECT c.id, c.nombre, c.descripcion, c.fecha_inicio, c.fecha_fin
              FROM cursos c
              JOIN inscripciones i ON c.id = i.id_curso
              WHERE i.id_usuario = :id_usuario";

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_usuario', $id_usuario, PDO::PARAM_INT);
    $stmt->execute();
    $cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($cursos) {
        echo json_encode(['status' => 'success', 'data' => $cursos]);
    } else {
        echo json_encode(['status' => 'info', 'message' => 'No estás inscrito en ningún curso']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Ocurrió un error al cargar los cursos']);
}
?>
