<?php
session_start();

require './conexion_bbdd.php';
require_once './permisos.php'; // Asegúrate de que esta ruta sea correcta

// Verifica si el usuario está logueado
if (!isset($_SESSION['usuario']['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No estás autenticado']);
    exit();
}

// Verificar permisos: "Ver cursos profesor"
if (!Permisos::tienePermiso('Ver cursos profesor', $_SESSION['usuario']['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No tienes permiso para ver los cursos']);
    exit;
}

$idusuario = $_SESSION['usuario']['id'];

try {
    // Preparar la consulta para obtener todos los cursos
    $query = "SELECT cursos.id, nombre, descripcion, programa_estudios, vacantes, imagen_url, fecha_inicio, fecha_fin
              FROM cursos 
              JOIN cursos_usuarios ON cursos_usuarios.id_curso=cursos.id 
              WHERE cursos_usuarios.id_usuario=:idusuario";

    $stmt = $conn->prepare($query);
    $stmt->bindParam("idusuario", $idusuario, PDO::PARAM_INT);

    $stmt->execute();

    // Obtener todos los resultados
    $cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($cursos) {
        // Devolver los datos de los cursos en formato JSON
        echo json_encode(['status' => 'success', 'cursos' => $cursos]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'No se encontraron cursos']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos', 'error_details' => $e->getMessage()]);
}
?>
