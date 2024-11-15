<?php
session_start();
header("Content-Type: application/json; charset=UTF-8");
require 'conexion_bbdd.php';

try {


    // Preparar la consulta para obtener todos los cursos
    $query = "SELECT id, nombre, descripcion, programa_estudios, vacantes, imagen_url, fecha_inicio, fecha_fin FROM cursos";
    
    $query2 = "SELECT u.id, u.nombre, u.apellido, c.nombre AS nombre_curso, r.nombre AS nombre_rol
    FROM usuarios u
    JOIN cursos_usuarios cu ON u.id = cu.id_usuario
    JOIN cursos c ON c.id = cu.id_curso
    JOIN roles_usuarios ru ON u.id = ru.id_usuario
    JOIN roles r ON r.id = ru.id_rol
    WHERE c.id = :id_curso AND r.nombre = :nombre_rol";

    $query3 = "SELECT c.id AS id_curso, c.nombre AS nombre_curso, descripcion, programa_estudios, vacantes, imagen_url, fecha_inicio, fecha_fin, u.nombre AS nombre_usuario
    FROM cursos c
    LEFT JOIN cursos_usuarios cu ON c.id = cu.id_curso
    LEFT JOIN usuarios u ON cu.id_usuario = u.id
    LEFT JOIN roles_usuarios ru ON u.id = ru.id_usuario
    LEFT JOIN roles r ON ru.id_rol = r.id
    WHERE r.nombre = 'administrador'
    ORDER BY c.id";


    $stmt = $conn->prepare($query);
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
    echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos']);
}
?>