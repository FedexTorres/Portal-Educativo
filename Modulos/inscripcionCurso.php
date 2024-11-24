<?php
session_start();
require './conexion_bbdd.php'; 
require './permisos.php';

// Verificar si el usuario está logueado
if (!isset($_SESSION['usuario']['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No estás logueado.']);
    exit;
}

// Verificar si el usuario tiene el permiso "Inscribirse a curso"
if (!Permisos::tienePermiso('Inscribirse a curso',$_SESSION['usuario']['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No tienes permiso para inscribirte en cursos.']);
    exit;
} 


// Validar que se reciban los parámetros requeridos para la inscripción
if (isset($_POST['curso_id']) && !empty(trim($_POST['curso_id']))) {
    $cursoId = trim($_POST['curso_id']);
    $usuarioId = $_SESSION['usuario']['id'];

    try {
        // Verificar si el usuario ya está inscrito en el curso
        $queryCheck = "SELECT 1 FROM inscripciones WHERE id_usuario = :usuario_id AND id_curso = :curso_id";
        $stmtCheck = $conn->prepare($queryCheck);
        $stmtCheck->bindParam(':usuario_id', $usuarioId, PDO::PARAM_INT);
        $stmtCheck->bindParam(':curso_id', $cursoId, PDO::PARAM_INT);
        $stmtCheck->execute();

        if ($stmtCheck->rowCount() > 0) {
            echo json_encode(['status' => 'error', 'message' => 'Ya estás inscripto en este curso.']);
            exit;
        }

        // Verificar si el curso tiene vacantes disponibles
        $queryVacantes = "SELECT vacantes FROM cursos WHERE id = :curso_id";
        $stmtVacantes = $conn->prepare($queryVacantes);
        $stmtVacantes->bindParam(':curso_id', $cursoId, PDO::PARAM_INT);
        $stmtVacantes->execute();
        $curso = $stmtVacantes->fetch(PDO::FETCH_ASSOC);

        if ($curso['vacantes'] <= 0) {
            echo json_encode(['status' => 'error', 'message' => 'No hay vacantes disponibles para este curso.']);
            exit;
        }

        // Insertar la inscripción en la base de datos
        $queryInscripcion = "INSERT INTO inscripciones (id_usuario, id_curso) VALUES (:usuario_id, :curso_id)";
        $stmtInscripcion = $conn->prepare($queryInscripcion);
        $stmtInscripcion->bindParam(':usuario_id', $usuarioId, PDO::PARAM_INT);
        $stmtInscripcion->bindParam(':curso_id', $cursoId, PDO::PARAM_INT);
        $stmtInscripcion->execute();

        // Actualizar las vacantes del curso
        $queryUpdateVacantes = "UPDATE cursos SET vacantes = vacantes - 1 WHERE id = :curso_id";
        $stmtUpdateVacantes = $conn->prepare($queryUpdateVacantes);
        $stmtUpdateVacantes->bindParam(':curso_id', $cursoId, PDO::PARAM_INT);
        $stmtUpdateVacantes->execute();

        // Respuesta exitosa
        echo json_encode(['status' => 'success', 'message' => '¡Inscripción realizada correctamente!']);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Faltan datos requeridos.']);
}
?>
