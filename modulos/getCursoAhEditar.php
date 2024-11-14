<?php

require('../modulos/conexion_bbdd.php');

if (isset($_POST["id"])) {
    
    $cursoId = $_POST["id"];

    try {
        $query = "SELECT * 
        FROM cursos
        WHERE id=:id";
    
        $stm = $conn->prepare($query);
        $stm->bindParam(':id', $cursoId);

        $stm->execute();
        $curso = $stm->fetchAll(PDO::FETCH_ASSOC);
    
        echo json_encode(['status' => 'success', 'data' => $curso]);
    
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Problema con el curso a traer']);
    }
}else {
    echo json_encode(['status' => 'error', 'message' => 'No se envio ningun usuario']);
}




