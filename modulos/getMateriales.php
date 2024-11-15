<?php
header("Content-Type: application/json; charset=UTF-8");
require 'conexion_bbdd.php';

try {

    $idcurso = $_POST["id"];
    // Preparar la consulta para obtener todos los cursos
    $query = "SELECT * FROM materiales_de_estudio WHERE id_curso=:idcurso";
    

    $stmt = $conn->prepare($query);
    $stmt->bindParam("idcurso",$idcurso);

    $stmt->execute();

    // Obtener todos los resultados
    $cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($cursos) {
        // Devolver los datos de los cursos en formato JSON
        echo json_encode(['status' => 'success', 'cursos' => $cursos]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No se encuentran materiales del curso']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos']);
}
