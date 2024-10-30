<?php
require './conexion_bbdd.php'; 
header('Content-Type: application/json');
// Comprobamos el método de solicitud
if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    //echo json_encode(['success' => false, 'message' => 'Método de solicitud no permitido']);
    exit();
}

// Verificamos que los datos requeridos fueron enviados y no están vacíos
if (isset($_POST["usuario"], $_POST["clave"]) && !empty($_POST["usuario"]) && !empty($_POST["clave"])) {
    $usuario = trim($_POST["usuario"]);
    $password = trim($_POST["clave"]);

    try {
        // Consulta segura usando PDO con prepared statements para evitar SQL Injection
        $query = "SELECT * FROM usuarios WHERE correo = :usuario";
        $stmt = $conn->prepare($query);
        $stmt->bindParam(":usuario", $usuario);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

        // Verificamos si se encontró el usuario y si el password ingresado coincide con el hash guardado
        if ($resultado && password_verify($password, $resultado['contrasena'])) {
            unset($resultado['contrasena']); // Removemos el hash de la respuesta por seguridad
            header('Content-Type: application/json');
            echo json_encode(['status' => 'success', 'data' => $resultado]);
        } else {
            // Si no hay coincidencia, enviamos un mensaje de error
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Usuario y/o contraseña incorrectos']);
        }
    } catch (PDOException $e) {
        // Capturamos errores de la base de datos y mostramos un mensaje genérico
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Error de conexión con la base de datos']);
    }

} else {
    // Mensaje de error si algún campo está vacío
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Por favor, completa todos los campos']);
}
?>
