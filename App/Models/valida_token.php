<?php
// Archivo de entrada: Recibe la petición, valida los datos y responde en JSON.
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { 
    http_response_code(405); 
    exit(json_encode(['error' => 'Metodo no permitido']));
}
require_once dirname(__DIR__) . '/Config/def_ruta.php';
require_once ROOT_PATH . '/Config/conexion.php';
require_once ROOT_PATH . '/Controllers/registro_controller.php';

if (!isset($database)) { // Si no hay conexión a la base de datos
    http_response_code(400);
    exit(json_encode(['success' => false, 'error' => 'No se puede acceder a los datos.']));
}
// Se recibe los datos JSON enviados por el cliente
$data = json_decode(file_get_contents('php://input'), true); 
if (!$data) {
    http_response_code(400);
    exit(json_encode(['success' => false, 'error' => 'Datos no válidos o faltantes']));
}

// Sanitizar, convertir a entero despues de eliminar caracteres no numéricos
$codverif = (int)filter_var($data['codv'], FILTER_SANITIZE_NUMBER_INT); // campo código de verificación
$codtoken = (int)filter_var($data['codt'], FILTER_SANITIZE_NUMBER_INT); // campo ID del token

if (empty($codverif) || empty($codtoken)) {
    //envio un código de estado HTTP específico si algo sale mal:
    http_response_code(400);
    exit(json_encode(['success'=> false,'error' => 'Error codigo o token vacio.']));
}
if (strlen(($codverif)) < 5) {
    http_response_code(400);
    exit(json_encode(['success'=> false,'error' => 'El codigo debe tener 5 caracteres.']));
}

// Verificar si el código de verificación es correcto
$resultado = procesar_validacion_registro($database, $codverif, $codtoken);
exit(json_encode($resultado));
