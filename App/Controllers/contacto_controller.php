<?php
// Logica de control para los contactos
require_once ROOT_PATH . '/Models/contacto_model.php';

function contactos_dashboard($db, $user) {
    //muestra todos los contactos que no estan siendo seguidos por el usuario
    $contac = obtener_contactos_no_seguidos($db, $user);
    if (empty($contac)) {
        echo json_encode(['success' => false, 'error' => 'No se encontraron contactos.']);
        exit;
    }
    // Obtener el número de seguidores para cada usuario
    $seguidores = obtener_numero_seguidores($db);
    // Mapear los contadores de seguidores a los contactos
    foreach ($contac as &$contacto) {
        $contacto['total_seguidores'] = 0; // Valor predeterminado
        foreach ($seguidores as $seguidor) {
            if ($contacto['id'] == $seguidor['seguido_id']) {
                $contacto['total_seguidores'] = $seguidor['total_seguidores'];
                break;
            }
        }
    }
    return array('conscont' => $contac,);
}

function contactos_seguidores($db, $user, $seguir, $estado) {
    // Verificar si ya sigue al usuario
    $existeRelacion = contactos_relaciones($db, $user, $seguir);
    if ($estado =='following'){
        if (!$existeRelacion) {
            insertar_relaciones($db, $user, $seguir);
        }
    } else {
        eliminar_relaciones($db, $user, $seguir);
    }
    return true;
}

function obtener_tipo_contacto($db, $user, $tipo) {
    if ($tipo === 1) {
        $contac = mostrar_seguidores($db, $user);
    } elseif ($tipo === 2) {
        $contac = mostrar_seguidos($db, $user);
    }

    // Mapear los contadores de seguidores y seguidos a los contactos
    foreach ($contac as &$contacto) {
        // Llamar a la función
        $segui = obtenerSeguidoresYSeguidos($db, $contacto['id']);
        // Asigno valores retornados de la funcion
        $contacto['total_seguidores'] = $segui['total_seguidores'];
        $contacto['total_seguidos'] = $segui['total_seguidos'];
    }
    // Mapear el estado, municipio y parroquia a los contactos
    foreach ($contac as &$contacto) {
        $ubicacion = obtenerUbicacion($db, $contacto['estado'], $contacto['municipio'], $contacto['parroquia']);
        // Asigno valores retornados de la funcion
        $contacto['descest'] = $ubicacion['descest'];
        $contacto['descmupar'] = $ubicacion['descmuni'] . ' - ' . $ubicacion['descparro'];
    }
    return $usuarios = array('conscont' => $contac);
}
