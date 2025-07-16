<?php
session_start(); // Iniciar la sesión
header('Content-Type: application/json');

// Verificar si la petición es POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') { // Si no es una petición POST o intenta acceder directamente
    http_response_code(405); // Método no permitido
    echo json_encode(['error' => 'Metodo no permitido']);
    exit;
}
require_once dirname(__DIR__) . '/Config/def_ruta.php';
$user = $_SESSION['usuario'] ?? null; // Obtener el usuario de la sesión
if (!isset($user) || empty($user)) {
    header('Location: ' . $appUrl);
    exit;
}
// Configuración inicial
require_once ROOT_PATH . '/Config/conexion.php';
require_once ROOT_PATH . '/Controllers/perfil_controller.php';

// Verificar si la conexión a la base de datos está disponible
if (!isset($database)) { 
    echo json_encode(['success' => false, 'error' => 'BD desconectada.']);
    exit;
}

try {
    $datos = $_POST;
    if (isset($datos['ind'])) {
        // Determinar el tipo de consulta
        $action = $datos['ind'] ?? null;
    }

    switch ($action) {

        case 'consultaPerfil':
            
            $resultado = obtener_perfil($database, $user);
            if ($resultado) {
                $usuarios = $resultado;
            } else {
                echo json_encode(['success' => true, 'message' => 'No existe']);
                exit;
            }
            break;
        
        case 'userPerfil':
            $avatar = $_FILES['avatar'] ?? null;   // Obtener el archivo de imagen
            if (isset($avatar['name']) && $avatar['error'] === UPLOAD_ERR_OK) { 
                
                // Sanitizar el nombre del archivo
                $fileName = preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $avatar['name']);
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
                if (!in_array($avatar['type'], $allowedMimeTypes)) {
                    echo json_encode([
                        'success' => false,
                        'error' => 'El archivo debe ser una imagen válida (JPEG, PNG, GIF).'
                    ]);
                    exit;
                }
                // Validar el tamaño del archivo (máximo 2 MB)
                $maxSize = 2 * 1024 * 1024; // 2 MB
                if ($avatar['size'] > $maxSize) {
                    echo json_encode([
                        'success' => false,
                        'error' => 'El archivo es demasiado grande. El tamaño máximo permitido es de 2 MB.'
                    ]);
                    exit;
                }

                // Busco el id del usuario en BD
                $id_user = obtener_id_user($database, $user);
                if (!$id_user) {
                    echo json_encode([
                        'success' => false,
                        'error' => 'No se encontró el ID del usuario.'
                    ]);
                    exit;
                }

                // Mover el archivo a una ubicación segura
                $uploadDir = ROOT_PATH2 . '/Public/img/avatars/' . $id_user . '/';

                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true); // Crear el directorio si no existe
                }
                $newFileName = uniqid('avatar_', true) . '.' . $fileExtension;
                $uploadPath = $uploadDir . $newFileName;
                if (!move_uploaded_file($avatar['tmp_name'], $uploadPath)) {
                    echo json_encode([
                        'success' => false,
                        'error' => 'Hubo un error al mover el archivo. Inténtalo nuevamente.'
                    ]);
                    exit;
                }

                // Eliminar el avatar anterior del sistema de archivos
                $archAv = busca_avatar($database, $user);
                if ($archAv) {
                    $filePath = ROOT_PATH2 . '/' . $archAv;
                    if (file_exists($filePath)) {
                        unlink($filePath); // Eliminar el archivo de imagen
                    }
                }
                // Ruta a guardar en BD
                $urlcorta = 'Public/img/avatars/' . $id_user . '/' . $newFileName;
            
            } else {
                $urlcorta = '';
            }
            
            // Sanitizando y validando los campos recibidos del cliente
            if (empty($datos['nombre']) || empty($datos['Categoria']) || empty($datos['descrip']) || empty($datos['ext']) || empty($datos['phone']) || 
                empty($datos['estado']) || empty($datos['municipio']) || empty($datos['parroquia']) || empty($datos['direccion']) || empty($datos['horario_apertura']) || empty($datos['horario_cierre'])) {
                
                echo json_encode(['success' => false, 'error' => 'Todos los campos son obligatorios.']); // Enviar JSON al cliente
                exit;
            }
            // Validar el campo de nombre
            // Sanitizar
            $nombre = trim($datos['nombre'] ?? '');
            // Validación
            $nombreLength = mb_strlen($nombre, 'UTF-8');
            if ($nombreLength < 5 || $nombreLength > 30) {
                http_response_code(400);
                echo json_encode(['error' => 'El nombre debe tener entre 5 y 30 caracteres']);
                exit;
            }
            if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $nombre)) {
                echo json_encode(['success' => false, 'error' => 'El nombre solo puede contener letras y espacios.']);
                exit;
            }

            // Validar el campo de categoría
            // Sanitizar
            $categoria = trim($datos['Categoria'] ?? '');
            if ($categoria !== 'OTRO') {
                // Validación
                if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ_\s]+$/", $categoria)) { 
                    echo json_encode(['success' => false, 'error' => 'La categoría no es válida.']);
                    exit;
                }
                // Verificar que la categoría exista en la base de datos
                $categoria = verificar_categoria($database, $categoria);
                if (!$categoria) {
                    echo json_encode(['success' => false, 'error' => 'La categoría no existe.']);
                    exit;
                }
            } else {
                if (isset($datos['otra_profesion'])) {
                    // Sanitizar
                    $otra_categoria = trim($datos['otra_profesion'] ?? '');
                    // Validación
                    if (!preg_match("/^[a-zA-ZáéíóúÁÉÍÓÚñÑ\s]+$/", $otra_categoria)) { 
                        echo json_encode(['success' => false, 'error' => 'La categoría no es válida.']);
                        exit;
                    }
                    $categoria = $otra_categoria; // Asignar la nueva categoría
                } else {
                    // Si la categoría es "OTRO" pero no se especifica una nueva categoría
                    echo json_encode(['success' => false, 'error' => 'Debe especificar una profesión.']);
                    exit;
                }
            }
            
            // Validar el campo de descripción
            // Sanitizar
            $descrip = trim($datos['descrip'] ?? '');
            // Validación
            $descripLength = mb_strlen($descrip, 'UTF-8');
            if ($descripLength < 5 || $descripLength > 200) {
                echo json_encode(['success' => false, 'error' => 'La descripción debe tener entre 5 y 200 caracteres.']);
                exit;
            }
            // Validar el campo codigo de área
            // Sanitizar
            $ext_tl = trim($datos['ext'] ?? '');
            $ext_tl = filter_var($ext_tl, FILTER_SANITIZE_NUMBER_INT);
            // Validación
            if (!preg_match("/^\d+$/", $ext_tl)) { 
                echo json_encode(['success' => false, 'error' => 'El código no es válido.']);
                exit;
            }
            // Verificar que el codigo exista en la base de datos
            $ext_tl = consultar_codigo_area($database, $ext_tl);
            if (!$ext_tl) {
                echo json_encode(['success' => false, 'error' => 'El código área no existe.']);
                exit;
            }

            // Validar el campo de teléfono
            // Sanitizar
            $telefono = trim($datos['phone'] ?? '');
            $telefono = filter_var($telefono, FILTER_SANITIZE_NUMBER_INT);
            // Validación
            if (!preg_match("/^\d{7}$/", $telefono)) { // Validar el teléfono (7 dígitos)
                echo json_encode(['success' => false, 'error' => 'El teléfono debe tener 7 dígitos.']);
                exit;
            }
            // Validar el campo de estado
            // Sanitizar
            $estado = trim($datos['estado'] ?? '');
            $estado = filter_var($estado, FILTER_SANITIZE_NUMBER_INT);
            // Validación
            if (!preg_match("/^\d+$/", $estado)) {
                echo json_encode(['success' => false, 'error' => 'El estado no es válido.']);
                exit;
            }

            // Validar el campo de municipio
            // Sanitizar
            $municipio = trim($datos['municipio'] ?? '');
            $municipio = filter_var($municipio, FILTER_SANITIZE_NUMBER_INT);
            // Validación
            if (!preg_match("/^\d+$/", $municipio)) { 
                echo json_encode(['success' => false, 'error' => 'El municipio no es válido.']);
                exit;
            }
            
            // Validar el campo de parroquia
            // Sanitizar
            $parroquia = trim($datos['parroquia'] ?? '');
            $parroquia = filter_var($parroquia, FILTER_SANITIZE_NUMBER_INT);
            // Validación
            if (!preg_match("/^\d+$/", $parroquia)) { 
                echo json_encode(['success' => false, 'error' => 'La parroquia no es válida.']);
                exit;
            }
            
            // Validar el campo de dirección
            // Sanitizar
            $direccion = trim($datos['direccion'] ?? '');
            // Validación
            $direccionLength = mb_strlen($direccion, 'UTF-8');
            if ($direccionLength < 5 || $direccionLength > 200) {
                echo json_encode(['success' => false, 'error' => 'La dirección debe tener entre 5 y 200 caracteres.']);
                exit;
            } 
            // Validar el campo de horario de apertura
            // Sanitizar
            $horario_aper = trim($datos['horario_apertura'] ?? '');
            $horario_aper = filter_var($horario_aper, FILTER_SANITIZE_NUMBER_INT);
            // Validación
            if (!preg_match("/^\d+$/", $horario_aper)) { 
                echo json_encode(['success' => false, 'error' => 'El horario apertura no es válido.']);
                exit;
            }
            // Verificar que el horario exista en la base de datos
            $horario_aper = verificar_horario($database, $horario_aper, true);
            if (!$horario_aper) {
                echo json_encode(['success' => false, 'error' => 'El horario apertura no existe.']);
                exit;
            }

            // Validar el campo de horario de cierre
            // Sanitizar
            $horario_cie = trim($datos['horario_cierre'] ?? '');
            $horario_cie = filter_var($horario_cie, FILTER_SANITIZE_NUMBER_INT);
            // Validación
            if (!preg_match("/^\d+$/", $horario_cie)) { 
                echo json_encode(['success' => false, 'error' => 'El horario cierre no es válido.']);
                exit;
            }
            // Verificar que el horario exista en la base de datos
            $horario_cie = verificar_horario($database, $horario_cie, false);
            if (!$horario_cie) {
                echo json_encode(['success' => false, 'error' => 'El horario cierre no existe.']);
                exit;
            }

            // Validar el campo de no-website
            // Verificar si el checkbox "no_website" está marcado
            $no_website = isset($datos['no-website']) ? true : false;
            // Validar el campo "web" solo si "no_website" no está marcado
            if (!$no_website) {
                // Sanitizar el campo "web"
                $web = trim($datos['web'] ?? '');
                $web = filter_var($web, FILTER_SANITIZE_URL);

                // Validar el formato del sitio web
                if (!empty($web) && !filter_var($web, FILTER_VALIDATE_URL)) {
                    echo json_encode(['success' => false, 'error' => 'El sitio web debe tener un formato válido (http:// o https://).']);
                    exit;
                }
            } else {
                // Si "no_website" está marcado, ignorar el campo "web"
                $web = '';
            }

            // Verificar si es una actualización o una inserción
            if (isset($datos['accion'])) {
                $accion = $datos['accion'] ?? null;
                if ($accion === 'update') {
                    $perfil = verificar_cambios($database, $user);

                    // Verificar si tiene nuevo avatar asignado 
                    if ($perfil['avatar'] && $urlcorta === '') {
                        // Mantener el avatar existente
                        $urlcorta = $perfil['avatar']; 
                    }
                    // Comparar los datos
                    if ($perfil['avatar'] === $urlcorta && $perfil['nombre'] === $nombre && $perfil['categoria'] === $categoria && 
                        $perfil['descripcion'] === $descrip && $perfil['telefono'] === $ext_tl . '-' . $telefono && 
                        $perfil['estado'] === $estado && $perfil['municipio'] === $municipio && 
                        $perfil['parroquia'] === $parroquia && $perfil['direccion'] === $direccion && 
                        $perfil['horario_ap'] === $horario_aper && $perfil['horario_ci'] === $horario_cie && 
                        $perfil['web'] === $web) {
                        
                        echo json_encode(['success' => true, 'message' => 'No hay cambios']);
                        exit;
                    }
                    // Actualizar el perfil
                    $usuario = actualizar_perfil_usuario($database, $user, $urlcorta, $nombre, $categoria, $descrip, $telefono, $ext_tl, $estado, $municipio, $parroquia, $direccion, $horario_aper, $horario_cie, $web);
                } else {
                    // insertar el perfil
                    $usuario = insertar_perfil_usuario($database, $user, $urlcorta, $nombre, $categoria, $descrip, $telefono, $ext_tl, $estado, $municipio, $parroquia, $direccion, $horario_aper, $horario_cie, $web);
                }
            }
            
            if (!$usuario) {
                echo json_encode(['success' => false, 'error' => 'Error al guardar los datos del perfil.']);
                exit;
            }
            $usuarios = '';
            break;
    default:
        echo json_encode(['success' => false, 'error' => 'Acción no válida.']);
        exit;
    }
    // Procesar los datos...
    echo json_encode([
        'success' => true,
        'data' => $usuarios
    ]);
    exit;
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    exit;
}