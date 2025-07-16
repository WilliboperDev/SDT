<?php
// Matar la sesión y redirigir al inicio
require_once dirname(__DIR__) . '/Config/def_ruta.php';
session_start();
session_unset();
session_destroy();
header('Location: ' . $appUrl); 
exit;