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
if (!Permisos::tienePermiso('Modificar asistencia', $_SESSION['usuario']['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No tienes permiso para modificar asistencia']);
    exit;
}
// Asegurarse de que el ID del curso esté presente
if (isset($_GET['idCurso'])) {
    $idCurso = $_GET['idCurso'];

    try {
        // Preparamos la consulta SQL
        $query = "
            SELECT 
                af.id AS id_asistencia,
                af.fecha,
                c.nombre AS nombre_curso
            FROM 
                asistencias_fechas af
            JOIN 
                cursos c ON af.id_curso = c.id
            WHERE 
                af.id_curso = :idCurso
            ORDER BY 
                af.fecha DESC
        ";

        // Ejecutamos la consulta
        $stmt = $conn->prepare($query);
        $stmt->bindParam(':idCurso', $idCurso, PDO::PARAM_INT);
        $stmt->execute();

        // Recuperar los resultados
        $asistencias = $stmt->fetchAll(PDO::FETCH_ASSOC);

        // Verificar si hay datos
        if ($asistencias) {
            echo json_encode(['status' => 'success', 'data' => $asistencias]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se encontraron asistencias para este curso']);
        }
    } catch (PDOException $e) {
        // Capturar errores de la base de datos
        echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos: ' . $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Falta el ID del curso']);
}
?>
