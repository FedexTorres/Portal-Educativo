<?php
require_once './conexion_bbdd.php';
session_start();

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

    // Configurar headers para la descarga
    header('Content-Description: File Transfer');
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="' . basename($material['titulo']) . '"');
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
