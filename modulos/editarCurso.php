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

if (!Permisos::tienePermiso('Editar cursos',$_SESSION['usuario']['id'] )) {
    echo json_encode(['status' => 'error', 'message' => 'No tienes permiso editar cursos']);
    exit;
}

// Verificar si la solicitud es POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Obtener los datos del formulario
    $idcurso = isset($_POST["id"]) ? trim($_POST["id"]) : null;
    $profe = isset($_POST["profe"]) ? trim($_POST["profe"]) : null;
    $vacante = isset($_POST["vacante"]) ? trim($_POST["vacante"]) : null;
    
    // Variables para almacenar errores
    $errores = [];

    // Validaciones
    if (empty($idcurso)) {
        $errores[] = "El id de curso no puede estar vacío.";
    } 

    // Verificar que el apellido no esté vacío y que solo tenga letras y espacios
    if (!empty($profe)) {  
        try {
            $queryUsuario = "SELECT COUNT(*) FROM usuarios WHERE id = :id";
            $stmUsuario = $conn->prepare($queryUsuario);
            $stmUsuario->bindParam(':id', $profe);
            $stmUsuario->execute();
            $existeUsuario = $stmUsuario->fetchColumn();
    
            if ($existeUsuario > 0) {
                
                ////validamos si el curso tiene profesor
                $query2 = "SELECT u.id, u.nombre, u.apellido, c.nombre AS nombre_curso, r.nombre AS nombre_rol
                FROM usuarios u
                JOIN cursos_usuarios cu ON u.id = cu.id_usuario
                JOIN cursos c ON c.id = cu.id_curso
                JOIN roles_usuarios ru ON u.id = ru.id_usuario
                JOIN roles r ON r.id = ru.id_rol
                WHERE c.id = :id_curso AND r.nombre = 'profesor'";

                $cursoprofe = $conn->prepare($query2);

                $cursoprofe->bindParam(':id_curso', $curso);

                $cursoprofe->execute();

                // Obtener todos los resultados
                $cursos = $cursoprofe->fetchAll(PDO::FETCH_ASSOC);
                
                    // se elimina el profe

                    $sinrol = $conn->prepare("DELETE cu
                    FROM cursos_usuarios cu
                    JOIN roles_usuarios ru ON cu.id_usuario = ru.id_usuario
                    JOIN roles r ON ru.id_rol = r.id
                    WHERE r.nombre = 'profesor' AND cu.id_curso = :id_curso");

                    $sinrol->bindParam(":id_curso", $idcurso);
                    $sinrol->execute();

                

                    //no tiene profe, se le asigna
                    $rolAsig = $conn->prepare("INSERT INTO cursos_usuarios (id_usuario, id_curso) VALUES (:user, :curso)");
                    $rolAsig->bindParam(":user", $profe);
                    $rolAsig->bindParam(":curso", $idcurso);

                    $rolAsig->execute();
                        

            }else{
                header('Content-Type: application/json');
                echo json_encode(['status' => 'error', 'message' => 'El usuario no existe']);
            }
        } catch (PDOException $e) {
            $errores[] = "Error al verificar el usuario en la base de datos." . $e->getMessage();
        }
    } 

    
    if (!empty($vacante)) {
        
        try {
            $stmt = $conn->prepare("UPDATE cursos SET vacantes = :vacantes WHERE id=:idcurso");
            $stmt->bindParam(':vacantes', $vacante); 
            $stmt->bindParam(':idcurso', $idcurso); 

            $stmt->execute();

        } catch (PDOException $e) {
            $errores[] = "Error al cargar vacantes en la base de datos.";
        }    

    } 



    // Si hay errores, devolverlos
    if (!empty($errores)) { 
        header('Content-Type: application/json');
        echo json_encode(['status' => 'error', 'message' => $errores]);
        exit();
    } else {
        header('Content-Type: application/json');
         echo json_encode(['status' => 'success', 'message' => 'Registro exitoso.']);
     }
 } else {
    header('Content-Type: application/json');
     echo json_encode(['status' => 'error', 'message' => 'Método de solicitud no válido.']);
 }