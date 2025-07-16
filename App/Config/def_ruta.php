<?php
/**
 * Archivo de configuración de rutas y URLs del proyecto
 * 
 * @package Config
 */

// Variable para la URL base del proyecto
if (!defined('APP_URL')) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    $scriptName = dirname(dirname(dirname($_SERVER['SCRIPT_NAME'])));
    $baseUrl = rtrim($protocol . $host . $scriptName, '/\\');
    define('APP_URL', $baseUrl);
    // Sanitizar la URL para evitar XSS
    $appUrl = filter_var(APP_URL, FILTER_SANITIZE_URL);

}
// Variable para la URL App del proyecto 
if (!defined('APP_URL2')) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    $scriptName = dirname(dirname($_SERVER['SCRIPT_NAME']));
    $baseUrl = rtrim($protocol . $host . $scriptName, '/\\');
    define('APP_URL2', $baseUrl);
    // Sanitizar la URL para evitar XSS
    $appUrl2 = filter_var(APP_URL2, FILTER_SANITIZE_URL);
}

// Variable para 404 URL base 
if (!defined('APP_URL3')) {
    $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
    $host = $_SERVER['HTTP_HOST'];
    $scriptName = dirname($_SERVER['SCRIPT_NAME']);
    $baseUrl = rtrim($protocol . $host . $scriptName, '/\\');
    define('APP_URL3', $baseUrl);
    // Sanitizar la URL para evitar XSS
    $appUrl3 = filter_var(APP_URL3, FILTER_SANITIZE_URL);
}

// Ruta al directorio App 
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__)); 
}

// Ruta al directorio base 
if (!defined('ROOT_PATH2')) {
    define('ROOT_PATH2', dirname(__DIR__, 2)); 
}