<?php

include('../../conexion.php');

$respuesta = ['mensaje' => '', 'errorMensaje' => false];

if (!isset($_POST['documento']) || !isset($_POST['id_producto'])) {
    $respuesta['mensaje'] = 'Datos Vacios';
    $respuesta['errorMensaje'] = true;
    echo json_encode($respuesta);
    die();
}


// DATOS
$tipo = $_POST['tipo'];
$documento = $_POST['documento'];
$fecha_emicion = $_POST['fecha_emicion'];
$id_proveedorInput = $_POST['id_proveedorInput'];
$detalle = $_POST['detalle'] == '' ? 'NONE' : $_POST['detalle'];;
$placa = $_POST['placa'] == '' ? 'NONE' : $_POST['placa'];;
$comentario = $_POST['comentario'] == '' ? 'NONE' : $_POST['comentario'];;
$total_tr = $_POST['total_tr'];
$entrada = $_POST['entrada'] == '' ? $_POST['cantidad'] : $_POST['entrada']; //si la entrada viene vacia se escoge el input cantidad
$salida = $_POST['salida']  == '' ? 0 : $_POST['salida'];
$retenInput = $_POST['retenInput'];
$id_producto = $_POST['id_producto'];
// $cantidad = $_POST['cantidad']; 
$precio = $_POST['precio'];
$humedad = $_POST['humedad'];
$inpureza = $_POST['inpureza'];



try {

    $queryGuardarCabecera = odbc_exec($conn, "INSERT INTO inv_cabecera_auxiliar 
    (documento, tipo, fecha_emision, fecha_vence, clien_prov, n_referencia, n_orden, anulado, contabilizado, periodo, descuento, transporte, seguro, impreso, periodo_cerrado, observacion, caja, tipo_clien_prov, nimpreso) 
    VALUES 
    ('$documento', '$tipo', '$fecha_emicion', '$fecha_emicion', '$id_proveedorInput', '$placa', '$detalle', 'N', 'S', '001', '0', '0', '0', 'N', 'N', '$comentario', '001', 'P', '0')");

    if (!$queryGuardarCabecera) {
        throw new Exception("Error al ejecutar la consulta: " . odbc_error($conn) . " - " . odbc_errormsg($conn));
    }


    // INGRESAR DATOS EN inv_detalle_axiliar
    $queryGuardarProductos = odbc_exec($conn, "INSERT INTO inv_detalle_auxiliar 
    (documento, tipo, periodo, cantidad, codigoproducto, valor, otro_valor, codigobodega, iva, descuento, bonificacion, otro_descuento, lista_precio, otro_documento, existencia_comp, tipo_producto, pvp_auxiliar, transferido, pasa, fecha_registro, fecha_modificacion, fecha_creacion, cajas, detalle_referencia)
    VALUES 
    ('$documento', 'PC','001', '$entrada', '$id_producto', '$precio', '$precio', '1', '0', '$inpureza', '$humedad', '$retenInput', NULL, NULL, '$salida', 'IN', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL) ");


    if ($queryGuardarProductos) {
        $respuesta['mensaje'] = 'ok';
        $respuesta['errorMensaje'] = false;
    }
} catch (Exception $e) {
    $respuesta['mensaje'] = 'Algo salió mal: ' . $e->getMessage();
    $respuesta['errorMensaje'] = true;
}

echo json_encode($respuesta);
