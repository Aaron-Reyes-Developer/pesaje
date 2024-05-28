<?php

include('../../conexion.php');


if (!isset($_POST['nombre'])) {
    $row = array('mensaje' => 'dato vacio');
    echo json_encode($row);
    die();
}


// BUSACAR EL CODIGO DEL PROVEEDOR
$queryCodigo = odbc_exec($conn, "SELECT count(*) codigo FROM fac_clientes_proveedores WHERE tipo = 'P'");
$rowCodigo = odbc_fetch_array($queryCodigo);
$codigo = $rowCodigo['codigo'] + 1;

// DATOS
$nombre = $_POST['nombre'];
$identificacion = $_POST['identificacion'];
$direccion = $_POST['direccion'] == '' ? 'NONE' : $_POST['direccion'];
$telefono = $_POST['telefono'] == '' ? 'NONE' : $_POST['telefono'];
$institucion = $_POST['institucion'] == '' ? 'NONE' : $_POST['institucion'];
$ciudad = $_POST['ciudad'] == '' ? 'NONE' : $_POST['ciudad'];

$queryGuardar = odbc_exec($conn, "INSERT INTO fac_clientes_proveedores 
(codigo, nombre, cedula_ruc, direccion, telefono, institucion, ciudad, descuento, cupo, estado, periodo, tipo) 
VALUES
($codigo , '$nombre', '$identificacion', '$direccion','$telefono','$institucion','$ciudad', 0, 0, 'S', '001', 'P')");


if ($queryGuardar) {
    $row = array('mensaje' => 'ok', 'id' => $codigo);
    echo json_encode($row);
} else {
    $row = array('mensaje' => 'nop');
    echo json_encode($row);
}

die();
