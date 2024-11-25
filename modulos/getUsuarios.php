<?php
session_start();

require './conexion_bbdd.php';
require_once './permisos.php';

header('Content-Type: application/json');

if (!isset($_SESSION['usuario']['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No estás autenticado']);
    exit;
}

if (!Permisos::tienePermiso('Visualizar usuarios',$_SESSION['usuario']['id'] )) {
    echo json_encode(['status' => 'error', 'message' => 'No tienes permiso para visualizar los usuarios']);
    exit;
}


$query = "SELECT usuarios.nombre as nombre, usuarios.correo, r.nombre as rol, usuarios.id 
FROM usuarios 
JOIN roles_usuarios ru ON usuarios.id = ru.id_usuario
JOIN roles r ON ru.id_rol = r.id";

$stm = $conn->prepare($query);

$stm->execute();
$reclamos = $stm->fetchAll(PDO::FETCH_ASSOC);
echo json_encode($reclamos);