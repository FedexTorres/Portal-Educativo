<?php
session_start();

require './conexion_bbdd.php';  
require_once './permisos.php';

// Verifica si el usuario está logueado
if (!isset($_SESSION['usuario']['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No estás autenticado']);
    exit();
}
// Verificar permisos
if (!Permisos::tienePermiso('Tomar asistencia', $_SESSION['usuario']['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No tienes permiso para tomar asistencia.']);
    exit;
}


// Decodificar datos enviados desde JavaScript
$data = json_decode(file_get_contents("php://input"), true);
$id_curso = $data['idCurso'];
$fecha = $data['fecha'];
$asistencias = $data['asistencias'];

try {
    // Validar que la fecha esté dentro del rango del curso
    $query = "SELECT fecha_inicio, fecha_fin FROM cursos WHERE id = :id_curso";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':id_curso', $id_curso, PDO::PARAM_INT);
    $stmt->execute();
    $curso = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$curso) {
        echo json_encode(['status' => 'error', 'message' => 'El curso no existe']);
        exit;
    }

    $fecha_inicio = $curso['fecha_inicio'];
    $fecha_fin = $curso['fecha_fin'];
    date_default_timezone_set('America/Argentina/Buenos_Aires'); 
    $fecha_actual = date('Y-m-d'); // Fecha actual del servidor
    // Validaciones de fecha
    if ($fecha < $fecha_inicio || $fecha > $fecha_fin) {
        echo json_encode(['status' => 'error', 'message' => 'La fecha está fuera del rango permitido para este curso.']);
        exit;
    }

    if ($fecha > $fecha_actual) {
        echo json_encode(['status' => 'error', 'message' => 'No puedes registrar asistencias para fechas futuras.']);
        exit;
    }

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
          VALUES (:id_asistencia_fecha, :id, :estado)";

        // $query = "INSERT INTO asistencias_usuarios (id_asistencia_fecha, id_usuario, estado) 
        //           VALUES (:id_asistencia_fecha, :id_usuario, :estado)";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':id_asistencia_fecha', $id_asistencia_fecha, PDO::PARAM_INT);
        // $stmt->bindParam(':id_usuario', $asistencia['id_alumno'], PDO::PARAM_INT);
        $stmt->bindParam(':id', $asistencia['id'], PDO::PARAM_INT);  // Cambio aquí de id_usuario a id
        $stmt->bindParam(':estado', $asistencia['estado'], PDO::PARAM_STR);
        $stmt->execute();
    }

    echo json_encode(['status' => 'success', 'message' => 'Asistencia registrada con éxito']);
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error al registrar la asistencia','error_details' => $e->getMessage() ]);
}
?>
