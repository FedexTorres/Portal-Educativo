<?php
session_start();
header("Content-Type: application/json; charset=UTF-8");
require 'conexion_bbdd.php';


if (isset($_POST["id"])) {
    
    $curso = $_POST["id"];

try {

    // Preparar la consulta para obtener todos los cursos
    
    $query2 = "SELECT u.id, u.nombre, u.apellido, c.nombre AS nombre_curso, r.nombre AS nombre_rol
    FROM usuarios u
    JOIN cursos_usuarios cu ON u.id = cu.id_usuario
    JOIN cursos c ON c.id = cu.id_curso
    JOIN roles_usuarios ru ON u.id = ru.id_usuario
    JOIN roles r ON r.id = ru.id_rol
    WHERE c.id = :id_curso AND r.nombre = 'profesor'";


    $stmt = $conn->prepare($query2);

    $stmt->bindParam(':id_curso', $curso);

    $stmt->execute();

    // Obtener todos los resultados
    $cursos = $stmt->fetchAll(PDO::FETCH_ASSOC);

    if ($cursos) {
        // Devolver los datos de los cursos en formato JSON
        echo json_encode(['status' => 'success', 'cursos' => $cursos]);
    } else {
        echo json_encode(['status' => 'Ninguno', 'message' => 'No Asignado']);
    }
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => 'Error en la base de datos']);
}


}else {
    echo json_encode(['status' => 'error', 'message' => 'No se envio ningun curso']);
}