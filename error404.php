<?php
session_start(); 
http_response_code(404);
if(isset($_SESSION['usuario'])){
    $redirig = '/SDT/dashboard';
} else {
    $redirig = '/SDT/';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Página no encontrada</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="/SDT/Public/css/style.css">
</head>
<body class="backfond2">
    <div class="container-404">
        <img src="/SDT/Public/img/error-404.gif" alt="Error 404">
        <p class="p-text">La página que buscas no existe o ha sido movida.</p>
        <a class="btn-404" href="<?php echo htmlspecialchars($redirig); ?>">Volver al inicio</a>
    </div>