<?php
session_start();
header("Content-Type: application/json; charset=UTF-8");
require 'conexion_bbdd.php';

// Verificar que el usuario esté logueado
if (!isset($_SESSION['usuario']['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No estás logueado']);
    exit;
}

try {
    // Preparar la consulta para obtener todos los cursos
    $query = "SELECT id, nombre, descripcion, programa_estudios, imagen_url, fecha_inicio, fecha_fin FROM cursos";
    $stmt = $conn->prepare($query);
    $stmt->execute();

    // Obtener todos los resultados
    $cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($cursos) {
        // Devolver los datos de los cursos en formato JSON
        echo json_encode(['status' => 'success', 'cursos' => $cursos]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No se encontraron cursos']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos']);
}
?>
