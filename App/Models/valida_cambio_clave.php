<?php
// Inicializar la sesión
session_start();

require_once dirname(__DIR__) . '/Config/def_ruta.php';
// Si no es una petición POST o intenta acceder directamente
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { 
    header('Location: ' . $appUrl);
    exit;
}
// Validar si hay un usuario en la sesión
$user = $_SESSION['usuario'];
if (!isset($user) || empty($user)) {
    echo json_encode(['success' => false, 'error' => 'No se puede acceder a los datos.']);
    exit;
}

/////// Configuración inicial
require_once ROOT_PATH . '/Config/conexion.php';
require_once ROOT_PATH . '/Controllers/cambio_clave_controller.php';

if (!isset($database)) { // Si no hay conexión a la base de datos
    echo json_encode(['success' => false, 'error' => 'No se puede acceder a los datos.']);
    exit;
}
// Se recibe los datos POST enviados por el fetch
$datos = $_POST;

if (!$datos) {
    http_response_code(400);
    echo json_encode(['success' => false, 'error' => 'Datos no válidos o faltantes']);
    exit;
}
// Validando que los campos no estén vacíos
if (empty($datos['conan']) || empty($datos['conac']) || empty($datos['conacr'])) {
    echo json_encode(['success' => false, 'error' => 'Todos los campos son obligatorios.']); // Enviar JSON al cliente
    exit;
}
// Definir constantes para la longitud de la clave
define('CLAVE_MIN_LEN', 6);
define('CLAVE_MAX_LEN', 16);

// funcion para validar la longitud de la clave
function validarClave($clave, $campo) {
    $clave = trim($clave);
    $claveLen = mb_strlen($clave, 'UTF-8');

    if ($claveLen < CLAVE_MIN_LEN || $claveLen > CLAVE_MAX_LEN) {
        http_response_code(400);
        echo json_encode(['error' => "La $campo excede el límite o es menor a " . CLAVE_MIN_LEN . " caracteres."]);
        exit;
    }
    return $clave;
}

try {
    // Sanitizando los campos recibidos del cliente
    $claveant = validarClave($datos['conan'] ?? '', 'clave anterior');
    $claven = validarClave($datos['conac'] ?? '', 'clave nueva');
    $clavenr = validarClave($datos['conacr'] ?? '', 'clave repetida');
    /*************************************************************************/

    // Verificar si la clave nueva y la repetida son iguales
    if ($claven !== $clavenr) {
        http_response_code(400);
        echo json_encode(['error' => 'Las claves no coinciden.']);
        exit;
    }

    // Obtener la clave almacenada en la base de datos
    $claveEnBD = obtener_clave_login($database, $user);
    if (empty($claveEnBD)) {
        http_response_code(404); // Not Found
        echo json_encode(['success' => false, 'error' => 'Usuario no encontrado']);
        exit;
    }
    // Verificar la clave anterior ingresada por el usuario con la almacenada en la base de datos
    if (!password_verify($claveant, $claveEnBD)) {
        http_response_code(400);
        echo json_encode(['error' => 'La clave anterior no es correcta.']);
        exit;
    }
    // Verificar que la clave anterior no sea igual a la nueva
    if (password_verify($claveant, $claven)) {
        http_response_code(400);
        echo json_encode(['error' => 'La nueva clave no puede ser igual a la anterior.']);
        exit;
    }

    // Si la clave anterior es correcta, continuar con el proceso de actualización
    // Encriptar la nueva clave
    $nuevaClaveHash = password_hash($claven, PASSWORD_DEFAULT);

    // Actualizar la clave en la base de datos
    $actualizado = actualiza_clave($database, $nuevaClaveHash, $user);
    if (!$actualizado) {
        http_response_code(500);
        echo json_encode(['success' => false, 'error' => 'No se actualizo la clave.']);
    }
    echo json_encode(['success' => true]);

} catch (Exception $e) {
    http_response_code(500); // Internal Server Error
    error_log("Error en la actualización de clave: " . $e->getMessage());
    echo json_encode(['success' => false, 'error' => 'Ocurrió un error inesperado. Inténtelo nuevamente.']);
}