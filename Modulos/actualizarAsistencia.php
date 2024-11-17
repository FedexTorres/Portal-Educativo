<?php
session_start();
require 'conexion_bbdd.php';

$data = json_decode(file_get_contents('php://input'), true);
//var_dump($data);
if (!isset($data['idAsistencia'], $data['fecha'], $data['asistencias'])) {
    echo json_encode(['status' => 'error', 'message' => 'Datos incompletos']);
    exit;
}

$idAsistencia = intval($data['idAsistencia']);
$fecha = $data['fecha'];
$alumnos = $data['asistencias'];

try {
    // Validar que la fecha no sea futura ni fuera de rango
    $queryCurso = $conn->prepare("
        SELECT c.fecha_inicio, c.fecha_fin
        FROM asistencias_fechas af
        JOIN cursos c ON af.id_curso = c.id
        WHERE af.id = :idAsistencia
    ");
    $queryCurso->bindParam(':idAsistencia', $idAsistencia, PDO::PARAM_INT);
    $queryCurso->execute();

    $curso = $queryCurso->fetch(PDO::FETCH_ASSOC);
    if (!$curso) {
        echo json_encode(['status' => 'error', 'message' => 'Curso no encontrado']);
        exit;
    }

    if ($fecha < $curso['fecha_inicio'] || $fecha > $curso['fecha_fin']) {
        echo json_encode(['status' => 'error', 'message' => 'La fecha está fuera del rango del curso']);
        exit;
    }

    // Actualizar la fecha de la asistencia
    $updateFecha = $conn->prepare("
        UPDATE asistencias_fechas
        SET fecha = :fecha
        WHERE id = :idAsistencia
    ");
    $updateFecha->bindParam(':fecha', $fecha);
    $updateFecha->bindParam(':idAsistencia', $idAsistencia, PDO::PARAM_INT);
    $updateFecha->execute();

    // Actualizar el estado de cada alumno
    $updateAlumno = $conn->prepare("
        UPDATE asistencias_usuarios
        SET estado = :estado
        WHERE id_asistencia_fecha = :idAsistencia AND id_usuario = :idUsuario
    ");

    foreach ($alumnos as $alumno) {
        $updateAlumno->bindParam(':estado', $alumno['estado']);
        $updateAlumno->bindParam(':idAsistencia', $idAsistencia, PDO::PARAM_INT);
        $updateAlumno->bindParam(':idUsuario', $alumno['id'], PDO::PARAM_INT);
        $updateAlumno->execute();
    }

    echo json_encode(['status' => 'success', 'message' => 'Asistencia actualizada correctamente']);
} catch (Exception $e) {
    echo json_encode([
        'status' => 'error',
        'message' => 'Error al actualizar la asistencia',
        'error_details' => $e->getMessage(),
    ]);
}
