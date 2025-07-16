<?php
session_start(); // Iniciar la sesión para manejar el usuario
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { // Si no es una petición POST o intenta acceder directamente
    http_response_code(405);
    echo json_encode(['error' => 'Metodo no permitido']);
    exit;
}
require_once dirname(__DIR__) . '/Config/def_ruta.php';
$user = $_SESSION['usuario'] ?? null; // Obtener el usuario de la sesión
if (!isset($user) || empty($user)) {
    header('Location: ' . $appUrl);
    exit;
}
require_once ROOT_PATH . '/Config/conexion.php';
require_once ROOT_PATH . '/Controllers/contacto_controller.php';

if (!isset($database)) { // Si no hay conexión a la base de datos
    echo json_encode(['success' => false, 'error' => 'No se puede acceder a los datos.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true); 
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['success' => false, 'error' => 'Error al decodificar JSON.']);
    exit;
}

try {
    // Determinar el tipo de consulta
    $action = $data['action'] ?? null;
    
    switch($action) {
        case 'per_slider':
            
            $usuarios = contactos_dashboard($database, $user);
            break;
            
        case 'pos_seguir':
            $seguir = filter_var($data['seguidoId'] ?? '', FILTER_SANITIZE_EMAIL);
            $estado = filter_var($data['estado'] ?? '', FILTER_SANITIZE_STRING);

            if (!contactos_seguidores($database, $user, $seguir, $estado)) {
                echo json_encode(['success' => false, 'error' => 'Error al actualizar la relación.']);
                exit;
            }
            $usuarios = '';
            break;

        case 'seguidores':
            $usuarios = obtener_tipo_contacto($database, $user, 1);
            if (empty($usuarios)) {
                echo json_encode(['success' => false, 'error' => 'No se encontraron seguidores.']);
                exit;
            }
            break;
        
        case 'seguidos':
            $usuarios = obtener_tipo_contacto($database, $user, 2);
            if (empty($usuarios)) {
                echo json_encode(['success' => false, 'error' => 'No se encontraron seguidos.']);
                exit;
            }
            break;
            
        default:
            echo json_encode(['success' => false, 'error' => 'Acción no válida.']);
            exit;
    }
    echo json_encode([
        'success' => true,
        'data' => $usuarios
    ]);
    exit;// Asegúrate de detener la ejecución después de enviar la respuesta

} catch(Exception $e) {
    echo json_encode([
        'success' => false,
        'error' => 'Error de base de datos: ' . $e->getMessage()
    ]);
    exit;
}