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

// CONSULTA TODOS LOS DATOS
$queryDatosFactura = odbc_exec($conn, "SELECT 
cabe.documento, 
deta.documento,
CONVERT(VARCHAR, DAY(cabe.fecha_emision)) + '/' +
CONVERT(VARCHAR, MONTH(cabe.fecha_emision)) + '/' +
CONVERT(VARCHAR, YEAR(cabe.fecha_emision)) AS fecha_emision,
deta.cantidad, 
cabe.n_referencia AS placa,
cabe.n_orden as chofer,
cabe.observacion as observacionChofer,
produc.descripcion as nombreProducto,
clienProve.codigo,
clienProve.nombre as nombreProveedor,
clienProve.cedula_ruc,
deta.existencia_comp as salida,
deta.descuento as impureza,
deta.bonificacion as humedad,
deta.otro_descuento as reten,
deta.valor as precio
FROM inv_cabecera_auxiliar cabe 
INNER JOIN  inv_detalle_auxiliar   deta
ON cabe.documento  = deta.documento
INNER JOIN inv_productos produc
ON produc.codigo = deta.codigoproducto
INNER JOIN fac_clientes_proveedores clienProve
ON clienProve.codigo = cabe.clien_prov
WHERE cabe.documento = '$id_orden' AND clienProve.tipo = 'P'");
$rowDatos = odbc_fetch_array($queryDatosFactura);


// CONSULTA EL NOMBRE DE LA EMPRESA
$queryNombreEmpresa = odbc_exec($conn, "SELECT empresa, direccion FROM sys_periodo WHERE codigo = '001' ");
$rowDatosEmpresa = odbc_fetch_array($queryNombreEmpresa);

?>
<!DOCTYPE html>
<html lang="en">

<body>

    <p class="nombreEmpresa">
        <span><?php echo $rowDatosEmpresa['empresa'] ?></span>

    </p>
    <p class="eslogan"> <?php echo $rowDatosEmpresa['direccion'] ?></p>

    <hr>

    <table>

        <tbody>
            <tr>
                <td><b>ID Orden:</b></td>
                <td><?php echo $id_orden ?></td>
            </tr>
            <tr>
                <td>Emisión:</td>
                <td><?php echo $rowDatos['fecha_emision'] ?></td>
            </tr>
            <tr>
                <td>Producto:</td>
                <td><?php echo $rowDatos['nombreProducto'] ?></td>
            </tr>
            <tr>
                <td>Proveedor:</td>
                <td><?php echo $rowDatos['cedula_ruc'] ?> <br> <?php echo $rowDatos['nombreProveedor'] ?></td>
            </tr>
            <tr>
                <td>Chofer:</td>
                <td><?php echo $rowDatos['chofer'] ?></td>
            </tr>
            <tr>
                <td>Placa:</td>
                <td><?php echo $rowDatos['placa'] ?></td>
            </tr>
            <tr>
                <td>DETALLE</td>
            </tr>

        </tbody>

    </table>

    <hr>

    <table>
        <tbody>

            <?php

            // DATOS MATEMATICA
            // $entrada = bcdiv($rowDatos['cantidad'], '1', 2);
            $entrada = $rowDatos['cantidad'];


            // $salida = bcdiv($rowDatos['salida'], '1', 2);
            $salida = $rowDatos['salida'];




            $neto = $entrada - $salida;
            // $neto = bcdiv($neto, '1', 2);


            $qqBruto = $neto * 0.022;
            // $qqBruto = bcdiv($qqBruto, '1', 2);

            if ($salida == 0) {
                $qqBruto = $entrada;
                $entrada = '0.00';
            }

            // $impureza = bcdiv($rowDatos['impureza'], '1', 2);
            $impureza = $rowDatos['impureza'];


            // $precio = bcdiv($rowDatos['precio'], '1', 2);
            $precio = $rowDatos['precio'];


            $qqNeto = $qqBruto - ($qqBruto * ($impureza / 100));
            // $qqNeto = bcdiv($qqNeto, '1', 2);


            $subTotal = $qqNeto * $precio;
            // $subTotal = bcdiv($subTotal, '1', 2);


            $reten = $subTotal * ($rowDatos['reten'] / 100);
            // $reten = bcdiv($reten, '1', 2);


            $pagar = $subTotal - $reten;
            // $pagar = bcdiv($pagar, '1', 2);


            $humedad = $rowDatos['humedad'];


            ?>
            <tr>
                <td>ENTRADA:</td>
                <td><b><?php echo truncarDecimal($entrada, 2)  ?></b></td>
            </tr>
            <tr>
                <td>SALIDA:</td>
                <td><b><?php echo truncarDecimal($salida, 2)  ?></b></td>
            </tr>
            <tr>
                <td>NETO:</td>
                <td><?php echo truncarDecimal($neto, 2)  ?></td>
            </tr>
            <tr>
                <td>QQ BRUTO:</td>
                <td><b><?php echo truncarDecimal($qqBruto, 2)  ?></b></td>
            </tr>
            <tr>
                <td>Impu:</td>
                <td><?php echo truncarDecimal($rowDatos['impureza'], 2) ?>%</td>
            </tr>
            <tr>
                <td>QQ NETO:</td>
                <td><b><?php echo truncarDecimal($qqNeto, 2) ?></b> PRECIO $ <?php echo truncarDecimal($precio, 2)  ?></td>
            </tr>
            <tr>
                <td>Sub Total:</td>
                <td><b>$ <?php echo truncarDecimal($subTotal, 2)  ?></b></td>
            </tr>
            <tr>
                <td>(-) RET 1%:</td>
                <td><b>$ <?php echo truncarDecimal($reten, 2) ?></b></td>
            </tr>
            <tr>
                <td>A PAGAR:</td>
                <td><b>$ <?php echo truncarDecimal($pagar, 2)  ?></b></td>
            </tr>
            <tr>
                <td>Humed:</td>
                <td><?php echo $humedad  ?></td>
            </tr>

        </tbody>

    </table>

    <h1></h1>
    <hr>
    <p style="text-align: center; margin: 0; line-height: 10px; ">Recibi Conforme</p>
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
