<?php
/**
 * Script para envío de correos electrónicos usando PHPMailer
 * 
 * @package Email
 */

// Verificar si Composer está instalado
$autoloadPath = ROOT_PATH2 . '/vendor/autoload.php';
if (!file_exists($autoloadPath)) {
    throw new RuntimeException('Ejecuta composer install primero');
}

// 1. Cargar autoloader primero (para que estén disponibles todas las clases)
require_once $autoloadPath;
// 2. Configuraciones específicas de la aplicación
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

// 3. Importaciones de namespaces (orden alfabético recomendado)
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer; 
use PHPMailer\PHPMailer\SMTP;

function enviarCorreo($username, $codv) {
    // Instancia de la clase PHPMailer
    $mail = new PHPMailer(true); // Pasar `true` para habilitar excepciones
    
    try { 
        // Configuración del servidor 
        $mail->SMTPDebug = 0; 
        //$mail->SMTPDebug = SMTP::DEBUG_SERVER;  // Genera un registro detallado
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Especifica el servidor SMTP 
        $mail->SMTPAuth = true; 
        $mail->Username = $GLOBALS['SMTP_US']; // Tu usuario SMTP 
        $mail->Password = $GLOBALS['SMTP_PA']; // Tu contraseña SMTP
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Remitente y destinatarios 
        $mail->setFrom($GLOBALS['SMTP_US'], 'Soporte_SDT'); 
        $mail->addAddress($username); 

        // Contenido del correo 
        $mail->isHTML(true); 
        $mail->Subject = 'Aqui tienes el codigo de verificacion de 5 dígitos que has solicitado'; 
        $mail->Body = '<p>Hola, Bienvenido!<br>Utiliza el siguiente código para crear tu cuenta en SDT.</p>
                    <p style="font-size: 25px;"><b>' . $codv . '</b></p>
                    <p>Este código caduca en 5 minutos.</p>
                    <br>    
                    *******************************************************************************************************************************
                    <p>¿No has solicitado este código?<br>Si no eres el origen de esta solicitud puedes ignorar este mensaje.</p>
                    <p><i>SDT, Directorio telefónico.</i></p>
                    *******************************************************************************************************************************'; 
        //$mail->AltBody = 'Este es el contenido del correo en texto plano';
        $mail->CharSet = 'UTF-8';  // uso para evitar error de visualizacion de la ñ
        $mail->Encoding = 'base64';

        $mail->send(); 
        return true;

    } catch (Exception $e) { 
        return false;
    }
}