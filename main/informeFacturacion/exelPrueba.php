<?php

require '../../vendor/autoload.php';
include('../../conexion.php');
include('../funcionesPhp/truncarDeciamal.php');

$fecha_inicio_original = '2024-05-27';
$fecha_fin_original = '2024-05-28';


$fecha_inicio_date = new DateTime($fecha_inicio_original); // Crear un objeto DateTime a partir de la fecha original
$fecha_inicio = $fecha_inicio_date->format('d-m-Y'); // Formatear la fecha en el nuevo formato  

$fecha_fin_date = new DateTime($fecha_fin_original); // Crear un objeto DateTime a partir de la fecha original
$fecha_fin = $fecha_fin_date->format('d-m-Y'); // Formatear la fecha en el nuevo formato    




use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\IOFactory;
use PhpOffice\PhpSpreadsheet\Style\Font;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;


$spreadsheet = new Spreadsheet();

$spreadsheet->getProperties()->setCreator('Aaron Reyes')->setTitle('Mi exel');

$spreadsheet->setActiveSheetIndex(0);
$hojaActiva = $spreadsheet->getActiveSheet();

$hojaActiva->setCellValue('A2', 'Fecha de inicio');
$hojaActiva->setCellValue('B2', $fecha_inicio);


$hojaActiva->setCellValue('A3', 'Fecha Final');
$hojaActiva->setCellValue('B3', $fecha_fin);



$hojaActiva->setCellValue('B5', 'Doc');
$hojaActiva->setCellValue('C5', 'Cod Pro.');
$hojaActiva->setCellValue('D5', 'Clien/Prov');
$hojaActiva->setCellValue('E5', 'Fecha');
$hojaActiva->setCellValue('F5', 'QQ Bruto');
$hojaActiva->setCellValue('G5', 'Impureza');
$hojaActiva->setCellValue('H5', 'QQ Neto');
$hojaActiva->setCellValue('I5', 'Precio');
$hojaActiva->setCellValue('J5', 'Sub Total');
$hojaActiva->setCellValue('K5', 'Retencion');
$hojaActiva->setCellValue('L5', 'Total');
$hojaActiva->setCellValue('M5', 'Humedad');

// Aplicar tamaño a las filas
$hojaActiva->getColumnDimension('A')->setWidth(17);
$hojaActiva->getColumnDimension('B')->setWidth(17);
$hojaActiva->getColumnDimension('C')->setWidth(10);
$hojaActiva->getColumnDimension('D')->setWidth(15);
$hojaActiva->getColumnDimension('E')->setWidth(11);
$hojaActiva->getColumnDimension('F')->setWidth(11);
$hojaActiva->getColumnDimension('G')->setWidth(11);
$hojaActiva->getColumnDimension('H')->setWidth(11);
$hojaActiva->getColumnDimension('I')->setWidth(11);
$hojaActiva->getColumnDimension('J')->setWidth(11);
$hojaActiva->getColumnDimension('K')->setWidth(11);
$hojaActiva->getColumnDimension('L')->setWidth(11);
$hojaActiva->getColumnDimension('M')->setWidth(11);

// Height de las tabla
$hojaActiva->getRowDimension('5')->setRowHeight(20);

//extender el titulo del documento
$hojaActiva->setCellValue('B1', 'Informe de Ventas');
$hojaActiva->mergeCells('B1:M1');


// Aplicar negrita a la fila 1
$hojaActiva->getStyle('B5:M5')->getFont()->setBold(true);
$hojaActiva->getStyle('A2')->getFont()->setBold(true); // negrita en las fecha de inicio
$hojaActiva->getStyle('A3')->getFont()->setBold(true); // negrita en las fecha final
$hojaActiva->getStyle('B1')->getFont()->setBold(true); // negrita TITULO


// Centrar el texto
$hojaActiva->getStyle('B1:B3')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); // titulo de informe   
$hojaActiva->getStyle('B5:M5')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER); //texto header de tabla
// centrar horizontal  
$hojaActiva->getStyle('B5:M5')->getAlignment()->setVertical(Alignment::VERTICAL_CENTER);


// Añadir bordes a la fila 1
$styleArray = [
    'borders' => [
        'allBorders' => [
            'borderStyle' => Border::BORDER_THIN,
        ],
    ],
];
$hojaActiva->getStyle('B5:M5')->applyFromArray($styleArray);


// Aplicar color de fondo a la cabecera de la tabla
$hojaActiva->getStyle('B5:M5')->getFill()->setFillType(Fill::FILL_SOLID)->getStartColor()->setARGB('F2F2F2'); // Color amarillo



/////////////////////////////////////////     CONSULTA //////////////////////////////////
// $queryDatosTabla = odbc_exec($conn, "CALL inv_rep_documentos_auxiliar_detalle( @p_periodo = '001', @p_desde = '$fecha_inicio_original',@p_hasta = '$fecha_fin_original',@p_tipo = 'PC')");
$fila = 6; // desde aqui se comienza los datos
$contadorTotal = 0;

$TqqBruto = 0;
$Timpureza = 0;
$TqqNeto = 0;
$TsubTotal = 0;
$Treten = 0;
$Ttotal = 0;
$Thumedad = 0;

