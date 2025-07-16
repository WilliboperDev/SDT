<?php
// Conexión a la base de datos
require_once dirname(__DIR__) . '/Config/def_ruta.php';
require_once ROOT_PATH . '/Config/conexion.php';

$stmt = $database->query("SELECT DISTINCT nombre FROM categorias ORDER BY sector");

echo json_encode(['categorias' => $stmt]);