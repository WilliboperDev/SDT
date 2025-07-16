<?php
// Establecer la cabecera Content-Type para indicar que se envía JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') { // Si no es una petición POST o intenta acceder directamente
    http_response_code(405); // Método no permitido
    echo json_encode(['error' => 'Metodo no permitido']);
    exit;
}

// Verificar si Composer está instalado
require_once dirname(__DIR__) . '/Config/def_ruta.php';
$autoloadPath = ROOT_PATH2 . '/vendor/autoload.php';
if (!file_exists($autoloadPath)) {
    throw new RuntimeException('Ejecuta composer install primero');
}

require_once ROOT_PATH . '/Config/conexion.php';
require_once ROOT_PATH . '/Models/generaclave.php';
require_once $autoloadPath; //Incluir el autoload de Composer
require_once ROOT_PATH . '/Controllers/login_controller.php';

// Carga el .env
$envDir = realpath(ROOT_PATH . '/Config/');
if (!$envDir) throw new RuntimeException("Directorio .env no encontrado");

$dotenv = Dotenv\Dotenv::createImmutable($envDir);
$dotenv->load();
$dotenv->required(['SMTP_US', 'SMTP_PA'])->notEmpty();

// Definir variables en ámbito global
$SMTP_US = $_ENV['SMTP_US'];
$SMTP_PA = $_ENV['SMTP_PA'];

// Verificar si las credenciales SMTP están definidas
if (empty($SMTP_US) || empty($SMTP_PA)) {
    throw new Exception('Credenciales SMTP no definidas.');
}
// Use de los espacios de nombres (namespaces) de PHPMailer
// Los use se colocan en la parte superior del script de PHP
// Estos son los use de las clases más importantes que puedes necesitar con PHPMailer
use PHPMailer\PHPMailer\PHPMailer; 
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

if (!isset($database)) { // Si no hay conexión a la base de datos
    echo json_encode(['success' => false, 'error' => 'No se puede acceder a los datos.']);
    exit;
}
$data = json_decode(file_get_contents('php://input'), true);
// Sanitizando los campos recibidos del cliente 
$correo = filter_var($data, FILTER_SANITIZE_EMAIL);

/*****************************************************************************************************************
                                        VALIDACIÓN DE LOS DATOS
******************************************************************************************************************/
if (empty($correo)) {
    echo json_encode(['success' => false, 'error' => 'El correo es obligatorio.']); // Enviar JSON al cliente
    exit;
}
// **** validar el email con el formato requerido
function validarEmail($email) {
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

/*****************************************************************************************************************
                                        PROCESAMIENTO EN BASE DE DATOS
******************************************************************************************************************/
try { // Manejo de excepciones

    //Verifica si el usuario existe en la BD
    $claveale = recupera_login($database, $correo);
    if ($claveale === false) {
        echo json_encode($claveale);
        exit;
    }

    //Enviar correo con la libreria PHPMAILER
    $mail = new PHPMailer(true);    

    // Configuración del servidor 
    $mail->SMTPDebug = 0; 
    //$mail->SMTPDebug = SMTP::DEBUG_SERVER;  // Genera un registro detallado
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com'; // Especifica el servidor SMTP 
    $mail->SMTPAuth = true; 
    $mail->Username = $SMTP_US; // Tu usuario SMTP 
    $mail->Password = $SMTP_PA; // Tu contraseña SMTP
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    // Remitente y destinatarios 
    $mail->setFrom($SMTP_US, 'Soporte_SDT'); 
    $mail->addAddress($correo); 
    // Contenido del correo 
    $mail->isHTML(true); 
    $mail->Subject = 'Aqui tienes el codigo de recuperación que has solicitado'; 
    $mail->Body = '<p>Hola, Bienvenido!<br>Utiliza el siguiente código temporal para iniciar sesión en tu cuenta de SDT.</p>
                <p style="font-size: 25px;"><b>' . $claveale . '</b></p>
                <p>Una vez que ingrese al sistema, Actualice su contraseña de preferencia.</p>
                <br> 
                *******************************************************************************************************************************
                <p>¿No has solicitado este código?<br>Si no eres el origen de esta solicitud puedes ignorar este mensaje.</p>
                <p><i>SDT, Directorio telefónico.</i></p>
                *******************************************************************************************************************************'; 
    //$mail->AltBody = 'Este es el contenido del correo en texto plano';
    $mail->CharSet = 'UTF-8';  // uso para evitar error de visualizacion de la ñ
    $mail->Encoding = 'base64';
    $mail->send(); 

    // Actualización de clave temporal
    $resultado = actualizar_clave_login($database, $correo, $claveale);
    if ($resultado === false) {
        echo json_encode($resultado);
        exit;
    }
} catch (Exception $e) {
    echo json_encode(['success' => false, 'error' => 'Error al enviar el mensaje, intente de nuevo.']);
    exit;
}
// Todo ok, no hay errores
echo json_encode(['success' => true, 'confirmado' => 'Se envio una clave de recuperacion a su correo electronico, <br>al ingresar a su perfil actualice la contraseña.']);
exit;
