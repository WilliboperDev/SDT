<?php
// Conexión a la base de datos ($db)
require_once __DIR__ . '/../Config/conexion.php';


$stmt = $database->query("SELECT DISTINCT nombre FROM categorias ORDER BY sector");

echo json_encode(['categorias' => $stmt]);