<?php
require './conexion_bbdd.php'; 

// Verificar la conexión
if (isset($GLOBALS['conn'])) {
    echo "Conexión exitosa a la base de datos.";
} else {
    echo "Error en la conexión.";
}
