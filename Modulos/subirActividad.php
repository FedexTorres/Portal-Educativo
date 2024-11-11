<?php
require './conexion_bbdd.php';  
session_start();

// Verifica si el usuario está logueado
if (!isset($_SESSION['usuario'])) {
    echo json_encode(['status' => 'error', 'message' => 'No estás autenticado']);
    exit();
}

// Verifica que se hayan recibido todos los parámetros necesarios
if (isset($_FILES['actividad']) && isset($_POST['cursoId']) && isset($_POST['actividadId'])) {
    $actividadId = $_POST['actividadId'];
    $cursoId = $_POST['cursoId'];
    $usuarioId = $_SESSION['usuario']['id'];

    // Verificar si el archivo fue subido correctamente
    if ($_FILES['actividad']['error'] !== UPLOAD_ERR_OK) {
        echo json_encode(['status' => 'error', 'message' => 'Error al subir el archivo']);
        exit();
    }

    // Directorio donde se almacenarán los archivos subidos
    $uploadDir = '../uploads/actividades/';

    // Obtener el nombre del archivo y su extensión
    $fileName = basename($_FILES['actividad']['name']);
    $filePath = $uploadDir . $fileName;

    // Verificar si el archivo ya existe
    if (file_exists($filePath)) {
        echo json_encode(['status' => 'error', 'message' => 'El archivo ya existe']);
        exit();
    }

    // Mover el archivo al directorio de destino
    if (!move_uploaded_file($_FILES['actividad']['tmp_name'], $filePath)) {
        echo json_encode(['status' => 'error', 'message' => 'No se pudo guardar el archivo']);
        exit();
    }

    // Verificar si el usuario y la actividad existen en la base de datos
    $stmt = $conn->prepare('SELECT id FROM usuarios WHERE id = :id_usuario');
    $stmt->bindParam(':id_usuario', $usuarioId, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount() === 0) {
        echo json_encode(['status' => 'error', 'message' => 'Usuario no encontrado']);
        exit();
    }

    $stmt = $conn->prepare('SELECT id FROM actividades WHERE id = :id_actividad');
    $stmt->bindParam(':id_actividad', $actividadId, PDO::PARAM_INT);
    $stmt->execute();
    if ($stmt->rowCount() === 0) {
        echo json_encode(['status' => 'error', 'message' => 'Actividad no encontrada']);
        exit();
    }

    // Obtener el último número de entrega para este usuario y actividad
    $stmt = $conn->prepare("SELECT MAX(numero_entrega) FROM entregas WHERE id_usuario = :id_usuario AND id_actividad = :id_actividad");
    $stmt->bindParam(':id_usuario', $usuarioId, PDO::PARAM_INT);
    $stmt->bindParam(':id_actividad', $actividadId, PDO::PARAM_INT);
    $stmt->execute();
    $ultimoNumeroEntrega = $stmt->fetchColumn();

    // Si no hay entregas previas, se usa el número 1
    if ($ultimoNumeroEntrega === null) {
        $nuevoNumeroEntrega = 1;
    } else {
        // Si hay entregas previas, incrementamos el último número de entrega
        $nuevoNumeroEntrega = $ultimoNumeroEntrega + 1;
    }

    // Preparar la consulta SQL para insertar la entrega
    $query = 'INSERT INTO entregas (id_usuario, id_actividad, numero_entrega, ruta_archivo, fecha_entrega) 
              VALUES (:id_usuario, :id_actividad, :numero_entrega, :ruta_archivo, NOW())';
    
    // Preparar la sentencia
    $stmt = $conn->prepare($query);

    // Vincular los parámetros
    $stmt->bindParam(':id_usuario', $usuarioId, PDO::PARAM_INT);
    $stmt->bindParam(':id_actividad', $actividadId, PDO::PARAM_INT);
    $stmt->bindParam(':numero_entrega', $nuevoNumeroEntrega, PDO::PARAM_INT);
    $stmt->bindParam(':ruta_archivo', $filePath, PDO::PARAM_STR);

    // Ejecutar la consulta
    try {
        $stmt->execute();
        echo json_encode(['status' => 'success', 'message' => 'Actividad subida correctamente']);
    } catch (PDOException $e) {
        // Mostrar el error exacto
        echo json_encode(['status' => 'error', 'message' => 'Error al guardar la entrega en la base de datos', 'details' => $e->getMessage()]);
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'Faltan parámetros']);
}
?>
