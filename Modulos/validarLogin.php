<?php
require './conexion_bbdd.php'; 
session_start(); // Iniciar sesión al principio del archivo
header('Content-Type: application/json');
// Comprobamos el método de solicitud
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    //echo json_encode(['success' => false, 'message' => 'Método de solicitud no permitido']);
    exit();
}

// Verificamos que los datos requeridos fueron enviados y no están vacíos
if (isset($_POST["correo"], $_POST["clave"]) && !empty($_POST["correo"]) && !empty($_POST["clave"])) {
    $usuario = trim($_POST["correo"]);
    $password = trim($_POST["clave"]);

    try {
        // Consulta segura usando PDO con prepared statements para evitar SQL Injection
        $query = "SELECT u.*, r.nombre AS tipo_usuario 
                  FROM usuarios u 
                  JOIN roles_usuarios ru ON u.id = ru.id_usuario 
                  JOIN roles r ON ru.id_rol = r.id 
                  WHERE u.correo = :usuario";

        $stmt = $conn->prepare($query);
        $stmt->bindParam(":usuario", $usuario);
        $stmt->execute();
        $resultado = $stmt->fetch(PDO::FETCH_ASSOC);

       // Verificamos si se encontró el usuario y si el password ingresado coincide con el hash guardado
       if ($resultado && password_verify($password, $resultado['contrasena'])) {
        // Guardamos datos clave en la sesión
        $_SESSION['usuario'] = [
            'id' => $resultado['id'],
            'nombre' => $resultado['nombre'],
            'apellido' => $resultado['apellido'], // Agrega el apellido aquí
            'rol' => $resultado['tipo_usuario'], // Asumiendo que este es el campo que usas para el rol
        ];

        // Establecemos la URL de redirección según el tipo de usuario
        $redireccion = "";
        if ($_SESSION['usuario']['rol'] == 'administrador') {
            $redireccion = "administrador.html";
        } elseif ($_SESSION['usuario']['rol'] == 'profesor') {
            $redireccion = "vista-profesor.html";
        } elseif ($_SESSION['usuario']['rol'] == 'estudiante') {
            $redireccion = "index.php";
        }

        // Enviamos la respuesta JSON con el rol y la URL de redirección
        echo json_encode([
            'status' => 'success',
            'data' => [
                'usuario' => $resultado['nombre'],
                'rol' => $_SESSION['usuario']['rol'],
                'redirect' => $redireccion
            ]
        ]);
        } else {
            // Si no hay coincidencia, enviamos un mensaje de error
            echo json_encode(['status' => 'error', 'message' => 'Usuario y/o contraseña incorrectos']);
        }
    } catch (PDOException $e) {
        // Capturamos errores de la base de datos y mostramos un mensaje genérico
        echo json_encode(['status' => 'error', 'message' => 'Error en la consulta: ' . $e->getMessage()]);
        //echo json_encode(['status' => 'error', 'message' => 'Error de conexión con la base de datos']);
    }

} else {
    // Mensaje de error si algún campo está vacío
    echo json_encode(['status' => 'error', 'message' => 'Por favor, completa todos los campos']);
}
?>
