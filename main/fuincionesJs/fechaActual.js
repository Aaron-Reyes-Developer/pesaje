
function obtenerFechaFormateada(fecha) {
    const year = fecha.getFullYear();
    const month = ('0' + (fecha.getMonth() + 1)).slice(-2); // Meses empiezan desde 0
    const day = ('0' + fecha.getDate()).slice(-2);
    return `${year}-${month}-${day}`;
}