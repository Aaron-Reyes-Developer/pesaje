<?php

include('../../conexion.php');

$id_producto = $_POST['id_producto'];

// QUERY BUSCAR PROVEEDOR
$queryProducto = odbc_exec($conn, "SELECT 
product.codigo, 
product.costoultimo,
product.pvp1,
product.pvp2,
product.pvp3,
product.pvp4,
product.pvp5
FROM inv_productos product 
WHERE product.codigo = '$id_producto' ");

$arrayProducto = [];
while ($row = odbc_fetch_array($queryProducto)) {
    $arrayProducto[] = $row;
}




echo json_encode($arrayProducto);


odbc_close($conn);
