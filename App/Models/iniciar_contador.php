<?php
// Establecer la cabecera Content-Type para indicar que se envía JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { // Si no es una petición POST o intenta acceder directamente
    http_response_code(405); // Método no permitido
    echo json_encode(['error' => 'Metodo no permitido']);
    exit;
}

require_once __DIR__ . '/../Config/conexion.php';
require_once __DIR__ . '/../Controllers/registro_controller.php';

if (!isset($database)) { // Si no hay conexión a la base de datos
    echo json_encode(['success' => false, 'error' => 'No se puede acceder a los datos.']);
    exit;
}

// Se recibe los datos JSON enviados por el cliente
$data = json_decode(file_get_contents('php://input'), true); 

// Sanitizar, eliminar caracteres no numéricos
$codigo = preg_replace('/[^0-9]/', '', $data['codigo']);

$respuesta = iniciar_hora_contador($database, $codigo);
echo json_encode($respuesta);
exit;





