<?php
// Establecer la cabecera Content-Type para indicar que se envía JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { // Si no es una petición POST o intenta acceder directamente
    http_response_code(405); // Método no permitido
    echo json_encode(['error' => 'Metodo no permitido']);
    exit;
}
require_once __DIR__ . '/../Controllers/registro_controller.php';
require_once __DIR__ . '/../Config/conexion.php';

if (!isset($database)) { // Si no hay conexión a la base de datos
    echo json_encode(['success' => false, 'error' => 'No se puede acceder a los datos.']);
    exit;
}

/*Se recibe los datos JSON enviados por el cliente, utilizando file_get_contents('php://input') 
decodifícado a un array asociativo de PHP utilizando json_decode()
la cual se almacena en una variable ($data), que luego se usara para acceder a los datos JSON. */
$data = json_decode(file_get_contents('php://input'), true); 

// Sanitizando los campos recibidos del cliente 
$correo = filter_var($data['correo'] ?? '', FILTER_SANITIZE_EMAIL); 
$clave = filter_var($data['clave'] ?? '', FILTER_SANITIZE_STRING);
$clave2 = filter_var($data['clave2'] ?? '', FILTER_SANITIZE_STRING);

/*****************************************************************************************************************
                                        VALIDACIÓN DE LOS DATOS
******************************************************************************************************************/
if (empty($correo) || empty($clave) || empty($clave2)) {
    echo json_encode(['success' => false, 'error' => 'Todos los campos son obligatorios.']); // Enviar JSON al cliente
    exit;
}

function validarEmail($email) { // validar el email con el formato requerido
    if (!preg_match('/^[^\s@]+@[^\s@]+\.com$/', $email)) {
        return false;
    } else {
        return true;
    }
}
if (!validarEmail($correo)) {
    echo json_encode(['success' => false, 'error' => 'Correo no válido.']);
    exit;
}

if (strlen($clave) < 6) {
    echo json_encode(['success' => false, 'error' => 'La clave debe tener al menos 6 caracteres.']);
    exit;
}

if ($clave !== $clave2) {
    echo json_encode(['success' => false, 'error' => 'Las claves no coinciden.']);
    exit;
}

/*****************************************************************************************************************
                                        PROCESAMIENTO EN BASE DE DATOS
******************************************************************************************************************/
$respuesta = procesar_temp_registro($database, $correo, $clave);
if (!$respuesta['success']) {
    echo json_encode($respuesta);
    exit;
}

/*****************************************************************************************************************
                                ENVIO DE CÓDIGO DE VERIFICACIÓN AL CORREO DEL USUARIO
******************************************************************************************************************/
$respuesta2 = enviar_token_correo($database, $correo);
echo json_encode($respuesta2);
exit;
