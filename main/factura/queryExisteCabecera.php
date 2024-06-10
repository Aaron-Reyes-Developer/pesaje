<?php

include('../../conexion.php');

$documento = $_POST['documento'];

$respuesta = ['mensaje' => ''];



// QUERY PARA SABER SI EXISTE LA CABECERA
$queryExisteCabecera = odbc_exec($conn, "SELECT documento FROM fac_cabecera WHERE tipo = 'FV' AND documento = '$documento' ");

if (odbc_num_rows($queryExisteCabecera) >= 1) {
    $respuesta['mensaje'] = 1;
} else {
    $respuesta['mensaje'] = 0;
}


echo json_encode($respuesta);


odbc_close($conn);
