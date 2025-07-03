<?php
header('Content-Type: application/json');

// Si no es una petición POST o intenta acceder directamente
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { 
    http_response_code(405); // Método no permitido
    echo json_encode(['error' => 'Metodo no permitido']);
    exit;
}
require_once __DIR__ . '/../Config/conexion.php';
require_once __DIR__ . '/../Models/login_model.php';

if (!isset($database)) { // Si no hay conexión a la base de datos
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'No se puede acceder a los datos.']);
    exit;
}

// Se recibe los datos JSON enviados por el cliente
$data = json_decode(file_get_contents('php://input'), true); 
$codtoken = (int)preg_replace('/[^0-9]/', '', $data['codigo']); // campo ID del token

// Eliminar el token de la tabla
eliminar_token_temp($database, $codtoken, null);
// Envio mensaje de confirmación 
http_response_code(200); // OK
echo json_encode(['success'=> true]);