// while ($row = odbc_fetch_array($queryDatosTabla)) {

//     $contadorTotal += 1;

//     // calculos
//     $impuresa = $row['cantidad'] * ($row['descuento'] / 100);
//     $qqNeto = $row['cantidad'] - ($row['cantidad'] * (($row['descuento'] / 100)));
//     $precio = $row['valor'];
//     $subTotal = (($row['cantidad'] - ($row['cantidad'] * ($row['descuento'] / 100))) * truncarDecimal($row['valor'], 2));
//     $retencion = $subTotal * ($row['otro_descuento'] / 100);
//     $total = $subTotal - ($subTotal * ($row['otro_descuento'] / 100));
//     $humedad = $row['bonificacion'];


//     // SUMA DE TOTALES
//     $TqqBruto += $row['cantidad'];
//     $Timpureza += $impuresa;
//     $TqqNeto += $qqNeto;
//     $TsubTotal += $subTotal;
//     $Treten += $retencion;
//     $Ttotal += $total;
//     $Thumedad += $humedad;



//     // MOSTRAR LOS DATOS
//     $hojaActiva->setCellValue('B' . $fila, $row['documento']); //documento
//     $hojaActiva->setCellValue('C' . $fila, $row['codigo']); //codigo producto
//     $hojaActiva->setCellValue('D' . $fila, $row['detalle_mov']); // cliente prov
//     $hojaActiva->setCellValue('E' . $fila, $row['fecha_emision']); // fecha
//     $hojaActiva->setCellValue('F' . $fila, $row['cantidad']); // qq bruto
//     $hojaActiva->setCellValue('G' . $fila, truncarDecimal($impuresa, 2)); // impureza
//     $hojaActiva->setCellValue('H' . $fila, truncarDecimal($qqNeto, 4)); // qq neto
//     $hojaActiva->setCellValue('I' . $fila, truncarDecimal($precio, 2)); // precio
//     $hojaActiva->setCellValue('J' . $fila, truncarDecimal($subTotal, 4)); // sub total
//     $hojaActiva->setCellValue('K' . $fila, truncarDecimal($retencion, 4)); // reten
//     $hojaActiva->setCellValue('L' . $fila, truncarDecimal($total, 2)); // total
//     $hojaActiva->setCellValue('M' . $fila, truncarDecimal($humedad, 2)); // humedad

//     // poner border en toda la tabla
//     $hojaActiva->getStyle('B' . $fila . ':M' . $fila . '')->applyFromArray($styleArray);

//     //centrar texto en la tabla (no todo)
//     $hojaActiva->getStyle('B' . $fila . ':E' . $fila . '')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);


//     $fila++; // Empezar a escribir los datos desde la fila 6
// }


// MOSTRAR LOS DATOS
$hojaActiva->setCellValue('B' . $fila, 1); //documento
$hojaActiva->setCellValue('C' . $fila, 1); //codigo producto
$hojaActiva->setCellValue('D' . $fila, 1); // cliente prov
$hojaActiva->setCellValue('E' . $fila, 1); // fecha
$hojaActiva->setCellValue('F' . $fila, 1); // qq bruto
$hojaActiva->setCellValue('G' . $fila, 1); // impureza
$hojaActiva->setCellValue('H' . $fila, 1); // qq neto
$hojaActiva->setCellValue('I' . $fila, 1); // precio
$hojaActiva->setCellValue('J' . $fila, 1); // sub total
$hojaActiva->setCellValue('K' . $fila, 1); // reten
$hojaActiva->setCellValue('L' . $fila, 1); // total
$hojaActiva->setCellValue('M' . $fila, 1); // humedad


// colocar una fila mas para los totales
$hojaActiva->setCellValue('B' . $fila, 'Totales (' . $contadorTotal . ')'); // Total Texto
$hojaActiva->setCellValue('F' . $fila, truncarDecimal($TqqBruto, 2)); // Total QQ BRUTO
$hojaActiva->setCellValue('G' . $fila, truncarDecimal($Timpureza, 2)); // Total IMPUREZA
$hojaActiva->setCellValue('H' . $fila, truncarDecimal($TqqNeto, 2)); // Total QQ NETO
$hojaActiva->setCellValue('J' . $fila, truncarDecimal($TsubTotal, 2)); // Total SUB TOTAL
$hojaActiva->setCellValue('K' . $fila, truncarDecimal($Treten, 2)); // Total RETEN
$hojaActiva->setCellValue('L' . $fila, truncarDecimal($Ttotal, 2)); // Total TOTAL
$hojaActiva->setCellValue('M' . $fila, truncarDecimal($Thumedad, 2)); // Total TOTAL

// centrar totales
$hojaActiva->getStyle('F' . $fila . ':L' . $fila . '')->getAlignment()->setHorizontal(Alignment::HORIZONTAL_CENTER);


// aplicar negrita a las fila de los totales
$hojaActiva->getStyle('B' . $fila . ':M' . $fila . '')->getFont()->setBold(true);


header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment;filename="Reporte_' . $fecha_inicio . '_' . $fecha_fin . '.Xlsx"');
header('Cache-Control: max-age=0');


$writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
$writer->save('php://output');
