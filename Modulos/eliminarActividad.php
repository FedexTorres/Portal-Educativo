<?php
session_start();
require './conexion_bbdd.php';

if (!isset($_SESSION['usuario']['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No estás logueado']);
    exit;
}

$idActividad = $_POST['idActividad'] ?? null;

if (!$idActividad) {
    echo json_encode(['status' => 'error', 'message' => 'ID de actividad no proporcionado']);
    exit;
}

try {
    $query = "DELETE FROM actividades WHERE id = :idActividad";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':idActividad', $idActividad, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode(['status' => 'success', 'message' => 'Actividad eliminada con éxito']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error al eliminar la actividad']);
}
?>
