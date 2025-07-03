<?php 
function generarCodigo($longitud = 12) { 
    $caracteres = '0123456789'; 
    $numeroDeCaracteres = strlen($caracteres); 
    $codigo = ''; 

    // Generar el primer carácter, excluyendo el '0'
    $primerosCaracteres = '123456789'; 
    $codigo .= $primerosCaracteres[rand(0, strlen($primerosCaracteres) - 1)];

    // Generar el resto de los caracteres
    for ($i = 1; $i < $longitud; $i++) { 
        $indiceAleatorio = rand(0, $numeroDeCaracteres - 1); 
        $codigo .= $caracteres[$indiceAleatorio]; 
    } 
    return $codigo; 
} 
