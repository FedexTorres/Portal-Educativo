<?php

// Incluir la conexión a la base de datos
require './conexion_bbdd.php';
header('Content-Type: application/json');

// Verificar si la solicitud es POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nombre = isset($_POST["title"]) ? trim($_POST["title"]) : null;
    $descripcion = isset($_POST["description"]) ? trim($_POST["description"]) : null;
    $idcurso = isset($_POST["idcurso"]) ? trim($_POST["idcurso"]) : null;
    $imagen = $_FILES["file"];

    // Variables para almacenar errores
    $errores = [];

    // Validaciones
    // Verificar que no esté vacío 
    if (empty($nombre)) {
        $errores[] = "El nombre no puede estar vacío.";
    } 

    // Verificar que no esté vacío 
    if (empty($descripcion)) {
        $errores[] = "La descripcion no puede estar vacío.";
    } 

    // Verificar que no esté vacío
    if (empty($idcurso)) {
        $errores[] = "El programa no puede estar vacío.";
    } 


    if ($imagen['error'] != 0) {
        $errores[] = "La imagen no puede estar vacia.";
    }

    $uploadDir = 'material/';
    $uploadDir2 = '../material/';
    if (!is_dir($uploadDir2)) {
        mkdir($uploadDir2, 0777, true);
    }
    

    // Si hay errores, devolverlos
    if (!empty($errores)) { 
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => $errores]);
        exit();
    }



    // Generar un nombre único para la imagen
    $imageName = uniqid() . "-" . basename($imagen['name']);
    $imagePath = $uploadDir . $imageName;
    $imagePath2 = $uploadDir2 . $imageName;

    // Mover la imagen a la carpeta de destino
    if (move_uploaded_file($imagen['tmp_name'], $imagePath2)) {
        // Guardar la URL en la base de datos
        $imageUrl = $imagePath;

        $hoy = new DateTime();
        $ahora = $hoy->format('Y-m-d H:i:s');
        // Si no hay errores, proceder a registrar

        // Prepara la consulta para insertar el nuevo estudiante
        $stmt = $conn->prepare("INSERT INTO materiales_de_estudio(titulo, descripcion, id_curso, fecha_subida, ruta_archivo) VALUES (
        :nombre, :descripcion, :idcurso, :fecha, :archivo)");
        $stmt->bindParam(':nombre', $nombre); 
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':idcurso', $idcurso);
        $stmt->bindParam(':fecha', $ahora); // Cambiado para usar la variable correcta
        $stmt->bindParam(':archivo', $imageUrl);

        $resultado = $stmt->execute();



        if ($resultado) {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'success', 'message' => 'Registro exitoso.']);
        } else {
            header('Content-Type: application/json');
            echo json_encode(['status' => 'error', 'message' => 'Error al registrar el material.']);
        }

    } else {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => 'Error al mover la imagen.']);
    }

}
else {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Método de solicitud no válido.']);
}