<?php

include('../../conexion.php');

if (!isset($_POST['documento'])) {

    $respuesta = ['mensaje' => 'Id Vacio', 'errorMensaje' => true];
    echo json_encode($respuesta);
    die();
}

$respuesta = ['mensaje' => '', 'errorMensaje' => false];



// DATOS
$id_documento = $_POST['documento'];
$vueltasModalPago = $_POST['vueltasModalPago'];
$m_referencia = $_POST['n_referencia'];
$entidad = $_POST['entidad'];
$observacion = $_POST['observacion'];

// guardar los datos del pago
for ($i = 0; $i <= $vueltasModalPago; $i++) {

    $fecha_emision_modal = $_POST['fecha_emision_modal_' . $i];
    $fecha_vencimiento_modal = $_POST['fecha_vencimiento_modal_' . $i];
    $formaPago = $_POST['formaPago_' . $i];
    $valor = $_POST['valor_' . $i];
    $banco = $_POST['banco_' . $i] == '' ? '' : $_POST['banco_' . $i];
    $cuenta = $_POST['cuenta_' . $i] == '' ? '(NULL)' : $_POST['cuenta_' . $i];
    $cheque = $_POST['cheque_' . $i] == '' ? '(NULL)' : $_POST['cheque_' . $i];


    // QUERY PARA CONSULTAR EL ULTIMO RECIBO
    $queryRecibo = odbc_exec($conn, "SELECT MAX(recibo) ultimoRecibo FROM fac_pagos  WHERE tipo = 'FV' ");
    $rowRecibo = odbc_fetch_array($queryRecibo);
    $recibo = $rowRecibo['ultimoRecibo'] + 1;


    // COSNULTA GUARDAR DATOS PAGOS
    $queryGuardarPago = odbc_exec($conn, "INSERT INTO fac_pagos ( documento , tipo , f_emision , f_vence , periodo , valor , forma_pago , entidad , referencia , codigobanco , n_cuenta , n_cheque , recibo , n_pago , contabilidad , observacion , usuario_ingresa , n_deposito , f_deposito )
    VALUES
    ('$id_documento', 'FV', '$fecha_emision_modal', '$fecha_vencimiento_modal', '001', '$valor' , '$formaPago' , '$entidad', '$m_referencia', '$banco', '$cuenta' , '$cheque' , '$recibo' ,NULL, NULL, '$observacion', NULL ,NULL,NULL)");

    //
}


if ($queryGuardarPago) {


    $respuesta['mensaje'] = 'ok';


    //
} else {
    $respuesta['mensaje'] = 'nop';
    $respuesta['errorMensaje'] = true;
}


echo json_encode($respuesta);
