<?php
// Logica de acceso a datos para productos del usuario

function verificar_usuario($db, $email) {
    return $db->get('login', 'id', ['email' => $email]);
}

function obtener_productos($db, $email) {
    // Busco el id del usuario
    $id_user = verificar_usuario($db, $email);
    if (!$id_user) {
        return[];
    }
    // Obtiene los productos del usuario 
    return $db->select('productos', [
        'id',
        'name',
        'description',
        'imageUrl'
    ], [
        'id_user' => $id_user
    ]);
}

function add_producto($db, $nombre, $descripcion, $ruta, $id) {
    $db->insert('productos', [
        'name' => $nombre,
        'description' => $descripcion,
        'imageUrl' => $ruta,
        'id_user' => $id
    ]);
    // Capturar el ID del producto insertado para el slider
    $id_producto = (int) $db->id(); // Forzar a entero
    return $id_producto;
}

function obtener_url_producto($db, $id, $id_user) {
    return $db->get('productos', 'imageUrl', ['id' => $id, 'id_user' => $id_user]);
}

function delete_producto($db, $id, $id_user) {
    $elimi = $db->delete('productos', ['id' => $id, 'id_user' => $id_user]);
    return $elimi ? true : false;
}