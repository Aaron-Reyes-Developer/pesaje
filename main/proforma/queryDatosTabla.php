<?php



if (isset($_POST['id_producto'])) {

    $id_producto = $_POST['id_producto'];

    $arrayDatos = [

        'id' => 11,
        'detalleProducto' => 'Patineta',
        'precio' => 15,
        'promo' => 0

    ];

    //
} else {
    $arrayDatos = [
        [
            'id' => 10,
            'detalleProducto' => 'Bicicleta',
            'precio' => 5,
            'promo' => 0
        ],
        [
            'id' => 11,
            'detalleProducto' => 'Patineta',
            'precio' => 15,
            'promo' => 0
        ]
    ];
}



echo json_encode($arrayDatos);
