<?php
require_once ROOT_PATH . '/Models/login_model.php';

function procesar_login($db, $correo, $clave) {
    $usuarios = buscar_usuario_por_email($db, $correo);
    if (!$usuarios) {
        return ['success' => false, 'error' => 'El usuario no existe en el sistema.'];
    }
    if (!password_verify($clave, $usuarios['password'])) {
        return ['success' => false, 'error' => 'La clave es inválida.'];
    }
    return ['success' => true];
}

function recupera_login($db, $correo) {
    $usuarios = buscar_usuario_por_email($db, $correo);
    if (!$usuarios) {
        return ['success' => false, 'error' => 'El usuario no existe en el sistema.'];
    }
    $nuevaClave = generarContrasenaAleatoria(8); // Generar una nueva clave de 8 caracteres
    if ($nuevaClave === '') {
        return ['success' => false, 'error' => 'Error al generar la nueva clave.'];
    }
    return $nuevaClave;
}

function actualizar_clave_login($db, $correo, $clave) {
    if (!actualizar_clave($db, $correo, $clave)) {
        return ['success' => false, 'error' => 'Error al actualizar la clave.'];
    }
}