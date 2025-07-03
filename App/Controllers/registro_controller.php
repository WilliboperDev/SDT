<?php
// Lógica de control para el registro de usuarios
require_once __DIR__ . '/../Models/login_model.php';
require_once __DIR__ . '/../Models/generacodigo.php';
require_once __DIR__ . '/../Models/envia_email.php';

function procesar_temp_registro($db, $correo, $clave) {
    $usuario = buscar_usuario_por_email($db, $correo); // Verificar si el usuario ya existe
    if ($usuario) {
        return ['success' => false, 'error' => 'El usuario ya existe en el sistema.'];
    }
    /* Insertar nuevo usuario en tabla temp, posterior a la 
    verificación del código se insertará en la tabla login*/
    if (!eliminar_token_temp($db, null, $correo)) {
        return ['success' => false, 'error' => 'Error al eliminar datos temp.'];
    }
    if (!insertar_usuario_temp($db, $correo, $clave)) {
        return ['success' => false, 'error' => 'Error al guardar los datos, intente de nuevo.'];
    }
    return ['success' => true];
}

function enviar_token_correo($db, $correo) {
    $codverifi = generarCodigo(5); // Generar un código de 5 caracteres
    if ($codverifi === '') {
        return ['success' => false, 'error' => 'Error al procesar codigo de validacion, intente mas tarde.']; 
    }
    // Enviar el correo con el código de verificación
    if (!enviarCorreo($correo, $codverifi)) {
        return ['success' => false, 'error' => 'Error al enviar el mensaje, intente de nuevo.']; 
    }
    // Guardar el codigo en la tabla temp para posterior verificación 
    if (!actualizar_codigo_temp($db, $codverifi, $correo)) {
        return ['success' => false, 'error' => 'Error de actualizacion de codigo.']; 
    }
    // Se busca en la tabla temp el ID del usuario
    $usuario = obtener_usuario_por_token($db, null, $correo);
    if (!$usuario) {
        return ['success' => false, 'error' => 'Error al buscar el usuario.'];
    }   
    // Retornar el ID del usuario para su posterior uso
    $id_user = $usuario['id'];
    return ['success' => true, 'user' => $id_user];
}

function procesar_validacion_registro($db, $codverif, $codtoken) {
    $usuario = obtener_usuario_por_token($db, $codtoken, null);
    if (!$usuario) {
        return ['success' => false, 'error' => 'Token no verificado'];
    }
    // Verificar si el código de verificación es correcto
    if ($codverif !== $usuario['codigo']) {
        return ['success' => false, 'error' => 'Token no válido'];
    }
    if (!insertar_usuario_login($db, $usuario['email'], $usuario['clave'])) {
        return ['success' => false, 'error' => 'Usuario no registrado'];
    }
    if (eliminar_token_temp($db, $codtoken, null)) {
        return ['success' => true, 'message' => 'Código correcto!'];
    }
}

function iniciar_hora_contador($db, $codigo) {
    $usuario = obtener_hora_contador($db, $codigo);
    if (!$usuario) {
        return ['success' => false, 'error' => 'Codigo expirado o no valido'];
    }
    // Crear un objeto DateTime con la hora actual del servidor
    $fechaHora = new DateTime();
    // Formatear la fecha y hora
    $horaInicio = $fechaHora->format('Y-m-d H:i:s');

    // Actualizar la hora de inicio si no hay fecha cargada
    if (fecha_hora_contador($db, $codigo)) {
        if (!actualiza_hora_contador($db, $codigo, $horaInicio)) {
            return ['success' => false, 'error' => 'No se pudo actualizar la hora de inicio.'];
        }
        return ['success' => true, 'horaInicio' => $horaInicio];
    } else {
        return ['success' => false, 'error' => 'con fecha'];
    }
}

function procesar_hora_contador($db, $codigo, $time) {
    $usuario = obtener_hora_contador($db, $codigo);
    if (!$usuario) {
        return ['success' => false, 'error' => 'Codigo no valido'];
    }
    $horaInicio = $usuario['hora_ini'];
    // Crear un objeto DateTime con la hora actual del servidor
    $fechaHora = new DateTime();
    // Formatear la fecha y hora
    $ahora = $fechaHora->format('Y-m-d H:i:s');

    $tiempoTranscurrido = strtotime($ahora) - strtotime($horaInicio);
    $tiempoExpirado = $tiempoTranscurrido > $time;
    if ($tiempoExpirado) {
        return ['success' => true, 'tiempoExpirado' => $tiempoExpirado];
    } else {
        return ['success' => false, 'tiempoTranscurrido' => $tiempoTranscurrido];
    }
}
