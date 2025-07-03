<?php
// Logica de acceso a datos para los contactos
function verificar_usuario_perfil($db, $user) {
    // Busca el ID del usuario
    return $db->get('perfil', 'id', ['email' => $user]);
}
function obtener_contactos_no_seguidos($db, $user) {
    // Busca el ID del usuario
    $useid = verificar_usuario_perfil($db, $user);
    if (!$useid) {
        return [];
    }
    return $db->query(
        "SELECT 
            UPPER(p.nombre) AS nombre, 
            p.categoria, 
            p.email, 
            p.telefono, 
            p.avatar, 
            p.id
        FROM perfil p
        LEFT JOIN relaciones r ON p.id = r.seguido_id AND r.seguidor_id = :emause
        WHERE p.email != :email AND r.seguidor_id IS NULL", 
        [':email' => $user, ':emause' => $useid]
    )->fetchAll();
}

function obtener_numero_seguidores($db) {
    return $db->query(
        "SELECT seguido_id, COUNT(*) AS total_seguidores
        FROM relaciones
        WHERE seguido_id IN (SELECT id FROM perfil)
        GROUP BY seguido_id"
    )->fetchAll();
}

function contactos_relaciones($db, $seguidorId, $seguidoId) {
    // Busca el ID del usuario
    $seguidor = verificar_usuario_perfil($db, $seguidorId);
    $seguido = verificar_usuario_perfil($db, $seguidoId);
    if (!$seguidor && !$seguido) {
        return [];
    }
    return $db->get('relaciones', '*', [
        'seguidor_id' => $seguidor,
        'seguido_id' => $seguido
    ]);
}

function insertar_relaciones($db, $seguidorId, $seguidoId) {
    // Busca el ID del usuario
    $seguidor = verificar_usuario_perfil($db, $seguidorId);
    $seguido = verificar_usuario_perfil($db, $seguidoId);

    $rela = $db->insert('relaciones', [
        'seguidor_id' => $seguidor,
        'seguido_id' => $seguido
    ]);
    return $rela ? true : false;
}

function eliminar_relaciones($db, $seguidorId, $seguidoId) {
    // Busca el ID del usuario
    $seguidor = verificar_usuario_perfil($db, $seguidorId);
    $seguido = verificar_usuario_perfil($db, $seguidoId);

    $rela = $db->delete('relaciones', [
        'seguidor_id' => $seguidor,
        'seguido_id' => $seguido
    ]);
    return $rela ? true : false;
}

function mostrar_seguidores($db, $user) {
    // Busca el ID del usuario
    $useid = verificar_usuario_perfil($db, $user);
    if (!$useid) {
        return [];
    }
    return $db->query(
        "SELECT 
            p.id,
            p.nombre, 
            p.categoria, 
            p.descripcion,
            p.email, 
            p.telefono, 
            p.estado,
            p.municipio,
            p.parroquia,
            p.direccion,
            CONCAT(p.horario_ap, '- ', p.horario_ci) AS horario,
            p.web,
            p.avatar
        FROM perfil p
        LEFT JOIN relaciones r ON p.id = r.seguidor_id
        WHERE r.seguido_id = :emause", 
        [':emause' => $useid]
    )->fetchAll();
}

function mostrar_seguidos($db, $user) {
    // Busca el ID del usuario
    $useid = verificar_usuario_perfil($db, $user);
    if (!$useid) {
        return [];
    }
    return $db->query(
        "SELECT 
            p.id,
            p.nombre, 
            p.categoria, 
            p.descripcion,
            p.email, 
            p.telefono, 
            p.estado,
            p.municipio,
            p.parroquia,
            p.direccion,
            CONCAT(p.horario_ap, '- ', p.horario_ci) AS horario,
            p.web,
            p.avatar
        FROM perfil p
        LEFT JOIN relaciones r ON p.id = r.seguido_id
        WHERE r.seguidor_id = :emause", 
        [':emause' => $useid]
    )->fetchAll();
}

// Obtener el numero de seguidores y seguidos por cada usuario
function obtenerSeguidoresYSeguidos($db, $id) {
    // Consulta para contar los seguidores
    $sqlSeguidores = $db->query(
        "SELECT 
            COUNT(*) AS total_seguidores 
        FROM relaciones 
        WHERE seguido_id = :usuarioId",
        [':usuarioId' => $id]
    )->fetch();

    // Consulta para contar los seguidos
    $sqlSeguidos = $db->query(
        "SELECT 
            COUNT(*) AS total_seguidos
        FROM relaciones 
        WHERE seguidor_id = :usuarioId",
        [':usuarioId' => $id]
    )->fetch();

    return [
        'total_seguidores' => $sqlSeguidores['total_seguidores'] ?? 0,
        'total_seguidos' => $sqlSeguidos['total_seguidos'] ?? 0
    ];
}

// Obtener la descripcion de estado, municipio y parroquia del usuario
function obtenerUbicacion($db, $esta, $muni, $parro) {
    // Consulta para obtener la ubicación
    $descrip = $db->query(
        "SELECT 
            e.estado AS descest, 
            m.municipio AS descmuni, 
            p.parroquia AS descparro
        FROM estados e
        JOIN municipios m ON m.id_estado = e.id_estado
        JOIN parroquias p ON p.id_municipio = m.id_municipio
        WHERE 
            e.id_estado = :estado AND 
            m.id_municipio = :municipio AND 
            p.id_parroquia = :parroquia",
        [':estado' => $esta, ':municipio' => $muni, ':parroquia' => $parro]
    )->fetch();

    return [
        'descest' => $descrip['descest'] ?? '',
        'descmuni' => $descrip['descmuni'] ?? '',
        'descparro' => $descrip['descparro'] ?? '',
    ];
}
