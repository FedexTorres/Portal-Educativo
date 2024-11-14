<?php
header('Content-Type: application/json');
error_log(print_r($_GET, true));

require './conexion_bbdd.php';  // Incluye la conexión a la base de datos

if (isset($_POST['id'])) {
    // Escapar caracteres especiales
    $query = htmlspecialchars(trim($_POST['id']));
    
    try {
        // Preparar la consulta usando PDO
        //$stmt = $conn->prepare("SELECT id, nombre FROM usuarios WHERE nombre LIKE :query LIMIT 10");

        $stmt = $conn->prepare("SELECT u.id, u.nombre, u.apellido, u.correo
        FROM usuarios u
        JOIN roles_usuarios ru ON u.id = ru.id_usuario
        JOIN roles r ON ru.id_rol = r.id
        WHERE r.nombre = 'profesor' AND u.nombre LIKE :query LIMIT 10");

        $queryParam = "%$query%"; // Asigna el valor a la variable
        $stmt->bindParam(':query', $queryParam, PDO::PARAM_STR);
        
        // Ejecutar la consulta
        $stmt->execute();
        $destinatarios = $stmt->fetchAll(PDO::FETCH_ASSOC);
        // Devolver resultados en formato JSON
        if ($destinatarios) {
            echo json_encode(['status' => 'success', 'destinatarios' => $destinatarios]);
        } else {
            echo json_encode(['status' => 'error', 'message' => 'No se encontraron destinatarios.']);
        }
        //echo json_encode(['status' => 'success', 'destinatarios' => $destinatarios]);
    } catch (PDOException $e) {
        echo json_encode(['status' => 'error', 'message' => 'Error: ' . $e->getMessage()]);
        exit;
    }
} else {
    echo json_encode(['status' => 'error', 'message' => 'No query provided.']);
}
