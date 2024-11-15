<?php


require './conexion_bbdd.php'; 



$query = "select * from roles";

$stm = $conn->prepare($query);

$stm->execute();
$reclamos = $stm->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($reclamos);