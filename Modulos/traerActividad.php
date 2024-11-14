<?php
session_start();
require './conexion_bbdd.php';

// Comprobamos si `idActividad` está presente en la solicitud
if (isset($_GET['idActividad'])) {
    $idActividad = $_GET['idActividad'];
} else {
    echo json_encode(['status' => 'error', 'message' => 'ID de actividad no proporcionado']);
    exit;
}
// if (!isset($_SESSION['usuario']['id'])) {
//     echo json_encode(['status' => 'error', 'message' => 'No estás logueado']);
//     exit;
// }
//$idActividad = $_GET['idActividad']; // Captura el ID de la actividad desde el frontend
try {
    // $query = "SELECT a.id, a.nombre AS actividad_nombre, a.consigna, a.fecha_limite, 
    //                 c.id AS curso_id, c.nombre AS curso_nombre
    //                 FROM actividades a
    //                 INNER JOIN cursos c ON a.id_curso = c.id
    //                 INNER JOIN cursos_usuarios cu ON cu.id_curso = c.id
    //                 INNER JOIN usuarios u ON cu.id_usuario = u.id
    //                 WHERE u.id = :id_profesor AND a.id = :idActividad";

    $query = "SELECT * FROM actividades WHERE id = :idActividad";

    $stmt = $conn->prepare($query);
    $stmt->bindParam(':idActividad', $idActividad, PDO::PARAM_INT);
    $stmt->execute();
    $actividad = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($actividad) {
        echo json_encode(['status' => 'success', 'data' => $actividad]);
    } else {
        echo json_encode(['status' => 'info', 'message' => 'No se encontró la actividad']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error al cargar la actividad']);
}
?>
