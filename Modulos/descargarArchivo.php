<?php
require_once './conexion_bbdd.php'; 

session_start();

$idEntrega = isset($_GET['id_entrega']) ? (int)$_GET['id_entrega'] : 0;

if ($idEntrega === 0) {
    die("ID de entrega no proporcionado.");
}

try {
    // Consulta para obtener la ruta del archivo en base al id_entrega
    $query = "SELECT ruta_archivo FROM entregas WHERE id = :idEntrega";
    $stmt = $conn->prepare($query);
    $stmt->bindParam(':idEntrega', $idEntrega, PDO::PARAM_INT);
    $stmt->execute();
    $entrega = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!$entrega || empty($entrega['ruta_archivo'])) {
        die("Archivo no encontrado para esta entrega.");
    }

    //$filePath = $_SERVER['DOCUMENT_ROOT'] . $entrega['ruta_archivo'];
    // Ruta completa usando el DOCUMENT_ROOT y la ruta relativa de la base de datos
    $filePath = $_SERVER['DOCUMENT_ROOT'] . '/Portal-Educativo/' . ltrim($entrega['ruta_archivo'], '../');
    
    // Verificar la ruta construida
    if (!file_exists($filePath)) {
        die("El archivo no existe en el servidor. Ruta buscada: " . $filePath);
    }

    // Configurar headers para la descarga
    header('Content-Description: File Transfer');
    header('Content-Type: application/pdf; charset=iso-8859-1');
    header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
    header('Expires: 0');
    header('Cache-Control: must-revalidate');
    header('Pragma: public');
    header('Content-Length: ' . filesize($filePath));

       
    // Leer el archivo y enviarlo
    readfile($filePath);
    exit;
} catch (PDOException $e) {
    die("Error al obtener el archivo: " . $e->getMessage());
}
