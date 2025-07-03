<?php
// Logica de acceso a datos para el perfil de usuario

function obtener_perfil_usuario($db, $email) {
    return $db->get('perfil', [
        '[>]hora_comer(hora_apertura)' => ['horario_ap' => 'hora'],
        '[>]hora_comer(hora_cierre)' => ['horario_ci' => 'hora']
    ], [
        'perfil.avatar',
        'perfil.nombre',
        'perfil.descripcion',
        'perfil.telefono',
        'perfil.estado',
        'perfil.municipio',
        'perfil.parroquia',
        'perfil.direccion',
        'hora_apertura.id(hora_apertura)',
        'hora_cierre.id(hora_cierre)',
        'perfil.web',
        'perfil.categoria' 
    ], [
        'perfil.email' => $email
    ]);
}

function obtener_codigo_area($db, $telefono) {
    return $db->get('cod_area', 'id', ['codigo' => substr($telefono, 0, 4)]);
}

function obtener_categorias($db, $user) {
    return $db->get('perfil', [
        '[>]categorias' => ['categoria' => 'nombre']
    ], [
        'categorias.codigo(categoria)',
        'categorias.nombre(nombre)'
    ], [
        'perfil.email' => $user
    ]);
}

function obtener_ubicacion($db, $estado, $municipio, $parroquia) {
    return $db->query(
        "SELECT 
            e.estado AS estado, 
            m.municipio AS municipio, 
            p.parroquia AS parroquia
        FROM estados e
        JOIN municipios m ON m.id_estado = e.id_estado
        JOIN parroquias p ON p.id_municipio = m.id_municipio
        WHERE 
            e.id_estado = :estado AND 
            m.id_municipio = :municipio AND 
            p.id_parroquia = :parroquia",
        [':estado' => $estado, ':municipio' => $municipio, ':parroquia' => $parroquia]
    )->fetchAll();
}

function obtener_horario($db, $apertura, $cierre) {
    return $db->select('hora_comer', [
        'id',
        'hora'
    ], [
        'id' => [$apertura, $cierre]
    ]);
}

function verificar_categoria($db, $categoria) {
    return $db->get('categorias', 'nombre', ['codigo' => $categoria]);
}

function verificar_codigo_area($db, $codigo) {
    return $db->get('cod_area', 'codigo', ['id' => $codigo]);
}

function obtener_horario_turno($db, $hora, $turno) {
    return $db->get('hora_comer','hora', ['id' => $hora, 'turno' => $turno]);
}

function verificar_cambios_perfil($db, $user) {
    return $db->get('perfil', [
        'avatar',
        'nombre',
        'categoria',
        'descripcion',
        'telefono',
        'estado',
        'municipio',
        'parroquia',
        'direccion',
        'horario_ap',
        'horario_ci',
        'web'
    ], [
        'email' => $user
    ]);
}

function actualizar_perfil($db, $user, $urlcorta, $nombre, $categoria, $descrip, 
                           $telefono, $ext_tl, $estado, $municipio, $parroquia, $direccion,
                           $horario_aper, $horario_cie, $web) {
    $usuario = $db->update('perfil', [
        'avatar'=> $urlcorta,
        'nombre' => $nombre,
        'categoria' => $categoria,
        'descripcion' => $descrip,
        'telefono' => $ext_tl . '-' . $telefono,
        'estado' => $estado,
        'municipio' => $municipio,
        'parroquia' => $parroquia,
        'direccion' => $direccion,
        'horario_ap' => $horario_aper,
        'horario_ci' => $horario_cie,
        'web' => $web
    ], [
        'email' => $user
    ]);
    return $usuario ? true : false;
}

function insertar_perfil($db, $user, $urlcorta, $nombre, $categoria, $descrip, 
                         $telefono, $ext_tl, $estado, $municipio, $parroquia, $direccion,
                         $horario_aper, $horario_cie, $web) {
    $usuario = $db->insert('perfil', [
        'email' => $user,
        'nombre' => $nombre,
        'categoria' => $categoria,
        'descripcion' => $descrip,
        'telefono' => $ext_tl . '-' . $telefono,
        'estado' => $estado,
        'municipio' => $municipio,
        'parroquia' => $parroquia,
        'direccion' => $direccion,
        'horario_ap' => $horario_aper,
        'horario_ci' => $horario_cie,
        'web' => $web,
        'avatar' => $urlcorta
    ]);
    return $usuario ? true : false;
}

function carga_categoria($db) {
    $categorias = $db->select('categorias', [
        'codigo',
        'nombre',
        'sector'
    ], [
        'ORDER' => [
            'es_otro' => 'ASC', // Ordenar por es_otro (0 primero, 1 después)
            'sector',           // Luego por sector
            'nombre'            // Finalmente por nombre
        ]
    ]);
    return $categorias ? $categorias : [];
}

function carga_codigo_area($db) {
    $codigos = $db->select('cod_area', ['id','codigo'], ['ORDER' => ['id']]);
    return $codigos ? $codigos : [];
}   

function carga_estados($db) {
    $estados = $db->select('estados', [
        'id_estado','estado'], [
        'ORDER' => ['id_estado']
    ]);
    return $estados ? $estados : [];
}

function carga_municipios($db, $estado) {
    $municipios = $db->select('municipios', ['id_municipio', 'municipio'], [
        'id_estado' => $estado,
        'ORDER' => ['id_municipio']
    ]);
    return $municipios ? $municipios : [];
}

function carga_parroquias($db, $municipio) {
    $parroquias = $db->select('parroquias', ['id_parroquia', 'parroquia'], [
        'id_municipio' => $municipio,
        'ORDER' => ['parroquia']
    ]);
    return $parroquias ? $parroquias : [];
}

function carga_horarios($db, $turno) {
    $horario = $db->select('hora_comer', ['id', 'hora'], ['turno' => $turno]);
    return $horario ? $horario : [];
}