function truncarDecimal(numero, cantidadDecimal) {
    // Convertir el número a string
    let numeroStr = numero.toString();
    // Encontrar la posición del punto decimal
    let pos = numeroStr.indexOf('.');

    // Si hay un punto decimal y la longitud después del punto es mayor que la cantidad deseada de decimales
    if (pos !== -1 && numeroStr.length > pos + cantidadDecimal + 1) {
        // Truncar después del número deseado de decimales
        numeroStr = numeroStr.substring(0, pos + 1 + cantidadDecimal);
    }

    // Convertir de vuelta a número
    let truncado = parseFloat(numeroStr);

    // Formatear el número para que tenga exactamente dos decimales
    return truncado.toFixed(cantidadDecimal);
}