<?php

include('../../conexion.php');


if (isset($_POST['id_producto'])) {


    $id_producto = $_POST['id_producto'];

    // QUERY BUSCAR PROVEEDOR
    $queryProducto = odbc_exec($conn, "SELECT 
    product.codigo, 
    product.descripcion,
    product.costoultimo,
    product.codigomedida,
    medi.descripcion as medida
    FROM inv_productos product 
    INNER JOIN inv_medidas medi
    ON medi.codigo = product.codigomedida
    WHERE product.codigo = '$id_producto' ");

    $arrayProducto = [];
    while ($row = odbc_fetch_array($queryProducto)) {
        $arrayProducto[] = $row;
    }


    //
} else {

    $detalle = $_POST['detalle'];

    // QUERY BUSCAR PROVEEDOR
    $queryProducto = odbc_exec($conn, "SELECT   TOP 37
    product.codigo, 
    product.descripcion,
    product.costoultimo,
    product.codigomedida,
    medi.descripcion as medida
    FROM inv_productos product 
    INNER JOIN inv_medidas medi 
    ON medi.codigo = product.codigomedida
    WHERE product.descripcion LIKE '%$detalle%' 
    ORDER BY product.codigo");

    $arrayProducto = [];
    while ($row = odbc_fetch_array($queryProducto)) {
        $arrayProducto[] = $row;
    }
}


echo json_encode($arrayProducto);


odbc_close($conn);
