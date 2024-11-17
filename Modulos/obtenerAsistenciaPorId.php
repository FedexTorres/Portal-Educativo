<?php
session_start();
require 'conexion_bbdd.php';

if (!isset($_GET['idAsistencia'])) {
    echo json_encode(['status' => 'error', 'message' => 'No se proporcionó el ID de asistencia']);
    exit;
}

$idAsistencia = intval($_GET['idAsistencia']);

try {
    // Obtener los datos de la asistencia (fecha, curso e id_curso)
    $queryAsistencia = $conn->prepare("
        SELECT af.id AS id_asistencia, af.fecha, c.id AS id_curso, c.nombre AS nombre_curso
        FROM asistencias_fechas af
        JOIN cursos c ON af.id_curso = c.id
        WHERE af.id = :idAsistencia
    ");
    $queryAsistencia->bindParam(':idAsistencia', $idAsistencia, PDO::PARAM_INT);
    $queryAsistencia->execute();

    $asistencia = $queryAsistencia->fetch(PDO::FETCH_ASSOC);
    if (!$asistencia) {
        echo json_encode(['status' => 'error', 'message' => 'Asistencia no encontrada']);
        exit;
    }

    // Obtener los estados de los alumnos
    $queryAlumnos = $conn->prepare("
        SELECT au.id_usuario AS id_alumno, u.nombre, u.apellido, au.estado
        FROM asistencias_usuarios au
        JOIN usuarios u ON au.id_usuario = u.id
        WHERE au.id_asistencia_fecha = :idAsistencia
    ");
    $queryAlumnos->bindParam(':idAsistencia', $idAsistencia, PDO::PARAM_INT);
    $queryAlumnos->execute();

    $alumnos = $queryAlumnos->fetchAll(PDO::FETCH_ASSOC);

    // Verificar si no hay alumnos
    if (empty($alumnos)) {
        error_log("No se encontraron alumnos para id_asistencia_fecha: $idAsistencia");
    }

    echo json_encode([
        'status' => 'success',
        'data' => [
            'id_asistencia' => $asistencia['id_asistencia'],
            'fecha' => $asistencia['fecha'],
            'id_curso' => $asistencia['id_curso'], // Incluir el ID del curso
            'nombre_curso' => $asistencia['nombre_curso'],
            'alumnos' => $alumnos,
        ],
    ]);
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Error al obtener la asistencia',
        'error_details' => $e->getMessage(),
    ]);
    error_log("Error al obtener asistencia: " . $e->getMessage());
}
