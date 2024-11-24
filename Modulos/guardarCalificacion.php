<?php
session_start();

require './conexion_bbdd.php';  
require_once './permisos.php';
header('Content-Type: application/json');

// Verifica si el usuario está logueado
if (!isset($_SESSION['usuario']['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No estás autenticado']);
    exit();
}
// Verificar permisos
if (!Permisos::tienePermiso('Calificar', $_SESSION['usuario']['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No tienes permiso para calificar, cerrando...']);
    exit;
}


$data = json_decode(file_get_contents("php://input"), true);
$idEntrega = isset($data['id_entrega']) ? (int)$data['id_entrega'] : 0;
$calificacion = isset($data['calificacion']) ? (float)$data['calificacion'] : null;

// Validar datos de entrada
if ($idEntrega === 0 || $calificacion === null) {
    echo json_encode(['status' => 'error', 'message' => 'ID de entrega o calificación no proporcionada']);
    exit;
}

try {
    // Obtener id_actividad e id_usuario desde la tabla de entregas para luego pasarlas a la proxima query.
    $query = "SELECT id_actividad, id_usuario FROM entregas WHERE id = :idEntrega";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':idEntrega', $idEntrega, PDO::PARAM_INT);
    $stmt->execute();
    $entrega = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$entrega) {
        echo json_encode(['status' => 'error', 'message' => 'Entrega no encontrada']);
        exit;
    }

    $idActividad = $entrega['id_actividad'];
    $idUsuario = $entrega['id_usuario'];

    // Insertar o actualizar en calificaciones
    $query = "INSERT INTO calificaciones (id_actividad, id_usuario, id_entrega, calificacion) 
              VALUES (:idActividad, :idUsuario, :idEntrega, :calificacion)
              ON DUPLICATE KEY UPDATE calificacion = :calificacion";

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':idActividad', $idActividad, PDO::PARAM_INT);
    $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);
    $stmt->bindParam(':idEntrega', $idEntrega, PDO::PARAM_INT);
    $stmt->bindParam(':calificacion', $calificacion, PDO::PARAM_STR);

    $stmt->execute();

    echo json_encode(['status' => 'success', 'message' => 'Calificación guardada exitosamente']);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error al guardar la calificación: ' . $e->getMessage()]);
}
