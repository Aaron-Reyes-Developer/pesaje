<?php

include('../../conexion.php');


if (!isset($_POST['nombres'])) {
    $row = array('mensaje' => 'dato vacio');
    echo json_encode($row);
    die();
}

// DATOS
$tipo = $_POST['tipo'];
$identificacion = $_POST['identificacion'];
$nombre = $_POST['nombres'];
$direccion = $_POST['direccion'] == '' ? 'NONE' : $_POST['direccion'];
$telefono = $_POST['telefono'] == '' ? 'NONE' : $_POST['telefono'];
$institucion = $_POST['institucion'] == '' ? 'NONE' : $_POST['institucion'];
$ciudad = $_POST['ciudad'] == '' ? 'NONE' : $_POST['ciudad'];


// BUSCAR SI YA EXISTE EL PROVEEDOR - CLIENTE
$queryBuscarPorveClien = odbc_exec($conn, "SELECT codigo FROM fac_clientes_proveedores WHERE cedula_ruc = '$identificacion' AND tipo = '$tipo' ");
if (odbc_num_rows($queryBuscarPorveClien) >= 1) {
    $row = array('mensaje' => 'existe');
    echo json_encode($row);
    die();
}


// BUSACAR EL CODIGO DEL PROVEEDOR / CLIENTE
$queryCodigo = odbc_exec($conn, "SELECT count(*) codigo FROM fac_clientes_proveedores WHERE tipo = '$tipo' ");
$rowCodigo = odbc_fetch_array($queryCodigo);
$codigo = $rowCodigo['codigo'] + 1;



// GUARDAR EL DATO
$queryGuardar = odbc_exec($conn, "INSERT INTO fac_clientes_proveedores 
(codigo, nombre, cedula_ruc, direccion, telefono, institucion, ciudad, descuento, cupo, estado, periodo, tipo) 
VALUES
($codigo , '$nombre', '$identificacion', '$direccion','$telefono','$institucion','$ciudad', 0, 0, 'S', '001', '$tipo')");


if ($queryGuardar) {
    $row = array('mensaje' => 'ok');
    echo json_encode($row);
} else {
    $row = array('mensaje' => 'nop');
    echo json_encode($row);
}

die();
