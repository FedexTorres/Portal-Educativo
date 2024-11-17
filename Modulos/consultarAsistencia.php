<?php

session_start();
// Incluimos el archivo de conexión a la base de datos
require './conexion_bbdd.php';

// Validamos que el usuario esté logueado
if (!isset($_SESSION['usuario']['id'])) {
    echo json_encode(['error' => 'Usuario no logueado']);
    exit;
}

$idUsuario = $_SESSION['usuario']['id'];

// Validamos que se haya enviado el ID del curso
if (!isset($_GET['cursoId'])) {
    echo json_encode(['error' => 'ID del curso no especificado']);
    exit;
}

$idCurso = (int)$_GET['cursoId'];

// Verificamos si se envió un filtro de asistencia (Presente, Ausente o Todos)
$filtro = $_GET['filtro'] ?? 'todos';

// Creamos la consulta base
$query = "
    SELECT af.fecha, au.estado, u.nombre AS estudiante_nombre, u.apellido AS estudiante_apellido
    FROM asistencias_fechas af
    JOIN asistencias_usuarios au ON af.id = au.id_asistencia_fecha
    JOIN usuarios u ON au.id_usuario = u.id
    WHERE af.id_curso = :cursoId AND au.id_usuario = :idUsuario
";

// Ajustamos la consulta si el filtro es distinto de "todos"
if ($filtro === 'Presente' || $filtro === 'Ausente') {
    $query .= " AND au.estado = :filtro";
}

try {
    // Preparamos y ejecutamos la consulta
    $stmt = $conn->prepare($query);

    // Vinculamos los parámetros
    $stmt->bindParam(':cursoId', $idCurso, PDO::PARAM_INT);
    $stmt->bindParam(':idUsuario', $idUsuario, PDO::PARAM_INT);

    // Si el filtro es específico, lo vinculamos
    if ($filtro === 'Presente' || $filtro === 'Ausente') {
        $stmt->bindParam(':filtro', $filtro, PDO::PARAM_STR);
    }

    $stmt->execute();

    // Obtenemos los resultados
    $asistencias = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Devolvemos los datos en formato JSON
    echo json_encode($asistencias);

} catch (PDOException $e) {
    echo json_encode(['error' => 'Error en la consulta de asistencias: ' . $e->getMessage()]);
}
?>
