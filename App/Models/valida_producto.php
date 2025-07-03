<?php
// Inicializar la sesión
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { // Si no es una petición POST o intenta acceder directamente
    http_response_code(405); // Método no permitido
    echo json_encode(['error' => 'Metodo no permitido']);
    exit;
}
// Validar si hay un usuario en la sesión
$user = $_SESSION['usuario'];
if (!isset($user) || empty($user)) {
    echo json_encode([]);
    exit;
}
require_once __DIR__ . '/../Config/conexion.php';
require_once __DIR__ . '/../Controllers/producto_controller.php';

// Verificar si la conexión a la base de datos está disponible
if (!isset($database)) { 
    echo json_encode(['success' => false, 'error' => 'No se puede acceder a los datos.']);
    exit;
}

// verificar si la petición viene con JSON o como formulario (POST) 
if (
    isset($_SERVER['CONTENT_TYPE']) &&
    strpos($_SERVER['CONTENT_TYPE'], 'application/json') !== false
) {
    // Procesar como JSON
    $data = json_decode(file_get_contents('php://input'), true);
} else {
    // Procesar como formulario
    $data = $_POST;
}

if (!isset($data) || empty($data)) {
    echo json_encode(['success' => false, 'error' => 'No se recibieron datos.']);
    exit;
}

try {
    // Determinar el tipo de consulta
    $action = $data['action'] ?? null;
    
    switch($action) {
        case 'productos':

            // Sanitizando el campo recibido del cliente 
            $email = filter_var($data['email'] ?? '', FILTER_SANITIZE_EMAIL); 

            $productos = procesar_productos($database, $email);
            if (empty($productos)) {
                echo json_encode(['success' => false, 'productos' => []]);
                exit;
            }
            break;

        case 'inserta_producto':
            $id_user = verificar_usuario($database, $user);
            if (!$id_user) {
                echo json_encode(['success' => false, 'error' => 'No existe el usuario.']);
                exit;
            }
            // Verificar si los campos no están vacíos
            if (empty($data['productName']) || empty($data['productDescription']) || empty($_FILES['hiddenProductImage'])) {
                echo json_encode(['success' => false, 'error' => 'Todos los campos son obligatorios.']); // Enviar JSON al cliente
                exit;
            }
            // Sanitizar y validar los campos recibidos del cliente
            // Campo nombre
            $nombre = trim($data['productName'] ?? '');
            // Validación
            $nombreLength = mb_strlen($nombre, 'UTF-8');
            if ($nombreLength < 3 || $nombreLength > 30) {
                http_response_code(400);
                echo json_encode(['error' => 'El nombre debe tener entre 3 y 30 caracteres']);
                exit;
            }
            if (!preg_match("/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s]+$/", $nombre)) {
                echo json_encode(['success' => false, 'error' => 'El nombre tiene un caracter no esperado.']);
                exit;
            }
            // Campo descripción
            $descripcion = trim($data['productDescription'] ?? '');
            // Validación
            $descripcionLength = mb_strlen($descripcion, 'UTF-8');
            if ($descripcionLength < 3 || $descripcionLength > 70) {
                http_response_code(400);
                echo json_encode(['error' => 'La descripción debe tener entre 3 y 70 caracteres']);
                exit;
            }
            if (!preg_match("/^[a-zA-Z0-9áéíóúÁÉÍÓÚñÑ\s]+$/", $descripcion)) {
                echo json_encode(['success' => false, 'error' => 'La descripción tiene un caracter no esperado.']);
                exit;
            }
            // Campo imagen
            $producto = $_FILES['hiddenProductImage'] ?? null;
            if (isset($producto['name']) && $producto['error'] === UPLOAD_ERR_OK) { 
                //error_log(Print_r($avatar,true));

                // Sanitizar el nombre del archivo
                $fileName = preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $producto['name']);
                // Obtener la extensión del archivo
                $fileExtension = strtolower(pathinfo($fileName, PATHINFO_EXTENSION));
                
                // Validar el tipo de archivo (solo imágenes permitidas)
                $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
                if (!in_array($fileExtension, $allowedExtensions)) {
                    echo json_encode([
                        'success' => false,
                        'error' => 'La extensión del archivo no es válida. Solo se permiten archivos JPG, JPEG, PNG o GIF.'
                    ]);
                    exit;
                }
                // Validar el tipo MIME del archivo
                $allowedMimeTypes = ['image/jpeg', 'image/png', 'image/gif'];
                if (!in_array($producto['type'], $allowedMimeTypes)) {
                    echo json_encode([
                        'success' => false,
                        'error' => 'El archivo debe ser una imagen válida (JPEG, PNG, GIF).'
                    ]);
                    exit;
                }
                // Validar el tamaño del archivo (máximo 2 MB)
                $maxSize = 2 * 1024 * 1024; // 2 MB
                if ($producto['size'] > $maxSize) {
                    echo json_encode([
                        'success' => false,
                        'error' => 'El archivo es demasiado grande. El tamaño máximo permitido es de 2 MB.'
                    ]);
                    exit;
                }
                // Mover el archivo a una ubicación segura
                $uploadDir = $_SERVER['DOCUMENT_ROOT'] . '/SDT/Public/img/avatars/' . $id_user . '/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true); // Crear el directorio si no existe
                }
                $newFileName = uniqid('producto_', true) . '.' . $fileExtension;
                $uploadPath = $uploadDir . $newFileName;
                if (!move_uploaded_file($producto['tmp_name'], $uploadPath)) {
                    echo json_encode([
                        'success' => false,
                        'error' => 'Hubo un error al mover el archivo. Inténtalo nuevamente.'
                    ]);
                    exit;
                }
            } else {
                echo json_encode(['success' => false, 'error' => 'No se recibió una imagen válida.']);
                exit;
            }
            // Ruta a guardar en BD
            $urlcorta = '/SDT/Public/img/avatars/' . $id_user . '/' . $newFileName;

            // Guardar datos
            $addPro = insertar_producto($database, $nombre, $descripcion, $urlcorta, $id_user, );
            if (empty($addPro)) {
                echo json_encode(['success' => false, 'error' => 'Error al insertar el producto.']);
                exit;
            }
            // Enviar respuesta
            $productos = [
                'id' => $addPro,
                'name' => $nombre,
                'description' => $descripcion,
                'imageUrl' => $urlcorta
            ];
            break;
        
        case 'elimina_producto':

            // Verificar si el ID recibido es válido
            if (empty($data['id'])) {
                echo json_encode(['success' => false, 'error' => 'ID no válido.']);
                exit;
            }
            
            // Sanitizando Campo id
            $id = filter_var($data['id'] ?? '', FILTER_SANITIZE_NUMBER_INT);
            
            // Eliminar el producto y el archivo de imagen en el directorio
            $producto = eliminar_producto($database, $id, $user);
            if (empty($producto)) {
                echo json_encode(['success' => false, 'error' => 'Error al eliminar el producto.']);
                exit;
            }
            $filePath = $_SERVER['DOCUMENT_ROOT'] . $producto;
            if (file_exists($filePath)) {
                unlink($filePath); // Eliminar el archivo de imagen
            } 
            // Enviar respuesta
            $productos = ['success' => true];

        default:
            echo json_encode(['success' => false, 'error' => 'Acción no válida.']);
            exit;
    }
    // Enviar respuesta a JS
    //echo json_encode($productos);
    echo json_encode(['success' => true, 'productos' => $productos]);
    exit;

} catch (Exception $e) {
    // Manejo de errores
    error_log("Error al obtener productos: " . $e->getMessage());
    echo json_encode([]); // Retorna un array vacío si $database no es válido
    exit;
}