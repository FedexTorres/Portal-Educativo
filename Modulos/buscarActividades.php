<?php
session_start();
require './conexion_bbdd.php';
require_once './permisos.php';

header('Content-Type: application/json');

// Validar que el usuario esté logueado
if (!isset($_SESSION['usuario']['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No estás autenticado']);
    exit;
}

// Validar el permiso "Ver material estudio estudiante"
if (!Permisos::tienePermiso('Listar actividades',$_SESSION['usuario']['id'] )) {
    echo json_encode(['status' => 'error', 'message' => 'No tienes permiso para acceder a las actividades']);
    exit;
}

$id_profesor = $_SESSION['usuario']['id'];

try {
    $query = "SELECT a.id, a.nombre AS actividad_nombre, a.consigna, a.fecha_limite, 
                    c.id AS curso_id, c.nombre AS curso_nombre
                    FROM actividades a
                    INNER JOIN cursos c ON a.id_curso = c.id
                    INNER JOIN cursos_usuarios cu ON cu.id_curso = c.id
                    INNER JOIN usuarios u ON cu.id_usuario = u.id
                    WHERE u.id = :id_profesor";

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_profesor', $id_profesor, PDO::PARAM_INT);
    $stmt->execute();
    $actividades = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($actividades) {
        echo json_encode(['status' => 'success', 'data' => $actividades]);
    } else {
        echo json_encode(['status' => 'info', 'message' => 'No hay actividades disponibles']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Ocurrió un error al cargar las actividades']);
}
?>
