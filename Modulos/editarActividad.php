<?php
session_start();
require './conexion_bbdd.php';

if (!isset($_SESSION['usuario']['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No estás logueado']);
    exit;
}

$idActividad = $_POST['idActividad'] ?? null;
$nombre = $_POST['nombre'] ?? null;
$consigna = $_POST['consigna'] ?? null;
$fecha_limite = $_POST['fecha_limite'] ?? null;

if (!$idActividad || !$nombre || !$consigna || !$fecha_limite) {
    echo json_encode(['status' => 'error', 'message' => 'Datos incompletos']);
    exit;
}

try {
    $query = "UPDATE actividades 
              SET nombre = :nombre, consigna = :consigna, fecha_limite = :fecha_limite 
              WHERE id = :idActividad";
    
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
    $stmt->bindParam(':consigna', $consigna, PDO::PARAM_STR);
    $stmt->bindParam(':fecha_limite', $fecha_limite, PDO::PARAM_STR);
    $stmt->bindParam(':idActividad', $idActividad, PDO::PARAM_INT);
    
    $stmt->execute();
    
    echo json_encode(['status' => 'success', 'message' => 'Actividad actualizada con éxito']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error al actualizar la actividad']);
}
?>
