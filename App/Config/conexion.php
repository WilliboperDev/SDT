<?php
// Configuración de conexión a la base de datos utilizando la librería Medoo
$credencialesPath = __DIR__ .'/../Libs/autoload.php';
if (!file_exists($credencialesPath)) {
    throw new RuntimeException('Archivo de libreria no encontrado');
}
require_once $credencialesPath;
use Medoo\Medoo;

try {
    // Conexión a la base de datos con la librería Medoo
    $database = new Medoo([ 
    'database_type' => 'mysql', 
    'database_name' => 'sis_direct_telef', 
    'server' => 'localhost', 
    'username' => '', 
    'password' => ''
    ]);
    
} catch (Exception $e) {
    // Mostrar el mensaje de error si la conexión falla
    return false;
}
