<?php

session_start();


if (!isset($_SESSION['usuario'])) {
    header('Location: ../../index.php');
    die();
}

?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0C0C0C">
    <link rel="icon" href="../../imagenes/icono.ico" type="image/x-icon">


    <!-- BOOSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- CSS -->
    <link rel="stylesheet" href="../estiloLoader.css">
    <link rel="stylesheet" href="../estiloMain.css">
    <link rel="stylesheet" href="../estiloModal.css">
    <link rel="stylesheet" href="../../estiloAlerta.css">
    <link rel="stylesheet" href="./estiloFacturacion.css">
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">

    <title>BrainSoft</title>

</head>

<body style="background-color: #24242C;">

    <!-- MODAL -->
    <div class="contenedorModal" id="contenedorModal">


        <!-- LOADER -->
        <div class="contenedorLoader" id="contenedorLoader">
            <!-- <div class="subContenedorLoader">
                <div class="custom-loader"></div>
            </div> -->
        </div>


    </div>

    <!-- IZQUERDA -->
    <aside class="aside" id="aside"></aside>


    <main>

        <!-- HEADER -->
        <header class="header">

            <ul>
                <li class="liBar"><img id="imagenBar" onclick="togleBar()" src="../../imagenes/bar.png" width="35px" alt=""></li>
                <li><a href="">Configuración</a></li>
                <li>

                </li>

            </ul>

            <a href="../../cerrarSesion.php" class="salir">Salir</a>
        </header>


        <!-- CUERPO -->
        <section class="cuerpo">

            <div class="contenedorTitulo">
                <h1>Informes de Ordenes de compras</h1>
            </div>


            <!-- RANGO DE FECHAS -->
            <form action="" class="mb-4" id="formulario">

                <!-- CONTENEDOR DESDE HASTA  -->
                <div class="contenedorRango row ">

                    <div class="mb-3 col-sm-3 col-lg-4">
                        <label for="fecha_desde" class="form-label">Desde*</label>
                        <input type="date" class="form-control" id="fecha_desde" name="fecha_desde" required>
                    </div>


                    <div class="mb-3 col-sm-3 col-lg-4">
                        <label for="fecha_hasta" class="form-label">Hasta*</label>
                        <input type="date" class="form-control" id="fecha_hasta" name="fecha_hasta" required>
                    </div>

                </div>


                <input type="Submit" class="btn  botonGenerar" value="Generar Tabla">
            </form>



            <!-- TABLA -->
            <div class="contendorTabla">

                <table class="table table-secondary table-striped" id="tablaInforme">

                    <thead>

                        <tr>
                            <!-- <th style="text-align: center; min-width: 50px;" scope="col">Tip</th> -->
                            <th style="text-align: center; min-width: 70px;" scope="col">Doc.</th>
                            <th style="text-align: center; min-width: 70px;" scope="col">Cod P.</th>
                            <th style="text-align: center; min-width: 70px;" scope="col">Prod.</th>
                            <th style="text-align: center; min-width: 170px;" scope="col">Cliente / proveedor</th>
                            <th style="text-align: center; min-width: 50px;" scope="col">Fecha</th>
                            <th style="text-align: center; min-width: 50px;" scope="col">QQ Bruto</th>
                            <th style="text-align: center; min-width: 50px;" scope="col">Impureza</th>
                            <th style="text-align: center; min-width: 50px;" scope="col">QQ Neto</th>
                            <th style="text-align: center; min-width: 50px;" scope="col">Precio</th>
                            <th style="text-align: center; min-width: 50px;" scope="col">SubTot</th>
                            <th style="text-align: center; min-width: 50px;" scope="col">Retención</th>
                            <th style="text-align: center; min-width: 50px;" scope="col">Total</th>
                            <th style="text-align: center; min-width: 50px;" scope="col">Humedad</th>
                        </tr>

                    </thead>


                    <tbody id="tbodyInforme"></tbody>

                </table>

            </div>

            <hr>

            <!-- BOTON GENERAR PDF/EXEL -->
            <div class="generarPDf btn btn-warning" onclick="GenerarPdf()">GENERAR PDF</div>
            <div class="generarPDf btn btn-success" onclick="generarExel()">GENERAR EXEL</div>

        </section>

    </main>



    <!-- ALERTA PERSONALIZADA -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script src="../../alertaPersonalizada.js"></script>

    <script src="../../main/fuincionesJs/truncarDecimal.js"></script>
    <script src="../../main/fuincionesJs/fechaActual.js"></script>


    <!-- NAVEGACION INTERACTIVA -->
    <script src="../fuincionesJs/navegacionIzquierda.js"></script>


    <script>
        // MODAL
        let contenedorModal = document.getElementById('contenedorModal')


        // DATOS PARA LA NAVEGACION
        let aside = document.getElementById('aside')
        let imagenBar = document.getElementById('imagenBar')
        let url = '../'
        let urlImagen = '../../'
        let navActivo = 'informeOrdenCompra'
        aside.innerHTML = navegacionIzquierda(url, urlImagen, navActivo)



        // TABLA
        let tablaInforme = document.getElementById('tablaInforme')
        let tbodyInforme = document.getElementById('tbodyInforme')

        let formulario = document.getElementById('formulario')

        // GENERAR LA TABLA DE INFORME
        formulario.addEventListener('submit', function(e) {
            e.preventDefault();

            let FD = new FormData(formulario);

            fetch('queryProceso.php', {
                    method: 'POST',
                    body: FD
                })
                .then(res => res.json())
                .then(data => {

                    if (data.length <= 0) {
                        alertaPersonalizada('ERROR', 'No existen datos', 'warning', 'Regresar')
                        return
                    }

                    // RECORRER EL ARREGLO DE DATOS 
                    data.map((e, index) => {

                        let impuresa = e.cantidad * (e.descuento / 100)
                        let qqNeto = e.cantidad - (e.cantidad * (e.descuento / 100))
                        let precio = e.valor
                        let subTotal = ((e.cantidad - (e.cantidad * (e.descuento / 100))) * truncarDecimal(e.valor, 2))
                        let retencion = subTotal * (e.otro_descuento / 100)
                        let total = subTotal - (subTotal * (e.otro_descuento / 100))
                        let humedad = e.bonificacion

                        return (
                            tbodyInforme.insertAdjacentHTML('beforeend', `
                            <tr>

                                <td id="${index}" style="text-align: center;  font-size: 0.8rem;" scope="row">${e.documento}</td>
                                <td id="${index}" style="text-align: center;  font-size: 0.8rem;" scope="row">${e.codigo}</td>
                                <td id="${index}" style="text-align: center;  font-size: 0.8rem;" scope="row">${e.descripcion}</td>
                                <td id="${index}" style="text-align: center;  font-size: 0.8rem;">${e.detalle_mov}</td>
                                <td id="${index}" style="text-align: center; font-size: 0.8rem;">${e.fecha_emision}</td>
                                <td id="dato1_${index}" style="text-align: center;  font-size: 0.8rem;">${e.cantidad}</td>
                                <td id="dato2_${index}" style="text-align: center;  font-size: 0.8rem;">${truncarDecimal(impuresa, 2)}</td>
                                <td id="dato3_${index}" style="text-align: center;  font-size: 0.8rem;">${truncarDecimal(qqNeto,4)}</td>
                                <td id="dato4_${index}" style="text-align: center;  font-size: 0.8rem;">${truncarDecimal(precio,2)}</td>
                                <td id="dato5_${index}" style="text-align: center;  font-size: 0.8rem;">${truncarDecimal( subTotal,4)}</td>
                                <td id="dato6_${index}" style="text-align: center;  font-size: 0.8rem;">${truncarDecimal( retencion,4)}</td>
                                <td id="dato7_${index}" style="text-align: center;  font-size: 0.8rem;">${truncarDecimal( total,2)}</td>
                                <td id="dato8_${index}" style="text-align: center;  font-size: 0.8rem;">${humedad}</td>
                            </tr>
                        `)
                        )
                    })


                    // CONTAR LAS FILAS DEL TR
                    let trCount = tbodyInforme.getElementsByTagName('tr').length;


                    let totalDato1 = 0
                    let totalDato2 = 0
                    let totalDato3 = 0
                    let totalDato4 = 0
                    let totalDato5 = 0
                    let totalDato6 = 0
                    let totalDato7 = 0
                    let totalDato8 = 0

                    for (let index = 0; index < trCount; index++) {
                        let dato1 = Number(document.getElementById(`dato1_${index}`).textContent)
                        let dato2 = Number(document.getElementById(`dato2_${index}`).textContent)
                        let dato3 = Number(document.getElementById(`dato3_${index}`).textContent)
                        let dato4 = Number(document.getElementById(`dato4_${index}`).textContent)
                        let dato5 = Number(document.getElementById(`dato5_${index}`).textContent)
                        let dato6 = Number(document.getElementById(`dato6_${index}`).textContent)
                        let dato7 = Number(document.getElementById(`dato7_${index}`).textContent)
                        let dato8 = Number(document.getElementById(`dato8_${index}`).textContent)

                        totalDato1 += dato1
                        totalDato2 += dato2
                        totalDato3 += dato3
                        totalDato4 += dato4
                        totalDato5 += dato5
                        totalDato6 += dato6
                        totalDato7 += dato7
                        totalDato8 += dato8
                    }


                    tbodyInforme.insertAdjacentHTML('beforeend', `
                            <tr>
                                
                                <th style="text-align: center;  font-size: 0.8rem;" scope="row">Total: ${trCount}</th>
                                <td style="text-align: center;  font-size: 0.8rem;"></td>
                                <td style="text-align: center;  font-size: 0.8rem;"></td>
                                <td style="text-align: center; font-size: 0.8rem;"></td>
                                <td style="text-align: center; font-size: 0.8rem;"></td>
                                <th style="text-align: center;  font-size: 0.8rem;">${truncarDecimal(totalDato1,2)}</th>
                                <th style="text-align: center;  font-size: 0.8rem;">${truncarDecimal(totalDato2,2) }</th>
                                <th style="text-align: center;  font-size: 0.8rem;">${truncarDecimal(totalDato3,2) }</th>
                                <th style="text-align: center;  font-size: 0.8rem;">--</th>
                                <th style="text-align: center;  font-size: 0.8rem;">${truncarDecimal(totalDato5,2) }</th>
                                <th style="text-align: center;  font-size: 0.8rem;">${truncarDecimal(totalDato6,2) }</th>
                                <th style="text-align: center;  font-size: 0.8rem;">${truncarDecimal(totalDato7,2) }</th>
                                <th style="text-align: center;  font-size: 0.8rem;">--</th>
                            </tr>
                        `)
                })
        })


        // LOGIA HEADER AMBURGUESA
        const togleBar = () => {

            aside.classList.toggle('asideActive')

            if (aside.classList.contains('asideActive')) {
                imagenBar.src = '../../imagenes/cerrarBar.png'

            } else {
                imagenBar.src = '../../imagenes/bar.png'

            }


        }

        // MOSTRAR LA FECHA DE EMICION EN EL INPUT, CABECERA AUXILIAR
        const hoy = new Date();
        let fecha_desde = document.getElementById('fecha_desde')
        let fecha_hasta = document.getElementById('fecha_hasta')
        // let fecha_actual = new Date().toISOString().split('T')[0];
        const fechaFormateada = obtenerFechaFormateada(hoy);
        fecha_hasta.value = fechaFormateada
        fecha_desde.value = fechaFormateada


        // GENERAR EL INFORME EXEL
        const generarExel = () => {
            let fecha_inicio = fecha_desde.value
            let fecha_fin = fecha_hasta.value
            let url = `./informeExel.php?fecha_inicio=${fecha_inicio}&fehca_fin=${fecha_fin}`
            window.open(url)
        }

        // GENERAR PDF
        const GenerarPdf = () => {

            let desdeInput = document.getElementById('fecha_desde').value
            let hastaInput = document.getElementById('fecha_hasta').value
            let url = `./generarPdf/plantilla.php?desde=${desdeInput}&hasta=${hastaInput}`

            window.open(url, '_blank');
        }
    </script>

</body>

</html>