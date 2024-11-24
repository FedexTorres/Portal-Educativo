<?php
session_start();
require_once './conexion_bbdd.php';
require_once './permisos.php';

header('Content-Type: application/json');


// Validar que el usuario esté logueado
if (!isset($_SESSION['usuario']['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No estás autenticado']);
    exit;
}

// Validar el permiso "Ver material estudio estudiante"
if (!Permisos::tienePermiso('Ver material estudio estudiante',$_SESSION['usuario']['id'] )) {
    echo json_encode(['status' => 'error', 'message' => 'No tienes permiso para acceder a los materiales de estudio']);
    exit;
}

// Validar el ID del curso recibido
$cursoId = isset($_GET['cursoId']) ? (int)$_GET['cursoId'] : 0;

if ($cursoId === 0) {
    echo json_encode(['status' => 'error', 'message' => 'ID de curso no proporcionado']);
    exit;
}

try {
    // Consultar los materiales de estudio asociados al curso
    $query = "
        SELECT 
            m.id AS id_material,
            m.titulo,
            m.descripcion,
            m.fecha_subida,
            m.ruta_archivo 
        FROM materiales_de_estudio m
        WHERE m.id_curso = :cursoId
    ";

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':cursoId', $cursoId, PDO::PARAM_INT);
    $stmt->execute();

    $materiales = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if (!$materiales) {
        echo json_encode(['status' => 'error', 'message' => 'No se encontraron materiales de estudio para este curso']);
        exit;
    }

    echo json_encode(['status' => 'success', 'data' => $materiales]);
} catch (PDOException $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error al obtener los materiales de estudio: ' . $e->getMessage()]);
}
