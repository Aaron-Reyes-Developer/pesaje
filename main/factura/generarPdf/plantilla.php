<?php

session_start();



if (!isset($_SESSION['usuario'])) {
    header('Location: ../../../index.php');
    die();
}


include('../../../conexion.php');
include('../../funcionesPhp/truncarDeciamal.php');


$id_orden = $_REQUEST['id_orden'];

ob_start();

// COSNUTLA IVA
$queryDatoIva = odbc_exec($conn, "SELECT codigo, parametro FROM sys_parametros_sistema WHERE codigo = '1' ");
$rowIva = odbc_fetch_array($queryDatoIva);
$iva = $rowIva['parametro'];


// CONSULTA DATOS CABECERA
$queryDatosFactura = odbc_exec($conn, " SELECT 
cabe.documento, 
cabe.fecha_emision, 
cabe.fecha_vence,
cabe.punto_venta + '-' +  cabe.caja + '-' + cabe.n_referencia as factura,
cabe.tipo_ambiente_fac as ambiente,
cabe.tipo_emision_fac as emision,
cabe.vendedor AS id_vendedor,
cabe.clien_prov AS id_cliente_proveedor,
cabe.clave_acceso_fac as n_autorizacion,
vende.nombre AS nombre_vendedor,
prove.nombre AS nombreProveedor,
prove.cedula_ruc,
prove.direccion,
formaPago.forma_de_pago,
cabe.valor_iva
FROM fac_cabecera cabe
INNER JOIN fac_vendedores vende
ON vende.codigo = cabe.vendedor
INNER JOIN fac_clientes_proveedores prove
ON prove.codigo = cabe.clien_prov
INNER JOIN sri_tabla_formas_pago formaPago
ON formaPago.codigo = cabe.pagoLocExt
WHERE cabe.documento = '$id_orden' AND cabe.tipo = 'FV' AND prove.tipo = 'C'
");
$rowDatos = odbc_fetch_array($queryDatosFactura);


// Configurar la zona horaria (ajusta esto según tu ubicación)
date_default_timezone_set('America/Guayaquil');
$hora_actual = date('H:i:s');



//////////////////////////////// ESTRUCTURA DA LA BASE DE DATOS ////////////////////////////////
// cantidad->  cantidad ✔️
// precio -> valor ✔️
// descuento -> descuento ✔️
// valor -> otro_valor ✔️
// v+iva -> c_ultimo ✔️
// total parcial -> c_promedio ✔️
// pvp neto -> cantidad2 ✔️


// COSNULTA PRODUCTOS
$queryDatosProducto = odbc_exec($conn, "SELECT 
deta.documento,
deta.tipo,
deta.cantidad,
deta.codigoproducto,
deta.valor,
deta.otro_valor,
deta.iva,
deta.descuento,
deta.bonificacion,
deta.otro_descuento,
deta.c_promedio,
deta.c_ultimo,
produ.descripcion
FROM fac_detalle deta 
INNER JOIN inv_productos produ
ON produ.codigo = deta.codigoproducto
where documento = '$id_orden' and tipo = 'FV'");




// CONSULTA EL NOMBRE DE LA EMPRESA
$queryNombreEmpresa = odbc_exec($conn, "SELECT * FROM sys_periodo WHERE codigo = '001' ");
$rowDatosEmpresa = odbc_fetch_array($queryNombreEmpresa);

?>
<!DOCTYPE html>
<html lang="en">

<body>

    <p class="nombreEmpresa">
        <span><?php echo $rowDatosEmpresa['empresa'] ?></span>

    </p>
    <p class="eslogan"> <?php echo $rowDatosEmpresa['razon_social'] ?></p>
    <p class="eslogan">Ruc: <?php echo $rowDatosEmpresa['ruc'] ?></p>
    <p class="eslogan"> <?php echo $rowDatosEmpresa['direccion'] ?></p>

    <hr>

    <table>

        <tbody>

            <tr>
                <td><b>DOC:</b></td>
                <td><?php echo $id_orden ?></td>
            </tr>


            <tr>
                <td>Emisión:</td>
                <td><?php echo $rowDatos['fecha_emision'], ' ', $hora_actual ?></td>
            </tr>

            <tr>
                <td>Cliente:</td>
                <td><?php echo $rowDatos['nombreProveedor'] ?></td>
            </tr>

            <tr>
                <td>Ruc/Ced:</td>
                <td><?php echo $rowDatos['cedula_ruc'] ?> </td>
            </tr>

            <tr>
                <td>Dirección:</td>
                <td><?php echo $rowDatos['direccion'] ?></td>
            </tr>

            <tr>
                <td>N Autorización:</td>
                <?php

                // Insertar el <br> en la posición 25
                $posicion = 25;
                $nueva_string = substr_replace($rowDatos['n_autorizacion'], '<br>', $posicion, 0);

                ?>
                <td><?php echo $nueva_string ?></td>
            </tr>


            <tr>
                <td>Factura:</td>
                <td><?php echo $rowDatos['factura'] ?></td>
            </tr>

            <tr>
                <td>Ambiente:</td>
                <td><?php echo $rowDatos['ambiente'] == 2 ? 'PRODUCCION' : 'PRUEBA' ?></td>
            </tr>

            <tr>
                <td>Emisión:</td>
                <td><?php echo $rowDatos['emision'] == 1 ? 'NORMAL' : '' ?></td>
            </tr>

        </tbody>

    </table>

    <span style="font-size: 0.8rem; ">OBLIGADO A LLEVAR CONTABILIDAD: <?php echo $rowDatosEmpresa['obligado_llevar_conta']  ?></span> <br>
    <span style="font-size: 0.8rem; "><?php echo $rowDatosEmpresa['regimenMicroempresas']  ?></span> <br>
    <span style="font-size: 0.8rem; "><?php echo $rowDatosEmpresa['resucion_contribuyente'] != NULL ? 'Resolución Contribuyente especial: ' . $rowDatosEmpresa['resucion_contribuyente'] : ''  ?></span> <br>
    <span style="font-size: 0.8rem; "><?php echo $rowDatosEmpresa['agenteRetencion'] != NULL ? 'Agente de Retención Resolución No: ' . $rowDatosEmpresa['agenteRetencion'] : ''  ?></span> <br> <br>

    <table class="tablaProducto">

        <thead class="cabeceraProducto">
            <tr>
                <th>Can</th>
                <th>Producto</th>
                <th>Pvp</th>
                <th>Total</th>
            </tr>
        </thead>

        <tbody>

            <?php
            $subTotalTmp = 0;
            $descuentoTmp = 0;
            $ivaTmp = 0;
            $totalFinalTmp = 0;


            while ($rowDatosProducto = odbc_fetch_array($queryDatosProducto)) {

                $subTotalTmp +=  $rowDatosProducto['c_promedio']; //SUB TOTAL
                $descuentoTmp +=  $rowDatosProducto['c_promedio'] * ($rowDatosProducto['descuento'] / 100); //TOTAL DESCUENTO

            ?>
                <tr>
                    <td><?php echo truncarDecimal($rowDatosProducto['cantidad'], 2) ?></td> <!-- cantidad  -->
                    <td class="detalleProducto"><?php echo $rowDatosProducto['descripcion'] ?></td> <!-- detalle  -->
                    <td><?php echo truncarDecimal($rowDatosProducto['otro_valor'], 2) ?></td> <!-- pvp  -->
                    <td><?php echo truncarDecimal($rowDatosProducto['c_promedio'], 2) ?></td> <!-- total  -->
                </tr>
            <?php
            }

            ?>


        </tbody>

    </table>

    <h1></h1>

    <!-- TOTALES -->
    <table>

        <tbody>

            <tr>
                <td>SubTtotal:</td>
                <td>$<?php echo truncarDecimal($subTotalTmp, 2) ?></td>
            </tr>

            <tr>
                <td>Descuento:</td>
                <td>$<?php echo truncarDecimal($descuentoTmp, 2) ?></td>
            </tr>

            <tr>
                <td>IVA <?php echo truncarDecimal($iva, 0)  ?>%:</td>
                <td>$<?php echo truncarDecimal($rowDatos['valor_iva'], 2) ?></td>
            </tr>

            <tr>
                <td>Total Final:</td>
                <td><b>$<?php echo truncarDecimal(($subTotalTmp - $descuentoTmp) + $rowDatos['valor_iva'], 2) ?></b></td>
            </tr>

            <tr>
                <td>Forma de pago:</td>
                <td><?php echo $rowDatos['forma_de_pago'] ?></td>
            </tr>



        </tbody>

    </table>


    <h1></h1>

    <p style="text-align: center; margin: 0; line-height: 10px; ">GRACIAS POR SU VISITA</p>
</body>

</html>

<?php

require_once __DIR__ . '../../../../vendor/autoload.php';

// contenido completo
$contenido = ob_get_clean();

// $platilla = getPlatilla($id_lote);
$css = file_get_contents('estiloPlantilla.css');

// Configurar opciones de mPDF
$mpdfConfig = [
    'mode' => 'utf-8',
    'format' => [80, 180],
    'margin_left' => 5,  // Establecer el margen izquierdo 
    'margin_right' => 5, // Establecer el margen derecho 
    'margin_top' => 3,   // Establecer el margen superior 
    'margin_bottom' => 0 // Establecer el margen bajo 
];


$mpdf = new \Mpdf\Mpdf($mpdfConfig);
$mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($contenido, \Mpdf\HTMLParserMode::HTML_BODY);
$mpdf->Output('Factura_' . $id_orden . '.pdf', 'I');
