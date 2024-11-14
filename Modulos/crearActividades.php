<?php
session_start();
require './conexion_bbdd.php';

if (!isset($_SESSION['usuario']) || $_SESSION['usuario']['rol'] !== 'profesor') {
    echo json_encode(['status' => 'error', 'message' => 'Acceso denegado']);
    exit;
}

header('Content-Type: application/json');

$data = json_decode(file_get_contents('php://input'), true);

// Validar campos obligatorios
if (empty($data['nombre']) || empty($data['consigna']) || empty($data['fecha_limite']) || empty($data['id_curso'])) {
    echo json_encode(['status' => 'error', 'message' => 'Todos los campos son obligatorios']);
    exit;
}

$nombre = htmlspecialchars($data['nombre']);
$consigna = htmlspecialchars($data['consigna']);
$fecha_limite = $data['fecha_limite'];
$id_curso = (int)$data['id_curso'];

try {
    $query = "INSERT INTO actividades (nombre, consigna, fecha_limite, id_curso) 
              VALUES (:nombre, :consigna, :fecha_limite, :id_curso)";

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':nombre', $nombre, PDO::PARAM_STR);
    $stmt->bindParam(':consigna', $consigna, PDO::PARAM_STR);
    $stmt->bindParam(':fecha_limite', $fecha_limite, PDO::PARAM_STR);
    $stmt->bindParam(':id_curso', $id_curso, PDO::PARAM_INT);
    $stmt->execute();

    echo json_encode(['status' => 'success', 'message' => 'Actividad creada exitosamente']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error al crear la actividad']);
}
