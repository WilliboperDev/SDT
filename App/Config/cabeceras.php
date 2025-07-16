<?php 
// Verificar si la petición es AJAX (fetch)
// aplica si entras directamente con la url y no desde el dashboard
if (
    !isset($_SERVER['HTTP_X_REQUESTED_WITH']) ||
    strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) !== 'xmlhttprequest'
) {
    // Destruye la sesion y redirige
    require_once ROOT_PATH . '/Models/logout.php';
}
?>