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
