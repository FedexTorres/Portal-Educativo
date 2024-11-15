<?php
session_start();
header("Content-Type: application/json; charset=UTF-8");
require 'conexion_bbdd.php';

try {

    $idusuario = $_SESSION['usuario']['id'];
    // Preparar la consulta para obtener todos los cursos
    $query = "SELECT cursos.id, nombre, descripcion, programa_estudios, vacantes, imagen_url, fecha_inicio, fecha_fin
     FROM cursos JOIN cursos_usuarios ON cursos_usuarios.id_curso=cursos.id 
     WHERE cursos_usuarios.id_usuario=:idusuario";
    

    $stmt = $conn->prepare($query);
    $stmt->bindParam("idusuario",$idusuario);

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
