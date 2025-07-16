<?php
// Logica de control para cambio de clave
require_once ROOT_PATH . '/Models/cambio_clave_model.php';

function obtener_clave_login($db, $user) {
    $cambio = obtener_clave($db, $user);
    if (!$cambio) {
        return[];
    }
    return $cambio['password'];
}

function actualiza_clave($db, $nuevaClaveHash, $user) {
    $actua = update_clave($db, $nuevaClaveHash, $user);
    return $actua ? true : false;

}