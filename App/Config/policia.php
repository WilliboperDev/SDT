<!-- Control de redireccion de archivos (policia) -->
<?php 
//Iniciar una nueva sesión o reanudar la existente.
session_start(); 

/* la variable superglobal ($_SERVER['REQUEST_URI']) muestra de la URL solo el nombre de carpeta y archivo 
   con explode, extrae de la variable superglobal el nombre del archivo en la posicion 2 utilizando el delimitador (/) */
$url = explode("/", $_SERVER['REQUEST_URI'])[2];

switch ($url) {

case "dashboard":
    if(!isset($_SESSION['usuario'])){
        header('Location: /SDT/');
        exit();
    }
    // Configuración del tiempo máximo de inactividad (5 minutos)
    $inactividad = 300;

    // Verifica si $_SESSION["timeout"] está establecida
    if (isset($_SESSION["timeout"])) {
        // Calcula el tiempo de vida de la sesión (TTL = Time To Live)
        $sessionTTL = time() - $_SESSION["timeout"];
        if ($sessionTTL > $inactividad) {
            // Destruye la sesión y redirige
            header('Location: /SDT/App/Models/logout.php');
            exit();
        }
    }
    // Actualiza el tiempo de la última actividad
    $_SESSION["timeout"] = time();

    break;

default:
    header('Location: /SDT/');
    exit;

    break;
}