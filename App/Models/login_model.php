<?php
// Logica de acceso a datos para el registro de usuarios
function obtener_usuario_por_token($db, $codtoken, $email) {
    // Retorna un solo registro si tiene id unico
    $condicion = [];
    if ($codtoken !== null) {
        $condicion['id'] = $codtoken;
    }
    if ($email !== null) {
        $condicion['email'] = $email;
    }
    if (empty($condicion)) {
        // No se proporcionó ni id ni email
        return false;
    }
    return $db->get('temp_login', '*', $condicion); 
}

function insertar_usuario_temp($db, $email, $clave) {
    $usuarios = $db->insert('temp_login', ['email' => $email, 'clave' => $clave]); 
    return $usuarios ? true : false;
}

function actualizar_codigo_temp($db, $codverifi, $correo) {
    $usuarios = $db->update('temp_login', ['codigo' => $codverifi], ['email' => $correo]);
    return $usuarios ? true : false;
}

function eliminar_token_temp($db, $codtoken, $email) {
    $condicion = [];
    if ($codtoken !== null) {
        $condicion['id'] = $codtoken;
    }
    if ($email !== null) {
        $condicion['email'] = $email;
    }
    if (empty($condicion)) {
        // No se proporcionó ni id ni email
        return false;
    }
    $usuarios = $db->delete('temp_login', $condicion);
    return $usuarios ? true : false;
}

function buscar_usuario_por_email($db, $correo) {
    return $db->get('login', '*', ['email' => $correo]);
}

function insertar_usuario_login($db, $email, $clave) {
    $nuevoUser = [
        'email' => $email, 
        'password' => password_hash($clave, PASSWORD_DEFAULT) // clave encriptado
    ];
    $usuarios = $db->insert('login', $nuevoUser);
    return $usuarios ? true : false;
}

function actualizar_clave($db, $email, $clave) {
    $nuevoUser = [
        'password' => password_hash($clave, PASSWORD_DEFAULT) // clave encriptado
    ];
    $usuarios = $db->update('login', $nuevoUser, ['email' => $email]);
    return $usuarios ? true : false;
}

function obtener_hora_contador($db, $codigo) {
    // Verificar si hay fecha cargada en la tabla
    return $db->get('temp_login', ['hora_ini'], ['id' => $codigo]);
}

function fecha_hora_contador($db, $codigo) {
    // Verificar si hay fecha cargada en la tabla
    return $db->has('temp_login', ['id'=> $codigo,'hora_ini' => '']);
}

function actualiza_hora_contador($db, $codigo, $horaInicio) {
    $usuarios = $db->update('temp_login', ['hora_ini' => $horaInicio], ['id' => $codigo]);
    return $usuarios ? true : false;
}



