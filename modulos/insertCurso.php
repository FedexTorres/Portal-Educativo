<?php

// Incluir la conexión a la base de datos
require './conexion_bbdd.php';
header('Content-Type: application/json');

// Verificar si la solicitud es POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $nombre = isset($_POST["nombrecrear"]) ? trim($_POST["nombrecrear"]) : null;
    $descripcion = isset($_POST["textCrearCurso"]) ? trim($_POST["textCrearCurso"]) : null;
    $programa = isset($_POST["programaCrearCurso"]) ? trim($_POST["programaCrearCurso"]) : null;
    $fechainicio = isset($_POST["fechaInicioCrearCurso"]) ? trim($_POST["fechaInicioCrearCurso"]) : null;
    $fechafin = isset($_POST["fechaFinCrearCurso"]) ? trim($_POST["fechaFinCrearCurso"]) : null;
    $imagen = $_FILES["archivoCrear"];

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
    if (empty($programa)) {
        $errores[] = "El programa no puede estar vacío.";
    } 

    // Verificar que no esté vacía, sea válida
    if (empty($fechainicio)) {
        $errores[] = "La fecha de inicio no puede estar vacía.";
    } 

    if (empty($fechafin)) {
        $errores[] = "La fecha de fin no puede estar vacía.";
    } 

    if ($fechainicio > $fechafin) {
        $errores[] = "La fecha de inicio no puede ser posterio a la fecha de fin.";
    } 

    if ($imagen['error'] != 0) {
        $errores[] = "La imagen no puede estar vacia.";
    }

    $uploadDir = '../image/';
    if (!is_dir($uploadDir)) {
        mkdir($uploadDir, 0777, true);
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

    // Mover la imagen a la carpeta de destino
    if (move_uploaded_file($imagen['tmp_name'], $imagePath)) {
        // Guardar la URL en la base de datos
        $imageUrl = $imagePath;

        // Si no hay errores, proceder a registrar

        // Prepara la consulta para insertar el nuevo estudiante
        $stmt = $conn->prepare("INSERT INTO cursos(nombre, descripcion, programa_estudios, imagen_url, fecha_inicio, fecha_fin) VALUES (
        :nombre, :descripcion, :programa, :imagen, :inicio, :fin)");
        $stmt->bindParam(':nombre', $nombre); 
        $stmt->bindParam(':descripcion', $descripcion);
        $stmt->bindParam(':programa', $programa);
        $stmt->bindParam(':imagen', $imageUrl); // Cambiado para usar la variable correcta
        $stmt->bindParam(':inicio', $fechainicio);
        $stmt->bindParam(':fin', $fechafin);

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
        echo json_encode(['status' => 'error', 'message' => 'Error al mover la imagen.']);
    }

}
else {
    header('Content-Type: application/json');
    echo json_encode(['status' => 'error', 'message' => 'Método de solicitud no válido.']);
}