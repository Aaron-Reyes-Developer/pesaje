<?php

include('../../conexion.php');


if (!isset($_POST['fecha_desde'])) {
    $row = array('nombre' => 'dato vacio');
    echo json_encode($row);
    die();
}


$fecha_desde = $_POST['fecha_desde'];
$fecha_hasta = $_POST['fecha_hasta'];

// QUERY BUSCAR PROVEEDOR
$queryProceso = odbc_exec($conn, "CALL inv_rep_documentos_auxiliar_detalle( @p_periodo = '001', @p_desde = '$fecha_desde',@p_hasta = '$fecha_hasta',@p_tipo = 'PC')");
$arrayProceso = [];

while ($row = odbc_fetch_array($queryProceso)) {
    $arrayProceso[] = $row;
}


echo json_encode($arrayProceso);


odbc_close($conn);
