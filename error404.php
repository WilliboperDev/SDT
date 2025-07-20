<?php
session_start();
http_response_code(404); // Obligatorio para SEO

require_once __DIR__ . '/App/Config/def_ruta.php';
if(isset($_SESSION['usuario'])){
    $redirig = $appUrl3 . '/dashboard';
} else {
    $redirig = $appUrl3;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Página no encontrada</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href= "Public/css/style.css">
</head>
<body class="backfond2">
    <div class="container-404">
        <img src="Public/img/error-404.gif" alt="Error 404">
        <p class="p-text">La página que buscas no existe o ha sido movida.</p>
        <a class="btn-404" href="<?= htmlspecialchars($redirig, ENT_QUOTES) ?>">Volver al inicio</a>
    </div>
    <script src="Public/js/validar.js"></script>
</body>
</html>
