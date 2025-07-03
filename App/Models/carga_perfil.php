<?php
require_once __DIR__ . '/../Config/conexion.php';
require_once __DIR__ . '/../Controllers/perfil_controller.php';

header('Content-Type: application/json');

// Verificar si la petición es POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { // Si no es una petición POST o intenta acceder directamente
    http_response_code(405); // Método no permitido
    echo json_encode(['error' => 'Metodo no permitido']);
    exit;
}
// Verificar si la conexión a la base de datos está disponible
if (!isset($database)) { 
    echo json_encode(['success' => false, 'error' => 'BD desconectada.']);
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$action = $data['accion'] ?? null;

switch ($action) {
    case 'categoria':
        carga_categoria_perfil($database);
        break;
    
    case 'codarea':
        carga_codarea_perfil($database);
        break;
    
    case 'estados':
        carga_estados_perfil($database);
        break;
    
    case 'municipios':
        carga_municipios_perfil($database, $data['estado_id']);
        break;
    
    case 'parroquias':
        carga_parroquias_perfil($database, $data['municipio_id']);
        break;

    case 'horarios':
        carga_horarios_perfil($database);
        break;

    default:
        echo json_encode(['success' => false, 'error' => 'Acción no válida.']);
        break;
}
    