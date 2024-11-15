<?php
session_start();
require 'conexion_bbdd.php';

if (!isset($_SESSION['usuario']['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No estás logueado']);
    exit;
}

$id_usuario = $_SESSION['usuario']['id'];
$data = json_decode(file_get_contents("php://input"), true);

$id_curso = $data['idCurso'];
$fecha = $data['fecha'];
$asistencias = $data['asistencias'];

try {
    // Guardar la fecha de asistencia
    $query = "INSERT INTO asistencias_fechas (id_curso, fecha) VALUES (:id_curso, :fecha)";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_curso', $id_curso, PDO::PARAM_INT);
    $stmt->bindParam(':fecha', $fecha, PDO::PARAM_STR);
    $stmt->execute();
    $id_asistencia_fecha = $conn->lastInsertId();

    // Guardar la asistencia de cada alumno
    foreach ($asistencias as $asistencia) {
        $query = "INSERT INTO asistencias_usuarios (id_asistencia_fecha, id_usuario, estado) 
                  VALUES (:id_asistencia_fecha, :id_usuario, :estado)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id_asistencia_fecha', $id_asistencia_fecha, PDO::PARAM_INT);
        $stmt->bindParam(':id_usuario', $asistencia['id_alumno'], PDO::PARAM_INT);
        $stmt->bindParam(':estado', $asistencia['estado'], PDO::PARAM_STR);
        $stmt->execute();
    }

    echo json_encode(['status' => 'success', 'message' => 'Asistencia registrada con éxito']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error al registrar la asistencia']);
}
?>
