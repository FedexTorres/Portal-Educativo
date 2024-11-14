<?php

// Incluir la conexión a la base de datos
require './conexion_bbdd.php';
header('Content-Type: application/json');

// Verificar si la solicitud es POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $id = isset($_POST["id"]) ? trim($_POST["id"]) : null;
    
    // Variables para almacenar errores
    $errores = [];

  
// Verificar si el curso ya existe
try {
    $queryUsuario = "SELECT * FROM cursos WHERE id = :id";
    $stmUsuario = $conn->prepare($queryUsuario);
    $stmUsuario->bindParam(':id', $id);
    $stmUsuario->execute();
    $existeUsuario = $stmUsuario->fetchColumn();

    if (!$existeUsuario) {
        $errores[] = "El curso no existe";
    }
} catch (PDOException $e) {
    $errores[] = "Error al verificar el correo en la base de datos.";
}

    // Si hay errores, devolverlos
    if (!empty($errores)) { 
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => $errores]);
        exit();
    }

    // prepara para eliminar la relacion cursos-usuarios
        // $sinrol = $conn->prepare("DELETE FROM cursos_usuarios WHERE id_curso=:id_curso");
        // $sinrol->bindParam(":id_curso", $id);
        // $sinrol->execute();

    // Prepara la consulta para eliminar el usuario
        $stmt = $conn->prepare("DELETE FROM cursos WHERE id = :id");
        $stmt->bindParam(':id', $id); 
    
        $resultado = $stmt->execute();

     if ($resultado) {
        header('Content-Type: application/json');
         echo json_encode(['status' => 'success', 'message' => 'Registro exitoso.']);
     } else {
        header('Content-Type: application/json');
         echo json_encode(['status' => 'error', 'message' => 'Error al registrar el usuario.']);
     }
 } else {
    header('Content-Type: application/json');
     echo json_encode(['status' => 'error', 'message' => 'Método de solicitud no válido.']);
 }