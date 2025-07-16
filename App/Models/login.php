<?php
session_start(); // Iniciar la sesión para manejar el usuario
// Establecer la cabecera Content-Type para indicar que se envía JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { // Si no es una petición POST o intenta acceder directamente
    http_response_code(405); // Método no permitido
    exit(json_encode(['error' => 'Metodo no permitido']));
}
// Configura headers de seguridad
header("X-Frame-Options: DENY");
header("X-Content-Type-Options: nosniff");
// Para máxima seguridad (proteccion contra XSS)
header("Content-Security-Policy: default-src 'self'"); 
header("Referrer-Policy: no-referrer-when-downgrade");

require_once dirname(__DIR__) . '/Config/def_ruta.php';
require_once ROOT_PATH . '/Config/conexion.php';
require_once ROOT_PATH . '/Controllers/login_controller.php';

if (!isset($database)) { // Si no hay conexión a la base de datos
    exit(json_encode(['success' => false, 'error' => 'No se puede acceder a los datos.']));
}

// Obtener y Sanitizar los campos recibidos del cliente 
$data = json_decode(file_get_contents('php://input'), true); 
$correo = filter_var($data['correo'] ?? '', FILTER_SANITIZE_EMAIL); 
$clave = filter_var($data['clave'] ?? '', FILTER_SANITIZE_STRING);

/*****************************************************************************************************************
                                        VALIDACIÓN DE LOS DATOS
******************************************************************************************************************/
if (empty($correo) || empty($clave)) {
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
// Guardo el correo en una session para su posterior uso
$_SESSION['usuario'] = $correo;

/*****************************************************************************************************************
                                        PROCESAMIENTO EN BASE DE DATOS
******************************************************************************************************************/
$respuesta = procesar_login($database, $correo, $clave);
echo json_encode($respuesta);
exit;