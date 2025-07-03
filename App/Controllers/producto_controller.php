<?php
// Logica de control para los productos del usuario
require_once __DIR__ . '/../Models/producto_model.php';

function procesar_productos($db, $email) {
    $productos = obtener_productos($db, $email);
    if (!$productos) {
        return [];
    }
    return $productos;
}

function insertar_producto($db, $nombre, $descripcion, $ruta, $id) {
    $resultado = add_producto($db, $nombre, $descripcion, $ruta, $id);
    if (!$resultado) {
        return [];
    }
    return $resultado;
}

function eliminar_producto($db, $id, $user) {
    $id_user = verificar_usuario($db, $user);
    if (!$id_user) {
        return [];
    }
    $imageUrl = obtener_url_producto($db, $id, $id_user);
    if (!$imageUrl) {
        return [];
    }
    $elim = delete_producto($db, $id, $id_user);
    if (!$elim) {
        return [];
    }
    return $imageUrl;
}