<?php

session_start();



if (!isset($_SESSION['usuario'])) {
    header('Location: ../../../index.php');
    die();
}


include('../../../conexion.php');
include('../../funcionesPhp/truncarDeciamal.php');



ob_start();

// Setar la fecha
$desde = $_REQUEST['desde'];
// Separar la fecha en componentes
$componentes = explode("-", $desde);
// Reordenar y juntar con /
$desde_formateado = $componentes[2] . "/" . $componentes[1] . "/" . $componentes[0];


// Setar la fecha
$hasta = $_REQUEST['hasta'];
// Separar la fecha en componentes
$componentes = explode("-", $hasta);
// Reordenar y juntar con /
$hasta_formateado = $componentes[2] . "/" . $componentes[1] . "/" . $componentes[0];



/////////////////////////////////////////     CONSULTA //////////////////////////////////
$queryDatosTabla = odbc_exec($conn, "CALL inv_rep_documentos_auxiliar_detalle( @p_periodo = '001', @p_desde = '$desde',@p_hasta = '$hasta',@p_tipo = 'PC')");




?>
<!DOCTYPE html>
<html lang="en">

<head>

</head>


<body>

    <p class="nombreEmpresa">
        <span>BRAIN</span>
        SOFT
    </p>
    <p class="eslogan">Facturación Electrónica y Sistemas</p>

    <hr>

    <p style="margin: 0; padding: 0; text-align: center;"><i>Informe Facturación</i></p>

    <hr>

    <p style="margin: 0; padding: 0; "><b>Desde</b> : <?php echo $desde_formateado ?></p>
    <p style="margin: 0; padding: 0; "><b>Hasta</b> : <?php echo $hasta_formateado ?></p>


    <table>

        <thead>

            <tr>
                <th style="text-align: center;  width: 30px;">Doc</th>
                <th style="text-align: center; width: 30px;">Cod Pro.</th>
                <th style="text-align: center;">Client/Prov</th>
                <th style="text-align: center;">Fecha</th>
                <th style="text-align: center;">QQ Bruto</th>
                <th style="text-align: center;">Impureza</th>
                <th style="text-align: center;">QQ Neto</th>
                <th style="text-align: center;">Precio</th>
                <th style="text-align: center;">Sub Total</th>
                <th style="text-align: center;">Retención</th>
                <th style="text-align: center;">Total</th>
                <th style="text-align: center;">Humedad</th>
            </tr>

        </thead>

        <tbody>

            <?php
            $contador = 0;

            $TqqBruto = 0;
            $Timpureza = 0;
            $TqqNeto = 0;
            $TsubTotal = 0;
            $Treten = 0;
            $Ttotal = 0;
            $Thumedad = 0;

            while ($row = odbc_fetch_array($queryDatosTabla)) {

                $contador++;


                // calculos
                $impuresa = $row['cantidad'] * ($row['descuento'] / 100);
                $qqNeto = $row['cantidad'] - ($row['cantidad'] * (($row['descuento'] / 100)));
                $precio = $row['valor'];
                $subTotal = (($row['cantidad'] - ($row['cantidad'] * ($row['descuento'] / 100))) * truncarDecimal($row['valor'], 2));
                $retencion = $subTotal * ($row['otro_descuento'] / 100);
                $total = $subTotal - ($subTotal * ($row['otro_descuento'] / 100));
                $humedad = $row['bonificacion'];



                // SUMA DE TOTALES
                $TqqBruto += $row['cantidad'];
                $Timpureza += $impuresa;
                $TqqNeto += $qqNeto;
                $TsubTotal += $subTotal;
                $Treten += $retencion;
                $Ttotal += $total;
                $Thumedad += $humedad;
            ?>

                <!-- MOSTRAR LOS DATOS -->
                <tr>
                    <td><?php echo $row['documento'] ?></td><!-- DOC-->
                    <td><?php echo $row['codigo'] ?></td><!-- COD PRO-->
                    <td><?php echo $row['detalle_mov'] ?></td><!-- CLIENTE / PRO-->
                    <td><?php echo $row['fecha_emision'] ?></td><!-- FECHA-->
                    <td><?php echo $row['cantidad'] ?></td><!-- QQ BRUTO -->
                    <td><?php echo truncarDecimal($impuresa, 2) ?></td><!-- IMPUREZA-->
                    <td><?php echo truncarDecimal($qqNeto, 4) ?></td><!-- QQ NETO-->
                    <td><?php echo truncarDecimal($precio, 2) ?></td><!-- PRECIO-->
                    <td><?php echo truncarDecimal($subTotal, 4) ?></td><!-- SUB TOTAL-->
                    <td><?php echo truncarDecimal($retencion, 4) ?></td><!-- RETENCION-->
                    <td><?php echo truncarDecimal($total, 2) ?></td><!-- TOTAL-->
                    <td><?php echo truncarDecimal($humedad, 2) ?></td><!-- HUMEDAD-->
                </tr>


            <?php
            }


            ?>

            <tr>
                <td style="font-weight: bold;">Totales (<?php echo $contador ?>)</td><!-- DOC-->
                <td></td><!-- COD PRO-->
                <td></td><!-- CLIENTE / PRO-->
                <td></td><!-- FECHA-->
                <td style="font-weight: bold;"><?php echo truncarDecimal($TqqBruto, 2) ?></td><!-- QQ BRUTO -->
                <td style="font-weight: bold;"><?php echo truncarDecimal($Timpureza, 2) ?></td><!-- IMPUREZA-->
                <td style="font-weight: bold;"><?php echo truncarDecimal($TqqNeto, 2) ?></td><!-- QQ NETO-->
                <td></td><!-- PRECIO-->
                <td style="font-weight: bold;"><?php echo truncarDecimal($TsubTotal, 2) ?></td><!-- SUB TOTAL-->
                <td style="font-weight: bold;"><?php echo truncarDecimal($Treten, 2) ?></td><!-- RETENCION-->
                <td style="font-weight: bold;"><?php echo truncarDecimal($Ttotal, 2) ?></td><!-- TOTAL-->
                <td style="font-weight: bold;"><?php echo truncarDecimal($Thumedad, 2) ?></td><!-- HUMEDAD-->
            </tr>

        </tbody>
    </table>


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
    'format' => 'A4',
    'margin_left' => 5,  // Establecer el margen izquierdo 
    'margin_right' => 5, // Establecer el margen derecho 
    'margin_top' => 3,   // Establecer el margen superior 
    'margin_bottom' => 0 // Establecer el margen bajo 
];


$mpdf = new \Mpdf\Mpdf($mpdfConfig);
$mpdf->WriteHTML($css, \Mpdf\HTMLParserMode::HEADER_CSS);
$mpdf->WriteHTML($contenido, \Mpdf\HTMLParserMode::HTML_BODY);
$mpdf->Output('Factura_' . $id_orden . '.pdf', 'I');
