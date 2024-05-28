<?php
function truncarDecimal($numero, $cantidadDecimal)
{
    // Convertir el número a string
    $numero_str = (string)$numero;
    // Encontrar la posición del punto decimal
    $pos = strpos($numero_str, '.');

    // Si hay un punto decimal y la longitud después del punto es mayor que la cantidad deseada de decimales
    if ($pos !== false && strlen($numero_str) > $pos + $cantidadDecimal) {
        // Truncar después del número deseado de decimales
        $truncado = substr($numero_str, 0, $pos + 1 + $cantidadDecimal);
    } else {
        // Si no hay punto decimal o no hay suficientes dígitos, no truncar
        $truncado = $numero_str;
    }

    // Convertir de vuelta a número
    $truncado_num = (float)$truncado;

    // Formatear el número para que tenga exactamente la cantidad de decimales deseada
    return number_format($truncado_num, $cantidadDecimal, '.', '');
}
