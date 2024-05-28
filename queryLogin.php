<?php

include_once('./conexion.php');

$respuesta = [];

if (!isset($_POST['usuario'])) {
    $respuesta['mensaje'] = 'Vacio';
    echo json_encode($respuesta);
    die();
}



// DATOS
$usuario = $_POST['usuario'];
$contra = $_POST['contra'];


// QUERY BUSCAR
$queryBuscar = odbc_exec($conn, "SELECT usuario FROM sys_usuarios_sistema WHERE usuario = '$usuario' AND clave = '$contra' ");
if (odbc_num_rows($queryBuscar) >= 1) {
    $respuesta['mensaje'] = 'ok';
    session_start();

    $_SESSION['usuario'] = $usuario;
} else {

    $respuesta['mensaje'] = 'nop';
}

echo json_encode($respuesta);
