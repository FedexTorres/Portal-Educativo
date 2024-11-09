<?php

require('../modulos/conexion_bbdd.php');

if (isset($_POST["userId"])) {
    
    $usuarioId = $_POST["userId"];

    try {
        $query = "SELECT usuarios.nombre as nombre, usuarios.apellido, usuarios.correo, 
        usuarios.fecha_nacimiento, r.nombre as rol 
        FROM usuarios 
        JOIN roles_usuarios ru ON usuarios.id = ru.id_usuario
        JOIN roles r ON ru.id_rol = r.id
        WHERE usuarios.id=:idusuario";
    
        $stm = $conn->prepare($query);
        $stm->bindParam(':idusuario', $usuarioId);

        $stm->execute();
        $reclamos = $stm->fetchAll(PDO::FETCH_ASSOC);
    
        echo json_encode($reclamos);
    
    } catch (Exception $e) {
        echo json_encode(['status' => 'error', 'message' => 'Problema con el usuario a traer']);
    }
}else {
    echo json_encode(['status' => 'error', 'message' => 'No se envio ningun usuario']);
}




