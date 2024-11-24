<?php
session_start();
require './conexion_bbdd.php';
require_once './permisos.php';

header('Content-Type: application/json');

// Validar que el usuario esté logueado
if (!isset($_SESSION['usuario']['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No estás logueado']);
    exit;
}

// Validar el permiso "Ver material estudio estudiante"
if (!Permisos::tienePermiso('Crear actividad',$_SESSION['usuario']['id'] )) {
    echo json_encode(['status' => 'error', 'message' => 'No tienes permiso para crear actividades']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);

// Validar campos obligatorios
if (empty($data['nombre']) || empty($data['consigna']) || empty($data['fecha_limite']) || empty($data['id_curso'])) {
    echo json_encode(['status' => 'error', 'message' => 'Todos los campos son obligatorios']);
    exit;
}

// Validar fecha límite: no puede ser del pasado ni vacía
$fecha_limite = $data['fecha_limite'];
$fecha_actual = date('Y-m-d');

if ($fecha_limite < $fecha_actual) {
    echo json_encode(['status' => 'error', 'message' => 'La fecha límite no puede ser anterior a hoy']);
    exit;
}

// Validar que el nombre de la actividad no esté vacío y no contenga caracteres especiales
$nombre = trim($data['nombre']);
if (!preg_match('/^[a-zA-Z0-9\sáéíóúÁÉÍÓÚñÑ]+$/', $nombre)) {
    echo json_encode(['status' => 'error', 'message' => 'El nombre de la actividad solo puede contener letras, números y espacios']);
    exit;
}


// Validar que el texto de la consigna no esté vacío
$consigna = trim($data['consigna']);
if (empty($consigna)) {
    echo json_encode(['status' => 'error', 'message' => 'La consigna no puede estar vacía']);
    exit;
}

// Validar que el curso seleccionado sea válido
$id_curso = (int)$data['id_curso'];
if ($id_curso <= 0) {
    echo json_encode(['status' => 'error', 'message' => 'Debes seleccionar un curso válido']);
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
