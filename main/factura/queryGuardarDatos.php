<?php

include('../../conexion.php');

if (!isset($_POST['id_documento'])) {

    $respuesta = ['mensaje' => 'Id Vacio', 'errorMensaje' => true];
    echo json_encode($respuesta);
    die();
}

$respuesta = ['mensaje' => '', 'errorMensaje' => false];



// DATOS
$id_documento = $_POST['id_documento'];
$fecha_emision = $_POST['fecha_emision'];
$fecha_vence = $_POST['fecha_vence'];
$id_cliente_prov = $_POST['id_cliente_prov'];
$id_vendedor = $_POST['id_vendedor'];
$baseCero = $_POST['baseCero'];
$tarifa = $_POST['tarifa'];
$vueltas = $_POST['vueltas'];
$n_referencia = $_POST['n_referencia'];
$subTotal = $_POST['subTotal'];
$totalIva = $_POST['totalIva'];

// $respuesta['mensaje'] = $totalIva;
// echo json_encode($respuesta);
// die();

//todo: falta usuario ingresa en la bd



// COSNULTA GUARDAR DATOS CABECERA 
$queryGuardarCabecera = odbc_exec($conn, "INSERT INTO fac_cabecera 
( documento , tipo , fecha_emision ,  fecha_vence , vendedor , clien_prov , anulado , contabilizado , periodo , descuento , transporte , seguro ,n_referencia , tipo_otro_documento , impreso, periodo_cerrado, cancelado, caja, ret_fuente, ret_iva, punto_venta, tipo_clien_prov, cod_cred_tributario, cod_ret_fuente_sri, valor_base, valor_cero, valor_no_iva, pagoLocExt, tipo_ambiente_fac, tipo_emision_fac, cab_codigo_porc_iva, valor_iva, tipo_doumento_sri) 
VALUES( '$id_documento' , 'FV' , '$fecha_emision' , '$fecha_vence', '$id_vendedor' , '$id_cliente_prov', 'N' , 'N' , '001' , 0.00, 0.00, 0.00, '$n_referencia', 'FV', 'B', 'N', 'C', '004', 'N', 'N', '001', 'C', 'N', 'FAC_ELEC', '$subTotal' , '$baseCero', 0.00 , '01', 2, 1, 2, '$totalIva', 18) ");



if (!isset($_POST['modalPago'])) { //si no existe el modal pago significa que se guarda por defecto los datos del pago 

    // DATOS DEL PAGO
    $formaPago = !isset($_POST['formaPago']) ? 1 : $_POST['formaPago'];
    $valor = $_POST['totalPagar'];
    $banco = !isset($_POST['banco']) ? '' : $_POST['banco'];
    $cuenta = !isset($_POST['cuenta']) ? '(NULL)' : $_POST['cuenta'];
    $cheque = !isset($_POST['cheque']) ? '(NULL)' : $_POST['cheque'];
    $entidad = $_POST['entidad'];
    $observacion = !isset($_POST['observacion']) ? '' : $_POST['observacion'];


    // QUERY PARA CONSULTAR EL ULTIMO RECIBO
    $queryRecibo = odbc_exec($conn, "SELECT MAX(recibo) ultimoRecibo FROM fac_pagos  WHERE tipo = 'FV' ");
    $rowRecibo = odbc_fetch_array($queryRecibo);
    $recibo = $rowRecibo['ultimoRecibo'] + 1;


    // GUARDAMOS LOS DATOS DEL PAGO
    $queryGuardarPago = odbc_exec($conn, "INSERT INTO fac_pagos ( documento , tipo , f_emision , f_vence , periodo , valor , forma_pago , entidad , referencia , codigobanco , n_cuenta , n_cheque , recibo , n_pago , contabilidad , observacion , usuario_ingresa , n_deposito , f_deposito )
    VALUES
    ('$id_documento', 'FV', '$fecha_emision', '$fecha_vence', '001', '$valor' , '$formaPago' , '$entidad', '$n_referencia', '$banco', '$cuenta' , '$cheque' , '$recibo' ,NULL,NULL,NULL, NULL ,NULL,NULL)");

    //
}



if ($queryGuardarCabecera) {

    for ($i = 0; $i < $vueltas; $i++) {

        // DATOS
        $id_producto = $_POST['id_' . $i];
        $cantidad = $_POST['cantidad_' . $i];
        $precio = $_POST['precio_' . $i];
        $descuento = $_POST['descuento_' . $i];
        $valor = $_POST['valor_' . $i];
        $valorIva = $_POST['valorIva_' . $i];
        $totPrecio = $_POST['totPrecio_' . $i];
        $pvpNeto = $_POST['pvpNeto_' . $i];


        //////////////////////////////// ESTRUCTURA DA LA BASE DE DATOS ////////////////////////////////
        // cantidad->  cantidad ✔️
        // precio -> valor ✔️
        // descuento -> descuento ✔️
        // valor -> otro_valor ✔️
        // v+iva -> c_ultimo ✔️
        // total parcial -> c_promedio ✔️
        // pvp neto -> cantidad2 ✔️



        $queryDetalleAuxiliar = odbc_exec($conn, "INSERT  INTO fac_detalle 
        (documento, tipo, periodo, cantidad, codigoproducto,  valor, codigobodega, iva , descuento, otro_valor, c_ultimo, c_promedio, cantidad2, bonificacion, otro_descuento, ptransporte, pseguro, tipo_producto,  codigo_porc_iva)
        VALUES
        ( '$id_documento' , 'FV' , '001' , '$cantidad' , '$id_producto' , '$precio' , '1', 0.00,  '$descuento' ,  '$valor' ,  '$valorIva', '$totPrecio', '$pvpNeto' ,0.00, 0.00, 0.00, 0.00, 'IN' ,  0.00 )");
    }



    if ($queryDetalleAuxiliar) {
        $respuesta['mensaje'] = 'ok';
    }

    //
} else {
    $respuesta['mensaje'] = 'nop';
    $respuesta['errorMensaje'] = true;
}


echo json_encode($respuesta);
