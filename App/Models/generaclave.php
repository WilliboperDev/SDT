<?php 
function generarContrasenaAleatoria($longitud = 12) { 
    //$caracteres = 'abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789@_#%*.';
    $caracteres = 'abcdefghijklmnopqrstuvwxyz0123456789@_#%*.';  
    $numeroDeCaracteres = strlen($caracteres); 
    $contrasena = ''; 
    for ($i = 0; $i < $longitud; $i++) { 
        $indiceAleatorio = rand(0, $numeroDeCaracteres - 1); 
        $contrasena .= $caracteres[$indiceAleatorio]; 
    } 
    return $contrasena; 
} 
// Ejemplo de uso: 
//echo generarContrasenaAleatoria(8); // Genera una contraseña de 16 caracteres 
