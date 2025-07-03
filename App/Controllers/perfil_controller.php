<?php
// Logica de control para el perfil de usuario
require_once __DIR__ . '/../Models/perfil_model.php';
require_once __DIR__ . '/../Models/login_model.php';

function obtener_perfil($db, $email) {
    $perfil = obtener_perfil_usuario($db, $email);
    if (!$perfil) {
        return false;
    }
    // Extraer la extensión y el número
    $telefono = $perfil['telefono'];
    $perfil['extension'] = obtener_codigo_area($db, $telefono); // Asignar el id del área
    $perfil['numero'] = substr($telefono, 5);  // Resto de los caracteres

    // Obtener las categorías del perfil
    $categoria = obtener_categorias($db, $email);
    if (!empty($categoria) && isset($categoria['categoria'])) {
        $perfil['categoria'] = $categoria['categoria']; // Asignar la categoría del usuario
        $perfil['descate'] = $categoria['nombre']; // Asignar la descripción de la categoría
    } else {
        $perfil['tipocate'] = 'OTRO'; // Asignar la categoría "OTRO"
    }

    // Obtener la ubicación del perfil
    $ubicacion = obtener_ubicacion($db, $perfil['estado'], $perfil['municipio'], $perfil['parroquia']);
    if ($ubicacion) {
        $perfil['descest'] = $ubicacion[0]['estado'];
        $perfil['descmuni'] = $ubicacion[0]['municipio'];
        $perfil['descparro'] = $ubicacion[0]['parroquia'];
    }

    // Buscar horarios de apertura y cierre
    $horarios = obtener_horario($db, $perfil['hora_apertura'], $perfil['hora_cierre']);
    if ($horarios) {
        foreach ($horarios as $horario) {
            if ($horario['id'] === $perfil['hora_apertura']) {
                $perfil['hora_aper'] = $horario['hora'];
            } elseif ($horario['id'] === $perfil['hora_cierre']) {
                $perfil['hora_cie'] = $horario['hora'];
            }
        }
    } 
    return $perfil;
}

function obtener_id_user($db, $email) {
    $id_user = buscar_usuario_por_email($db, $email);
    return $id_user['id'] ?? null;
}

function busca_avatar($db, $email) {
    $perfil = obtener_perfil_usuario($db, $email);
    return $perfil['avatar'] ?? null;
}

function consultar_categorias($db, $categoria) {
    $categorias = verificar_categoria($db, $categoria);
    return $categorias;
}

function consultar_codigo_area($db, $codigo) {
    $ext_tl = verificar_codigo_area($db, $codigo);
    if (empty($ext_tl)) {
        return false;
    }
    return $ext_tl;
}

function verificar_horario($db, $hora, $turno) {
    $horario = obtener_horario_turno($db, $hora, $turno);
    if (empty($horario)) {
        return false;
    }
    return $horario;
}

function verificar_cambios($db, $user) {
    $cambios = verificar_cambios_perfil($db, $user);
    if (empty($cambios)) {
        return false;
    }
    return $cambios;
}

function actualizar_perfil_usuario($db, $user, $urlcorta, $nombre, $categoria, $descrip, 
                                   $telefono, $ext_tl, $estado, $municipio, $parroquia, $direccion,
                                   $horario_aper, $horario_cie, $web) {
    $update = actualizar_perfil($db, $user, $urlcorta, $nombre, $categoria, $descrip, 
                                $telefono, $ext_tl, $estado, $municipio, $parroquia, $direccion,
                                $horario_aper, $horario_cie, $web);
    if (!$update) {
        return false;
    }
    return true;
}

function insertar_perfil_usuario($db, $user, $urlcorta, $nombre, $categoria, $descrip, 
                                 $telefono, $ext_tl, $estado, $municipio, $parroquia, $direccion,
                                 $horario_aper, $horario_cie, $web) {
    $insert = insertar_perfil($db, $user, $urlcorta, $nombre, $categoria, $descrip, 
                              $telefono, $ext_tl, $estado, $municipio, $parroquia, $direccion,
                              $horario_aper, $horario_cie, $web);
    if (!$insert) {
        return false;
    }
    return true;
}

function carga_categoria_perfil($db) {
    $categoria = carga_categoria($db);
    echo json_encode($categoria);
}

function carga_codarea_perfil($db) {
    $codigo = carga_codigo_area($db);
    echo json_encode($codigo);
}

function carga_estados_perfil($db) {
    $estados = carga_estados($db);
    echo json_encode($estados);
}

function carga_municipios_perfil($db, $estado) {
    $municipios = carga_municipios($db, $estado);
    echo json_encode($municipios);
}

function carga_parroquias_perfil($db, $municipio) {
    $parroquias = carga_parroquias($db, $municipio);
    echo json_encode($parroquias);
}

function carga_horarios_perfil($db) {
    $apertura = carga_horarios($db, true);
    $cierre = carga_horarios($db, false);
    // Retornar ambas consultas en un solo JSON
    echo json_encode([
        'apertura' => $apertura,
        'cierre' => $cierre
    ]);
}