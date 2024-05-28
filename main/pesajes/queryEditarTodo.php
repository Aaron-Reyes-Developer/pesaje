<?php

include('../../conexion.php');

$respuesta = ['mensaje' => '', 'errorMensaje' => false];

if (!isset($_POST['id_editar']) || !isset($_POST['id_producto'])) {
    $respuesta['mensaje'] = 'Datos Vacios';
    $respuesta['errorMensaje'] = true;
    echo json_encode($respuesta);
    die();
}


// DATOS
$id_editar = $_POST['id_editar'];
$fecha_emicion = $_POST['fecha_emicion'];
$id_proveedorInput = $_POST['id_proveedorInput'];
$detalle = $_POST['detalle'] == '' ? 'NONE' : $_POST['detalle'];
$placa = $_POST['placa'] == '' ? 'NONE' : $_POST['placa'];
$comentario = $_POST['comentario'] == '' ? 'NONE' : $_POST['comentario'];
$entrada = ($_POST['entrada'] == 0 || $_POST['entrada'] == '') ? $_POST['cantidad'] : $_POST['entrada']; //si la entrada viene vacia se escoge el input cantidad
$salida = ($_POST['salida']  == 0 || $_POST['salida'] == '') ? 0 : $_POST['salida'];
$retenInput = $_POST['retenInput'];
$id_producto = $_POST['id_producto'];
$precio = $_POST['precio'];
$humedad = $_POST['humedad'];
$inpureza = $_POST['inpureza'];


try {

    $queryEditarCabecera = odbc_exec($conn, "UPDATE inv_cabecera_auxiliar SET  
    fecha_emision = '$fecha_emicion', 
    fecha_vence = '$fecha_emicion', 
    clien_prov = '$id_proveedorInput', 
    n_orden = '$detalle', 
    n_referencia = '$placa', 
    observacion =  '$comentario'
    WHERE documento = '$id_editar' ");

    if (!$queryEditarCabecera) {
        $error = odbc_errormsg($conn); // Error al ejecutar el query
        throw new Exception("Error en el query: $error");
    }


    // EDITAR LOS DATOS EN inv_detalle_axiliar
    $queryGuardarProductos = odbc_exec($conn, "UPDATE inv_detalle_auxiliar SET  
    cantidad = '$entrada' , 
    codigoproducto = '$id_producto', 
    valor = '$precio', 
    otro_valor = '$precio', 
    descuento = '$inpureza',
    bonificacion = '$humedad', 
    otro_descuento = '$retenInput', 
    existencia_comp = '$salida'
    WHERE documento = '$id_editar'  ");


    if ($queryGuardarProductos) {
        $respuesta['mensaje'] = 'ok';
        $respuesta['errorMensaje'] = false;
    } else {
        $respuesta['mensaje'] = 'nop';
        $respuesta['errorMensaje'] = true;
    }


    //
} catch (Exception $e) {
    $respuesta['mensaje'] = 'Algo salió mal: ' . $e->getMessage();
    $respuesta['errorMensaje'] = true;
}

echo json_encode($respuesta);
