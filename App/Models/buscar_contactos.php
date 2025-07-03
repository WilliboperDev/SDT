<?php
// Conexión a la base de datos ($db)
require_once __DIR__ . '/../Config/conexion.php';


$data = json_decode(file_get_contents('php://input'), true);
$categoria = $data['categoria'] ?? '';
$busqueda = $data['busqueda'] ?? '';

$sql = "SELECT * FROM perfil WHERE 1";
$params = [];

if ($categoria) {
    $sql .= " AND categoria = ?";
    $params[] = $categoria;
}
if ($busqueda) {
    $sql .= " AND nombre LIKE ?";
    $params[] = "%$busqueda%";
}
$sql .= " ORDER BY categoria, nombre";

$stmt = $db->prepare($sql);
$stmt->execute($params);
$contactos = $stmt->fetchAll(PDO::FETCH_ASSOC);

echo json_encode(['contactos' => $contactos]);