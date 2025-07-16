<?php
require_once dirname(__DIR__) . '/Config/def_ruta.php';
// Valida la variable del htaccess para permitir solo el acceso desde la URL amigable
if (!isset($_GET['from_rewrite']) || $_GET['from_rewrite'] != 1) {
    http_response_code(403);
    // Destruye la sesion y redirige
    require_once ROOT_PATH . '/Models/logout.php';
}
$codigo = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT, ["options" => ["min_range" => 1]]);
    if ($codigo === false) {
        // Destruye la sesion y redirige
        require_once ROOT_PATH . '/Models/logout.php';
    }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Codigo de verificacion</title>
    <link rel="stylesheet" href= "<?= htmlspecialchars($appUrl, ENT_QUOTES) ?>/Public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body id="cod-verif-page" class="backfond">
    <div class="contenedor">
        <div class="title-verifi">
            <h1 class="title-regis">Validacion de registro</h1>
            <h2 style="font-size: 14px;">Ingrese el código que ha sido enviado a su correo electrónico.</h2>
        </div>
        <div class="cuadecore"> 
            <form id="for-cod" class="form-grid">
                <!--campo oculto para guardar el id del token-->
                <input type="hidden" id="codeval" name="codeval" value="<?php echo htmlspecialchars($codigo); ?>">  
                
                <div>
                    <input id="cod" name="cod" style="max-width: 40%; text-align:center;" type="text" pattern="\d+" title="Solo se permiten números." maxlength="5" required>
                    <button id="enviar"type="submit"><i class="fa-solid fa-check"></i></button>
                    <p id="contador"></p>
                    <p id="mensaje"></p>
                </div>

                <!--IMPRIMIR MENSAJES DE CONFIRMACION O ERROR-->
                <div id="errorDiv2" class="errorDiv2"></div>
                <div id="successDiv" style="display:none; color:green;"></div>  
            </form>
        </div>  
    </div>

    <!-- Mostrar loader y overlay -->
    <div id="overlay" class="overlay"></div> <!-- Overlay (fondo opaco) -->
    <div id="loader" class="loader"></div> <!-- Loader -->

    <!-- Para el modal de confirmacion -->
    <div id="successModal" class="modal" style="display: none;">
        <div class="modal-content">
            <!--<span class="close-btn">&times;</span>-->
            <h2>Usuario creado</h2>
            <p>Ya puedes iniciar sesion al cerrar.</p>
            <button class="close-btn" type="button"><i class="fa-solid fa-house"></i></button>
        </div>
    </div>
    
    <!-- JavaScript Libraries -->
    <script src="<?= htmlspecialchars($appUrl, ENT_QUOTES) ?>/Public/js/validar.js"></script>
</body>
</html>