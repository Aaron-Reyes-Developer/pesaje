<?php

include('../../conexion.php');


if (!isset($_POST['dato'])) {
    $row = array('nombre' => 'dato vacio');
    echo json_encode($row);
    die();
}


$dato = $_POST['dato'];

// QUERY BUSCAR PROVEEDOR
$queryProveedores = odbc_exec($conn, "SELECT * FROM fac_clientes_proveedores WHERE (nombre LIKE '%$dato%' AND tipo = 'C' ) OR cedula_ruc = '$dato' AND tipo = 'C'  ");
$arrayProveedores = [];

while ($row = odbc_fetch_array($queryProveedores)) {
    $arrayProveedores[] = $row;
}


echo json_encode($arrayProveedores);


odbc_close($conn);
