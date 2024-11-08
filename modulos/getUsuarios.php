<?php

require('../modulos/conexion_bbdd.php');



$query = "SELECT usuarios.nombre as nombre, usuarios.correo, r.nombre as rol, usuarios.id 
FROM usuarios 
JOIN roles_usuarios ru ON usuarios.id = ru.id_usuario
JOIN roles r ON ru.id_rol = r.id";

$stm = $conn->prepare($query);

$stm->execute();
$reclamos = $stm->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($reclamos);