<?php
// Logica de acceso a datos para cambio de clave

function obtener_clave($db, $user) {
    return $db->get('login', ['password'], ['email' => $user]);
}

function update_clave($db, $nuevaClaveHash, $user) {
    return $db->update('login', ['password' => $nuevaClaveHash], ['email' => $user]);
}