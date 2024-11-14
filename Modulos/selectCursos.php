<?php
session_start();
require './conexion_bbdd.php';

if (!isset($_SESSION['usuario']['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No estás logueado']);
    exit;
}

$id_profesor = $_SESSION['usuario']['id'];

try {
    $query = "SELECT c.id, c.nombre
FROM cursos c
INNER JOIN cursos_usuarios cu ON c.id = cu.id_curso
INNER JOIN usuarios u ON cu.id_usuario = u.id
WHERE u.id = :id_profesor;
";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_profesor', $id_profesor, PDO::PARAM_INT);
    $stmt->execute();
    $cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($cursos) {
        echo json_encode(['status' => 'success', 'data' => $cursos]);
    } else {
        echo json_encode(['status' => 'info', 'message' => 'No tienes cursos disponibles']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Ocurrió un error al cargar los cursos']);
}
?>
