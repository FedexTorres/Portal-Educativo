<?php
session_start();

// Incluir la conexión a la base de datos
require './conexion_bbdd.php';
require_once './permisos.php';

header('Content-Type: application/json');

if (!isset($_SESSION['usuario']['id'])) {
    echo json_encode(['status' => 'error', 'message' => 'No estás autenticado']);
    exit;
}

if (!Permisos::tienePermiso('Borrar usuarios',$_SESSION['usuario']['id'] )) {
    echo json_encode(['status' => 'error', 'message' => 'No tienes permiso para borrar usuarios']);
    exit;
}

// Verificar si la solicitud es POST
if ($_SERVER["REQUEST_METHOD"] == "GET") {
    // Obtener los datos del formulario
    $id = isset($_GET["id"]) ? trim($_GET["id"]) : null;
    
    // Variables para almacenar errores
    $errores = [];

  
// Verificar si el correo ya existe
try {
    $queryUsuario = "SELECT * FROM usuarios WHERE id = :id";
    $stmUsuario = $conn->prepare($queryUsuario);
    $stmUsuario->bindParam(':id', $id);
    $stmUsuario->execute();
    $existeUsuario = $stmUsuario->fetchColumn();

    if (!$existeUsuario) {
        $errores[] = "El Usuario no existe";
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

    // prepara para eliminar su rol
        $sinrol = $conn->prepare("DELETE FROM roles_usuarios WHERE id_usuario=:id_usuario");
        $sinrol->bindParam(":id_usuario", $id);
        $sinrol->execute();

    // Prepara la consulta para eliminar el usuario
        $stmt = $conn->prepare("DELETE FROM usuarios WHERE id = :id");
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