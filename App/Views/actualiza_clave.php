<?php 
// bloqueo para evitar entrar directamente desde el navegador
require_once dirname(__DIR__) . '/Config/def_ruta.php';
require_once ROOT_PATH . '/Config/cabeceras.php';
?>

<!DOCTYPE html> 
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Actualiza clave</title>
    <link rel="stylesheet" href= "<?= htmlspecialchars($appUrl, ENT_QUOTES) ?>/Public/css/style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">  
</head>
<body>
    <div class="container">
        <h1 class="title-regis">Sección de cambio de clave</h1>
        <p>Actualiza la contraseña de acceso a tu cuenta de usuario para mayor seguridad.</p>
        <form id="formuAct" class="form-grid" style="width: 40%; margin-left: 15%; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); border-radius: 10px;">
            <div class="input-box"> 
                <label for="conan">Contraseña anterior:</label>
                <input type="password" name="conan" id="conan" placeholder="Ingrese clave anterior" minlength="6" maxlength="16">
            </div>
            <div class="input-box"> 
                <label for="conac">Nueva contraseña:</label>
                <input type="password" name="conac" id="conac" placeholder="Ingrese nueva clave" minlength="6" maxlength="16">
            </div>
            <div class="input-box"> 
                <label for="conacr">Repetir nueva contraseña:</label>
                <input type="password" name="conacr" id="conacr" placeholder="Repita nueva clave" minlength="6" maxlength="16">
            </div>

            <!--IMPRIMIR MENSAJES DE ERROR-->
            <div id="errorDiv2" class="errorDiv2"></div>

            <div class="input-rec">
                <button style="font-size: 15px" type="submit">Confirmar cambios <i class="fas fa-paper-plane"></i></button>
            </div>
        </form>
    </div>

    <!-- Mostrar loader y overlay -->
    <div id="overlay" class="overlay"></div> <!-- Overlay (fondo opaco) -->
    <div id="loader" class="loader">
        <span id="loader-message"></span>
    </div> <!-- Loader -->

</body>
</html>