<?php
session_start();

require './conexion_bbdd.php';  
require_once './permisos.php';

// Verifica si el usuario está logueado
if (!isset($_SESSION['usuario']['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No estás autenticado']);
    exit();
}
// Verificar permisos
if (!Permisos::tienePermiso('Calificar', $_SESSION['usuario']['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No tienes permiso para hacer la descarga.']);
    exit;
}

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

    $filePath = rtrim($_SERVER['DOCUMENT_ROOT'], '/') . '/Portal-Educativo/uploads/actividades/' . basename($entrega['ruta_archivo']);
    // Verificar la ruta construida
    if (!file_exists($filePath)) {
        die("El archivo no existe en el servidor. Ruta buscada: " . $filePath);
    }

    // Determinar el tipo de contenido según la extensión del archivo
    $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
    switch ($extension) {
        case 'pdf':
            $contentType = 'application/pdf';
            break;
        case 'png':
            $contentType = 'image/png';
            break;
        case 'txt':
            $contentType = 'text/plain';
            break;
        default:
            $contentType = 'application/octet-stream';
    }

    // Configurar headers para la descarga
    header('Content-Description: File Transfer');
    header('Content-Type: ' . $contentType);
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