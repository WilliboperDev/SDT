<?php
// Configuración de conexión a la base de datos utilizando la librería Medoo

$credencialesPath = ROOT_PATH2 . '/vendor/autoload.php';
if (!file_exists($credencialesPath)) {
    throw new RuntimeException('Archivo autoload no encontrado');
}
require_once $credencialesPath;
use Dotenv\Dotenv;
use Medoo\Medoo;

// Configuración inicial
$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

try {
    $database = new Medoo([
        'type' => $_ENV['DB_TYPE'] ?? 'mysql',
        'host' => $_ENV['DB_HOST'],
        'database' => $_ENV['DB_NAME'],
        'username' => $_ENV['DB_USER'],
        'password' => $_ENV['DB_PASS'],
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
        'port' => (int)$_ENV['DB_PORT'] ?? 3306,
        'option' => [
            PDO::ATTR_PERSISTENT => true,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
        ]
    ]);
} catch (PDOException $e) {
    error_log("Error de conexión: " . $e->getMessage());
    http_response_code(500);
    return false;
}