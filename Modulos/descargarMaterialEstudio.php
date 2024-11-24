<?php
session_start();
require './conexion_bbdd.php';  
require_once './permisos.php';

header('Content-Type: application/json');

// Validar que el usuario esté logueado
if (!isset($_SESSION['usuario']['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No estás autenticado']);
    exit;
}

// Validar el permiso "Ver material estudio estudiante"
if (!Permisos::tienePermiso('Ver material estudio estudiante',$_SESSION['usuario']['id'] )) {
    echo json_encode(['status' => 'error', 'message' => 'Acceso denegado: no tienes el permiso necesario para descargar este material.']);
    exit;
}
// Validar el ID del material recibido
$idMaterial = isset($_GET['id_material']) ? (int)$_GET['id_material'] : 0;
if ($idMaterial === 0) {
    die("ID de material no proporcionado.");
}

try {
    // Consultar la ruta del archivo asociada al material
    $query = "
        SELECT 
            ruta_archivo, 
            titulo 
        FROM materiales_de_estudio 
        WHERE id = :idMaterial
    ";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':idMaterial', $idMaterial, PDO::PARAM_INT);
    $stmt->execute();
    $material = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if (!$material || empty($material['ruta_archivo'])) {
        die("Archivo no encontrado para este material.");
    }
    
    // Ruta completa del archivo
    $filePath = "C:/xampp/htdocs/Portal-Educativo/" . ltrim($material['ruta_archivo'], '/');
    
    if (!file_exists($filePath)) {
        die("El archivo no existe en el servidor. Ruta buscada: " . $filePath);
    }
    
    // Obtener la extensión del archivo
    $extension = pathinfo($filePath, PATHINFO_EXTENSION);
    // Asegurarse de que el título tenga la extensión
    $filename = basename($material['titulo']) . '.' . $extension;

    // Configurar headers para la descarga
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . $filename . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($filePath));
    
    // Leer y enviar el archivo
    readfile($filePath);
    exit;

} catch (PDOException $e) {
    die("Error al obtener el archivo: " . $e->getMessage());
}