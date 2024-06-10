<?php

session_start();


if (!isset($_SESSION['usuario'])) {
    header('Location: ../../index.php');
    die();
}

include('../../conexion.php');

// buscar el % del iva
$queryDatoIva = odbc_exec($conn, "SELECT codigo, parametro FROM sys_parametros_sistema WHERE codigo = '1' ");
$rowIva = odbc_fetch_array($queryDatoIva);
$iva = $rowIva['parametro'];


// CONSULTA ULTIMO DOCUMENTO FV CABECERA
$queryDocumentoCabecera = odbc_exec($conn, "SELECT COUNT(*) documento FROM fac_cabecera WHERE tipo = 'FV' ");
$rowDocumentoCabecera = odbc_fetch_array($queryDocumentoCabecera);
$documento = $rowDocumentoCabecera['documento'] + 1;


// BUSCAR EL ULTIMO DATO DE n_referencia
$queryUltN_referencia = odbc_exec($conn, "SELECT n_referencia, punto_venta, caja FROM fac_cabecera WHERE tipo = 'FV' and documento = $documento-1 ");
$rowUltN_referencia = odbc_fetch_array($queryUltN_referencia);
$n_referencia = $rowUltN_referencia['n_referencia'];
$punto_venta = $rowUltN_referencia['punto_venta'];
$caja = $rowUltN_referencia['caja'];


//CONSULTA VENDEDORES 
$queryDatosVendedoras = odbc_exec($conn, "SELECT codigo, nombre FROM fac_vendedores WHERE activo = 'S'");


//CONSULTA CLIENTE PROVEEDOR 
$queryDatosClienteProv = odbc_exec($conn, "SELECT codigo, nombre, cedula_ruc, direccion,telefono FROM fac_clientes_proveedores WHERE  nombre = 'CONSUMIDOR FINAL'");
$rowDatosClienteProv = odbc_fetch_array($queryDatosClienteProv);


// FORMAS DE PAGO (para el modal)
$queryFormasPago = odbc_exec($conn, "SELECT codigo, descripcion FROM fac_forma_pago ");
$arrayFormaPago = [];
while ($rowPago = odbc_fetch_array($queryFormasPago)) {
    $arrayFormaPago[] =  $rowPago;
}


// BANCOS

$queryBancos = odbc_exec($conn, "SELECT codigo, banco FROM ban_bancos ");
$arrayBancos = [];
while ($rowBanco = odbc_fetch_array($queryBancos)) {
    $arrayBancos[] =  $rowBanco;
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
    <link rel="stylesheet" href="./estiloProforma.css">
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">

    <title>BrainSoft</title>
</head>

<body style="background-color: #24242C;">

    <!-- MODAL -->
    <div class="contenedorModal" id="contenedorModal">



    </div>

    <!-- LOADER -->
    <div class="contenedorLoader" id="contenedorLoader"></div>

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
                <h1>Factura</h1>
            </div>

            <!-- CABECERA AUXILIAR -->
            <div class="cabeceraAuxiliar mt-4 p-2 row mb-5">


                <!-- CONTENEDOR IZQUIERDO INPUTS -->
                <div class="conetenedorInputs col-lg-4 col-sm-12 d-flex flex-column gap-2 mb-5">

                    <div class="d-flex gap-2 justify-content-between align-items-center ">
                        <label for="">Documento:</label>
                        <input type="text" class="form-control inputDatos" id="documento" name="documento" value="<?php echo $documento ?>" disabled style="cursor: not-allowed;">
                    </div>

                    <div class="d-flex gap-2 justify-content-between align-items-center ">
                        <label for="">Fecha Emisión:</label>
                        <input type="date" class="form-control inputDatos" id="fecha_emision" name="fecha_emision">
                    </div>


                    <div class="d-flex gap-2 justify-content-between align-items-center ">
                        <label for="">Fecha Vence:</label>
                        <input type="date" class="form-control inputDatos" id="fecha_vence" name="fecha_vence">
                    </div>


                    <div class="d-flex gap-2 justify-content-between align-items-center ">
                        <label for="">ID Cliente:</label>
                        <input type="number" class="form-control inputDatos" id="id_cliente_prov" name="id_cliente_prov" value="<?php echo $rowDatosClienteProv['codigo'] ?>" disabled style="cursor: not-allowed;">
                    </div>


                    <!-- VENDEDOR -->
                    <div class="d-flex gap-2 justify-content-between align-items-center ">
                        <label for="">Vendedor:</label>
                        <select class="form-select inputDatos" name="id_vendedor" id="id_vendedor" aria-label="Default select example">
                            <?php

                            while ($rowVendedor = odbc_fetch_array($queryDatosVendedoras)) {
                            ?>
                                <option value="<?php echo $rowVendedor['codigo'] ?>"><?php echo $rowVendedor['nombre'] ?></option>
                            <?php
                            }

                            ?>
                        </select>
                    </div>


                    <!-- NUMERO DE REFENECIA -->
                    <div class="d-flex gap-2 justify-content-between align-items-center ">
                        <label for="">Numero Referencia:</label>
                        <input type="text" class="form-control inputDatos" id="numeroReferencia" name="numeroReferencia" value="" disabled style="cursor: not-allowed; width: 250px;">
                    </div>

                    <!-- TIPO DE VENTA -->
                    <div class="d-flex gap-2 justify-content-between align-items-center ">
                        <label for="">Tipo de venta:</label>
                        <select onchange="mostrarIngresoPago(event.target.value)" class="form-select inputDatos" name="tipoVenta" id="tipoVenta" aria-label="Default select example">

                            <option value="contado">Contado</option>
                            <option value="credito">Credito</option>

                        </select>
                    </div>
                </div>


                <!-- CONTENEDOR DATOS DERECHO -->
                <div class="conetenedorInputs col-lg-8 col-sm-12 d-flex flex-column gap-2 align-items-end">
                    <input type="text" onchange="obtenerDatosProveedor(event.target.value)" class="inputNombre" id="nombreCliente" name="nombreCliente" value="" placeholder="<?php echo $rowDatosClienteProv['nombre'] ?>">
                    <input type="text" onchange="obtenerDatosProveedor(event.target.value)" class="inputCedula" id="cedulaCliente" name="cedulaCliente" value="" placeholder="<?php echo $rowDatosClienteProv['cedula_ruc'] ?>">
                    <input type="text" class="inputUbicacion inputSecundario" id="ubicacionCliente" name="ubicacionCliente" value="<?php echo $rowDatosClienteProv['direccion'] ?>" placeholder="###" disabled style="cursor: not-allowed;">
                    <input type="text" class="inputTelfono inputSecundario" id="telefonoCliente" name="telefonoCliente" value="<?php echo $rowDatosClienteProv['telefono'] ?>" placeholder="099999999" disabled style="cursor: not-allowed;">
                </div>


            </div>


            <!-- DATOS INPUTS -->
            <form class="contenedorMainInput" id="contenedorMainInput">

                <!-- CARTA -->
                <div class="contenedorInputs row" id="contenedorInputs_0">

                    <!-- CERRAR CARTA -->
                    <span id="borrarCarta" class="borrarCarta" onclick="borrarCarta(0)">❌1</span>


                    <!-- ID -->
                    <div class="carta mb-3 col-lg-1 col-6" style="width: 50px;">
                        <label for="id_0" class="form-label">ID</label>
                        <input type="number" onchange="mostrarDatosTabla(event.target.value, 0)" style="width: 50px;" class="form-control" id="id_0" name="id_0" placeholder="ID">
                    </div>


                    <!-- DETALLE -->
                    <div class="carta mb-3 col-lg-1 col-6" style="width: 250px;">
                        <label for="detalle_0" class="form-label">DETALLE</label>
                        <input type="text" class="form-control" id="detalle_0" name="detalle_0" placeholder="DETALLE">
                    </div>


                    <!-- CANTIDAD -->
                    <div class="carta mb-3 col-lg-1 col-6">
                        <label for="cantidad_0" class="form-label">CANT</label>
                        <input type="number" onchange="calcularPrecioIva(0)" style="width: 50px;" class="form-control" id="cantidad_0" value="1" name="cantidad_0">
                    </div>


                    <!-- SELECT PRCIOS -->
                    <div class="carta mb-3 col-lg-1 col-6">
                        <label for="selectPrecio_0" class="form-label">PRECIOS</label><br>
                        <select onchange="cambiarPrecio(event.target.value, 0)" class="form-select" id="selectPrecio_0" name="selectPrecio_0" aria-label="Default select example">
                            <option value="1">pvp 1</option>
                            <option value="2">pvp 2</option>
                            <option value="3">pvp 3</option>
                            <option value="3">pvp 4</option>
                            <option value="3">pvp 5</option>
                        </select>
                    </div>


                    <!-- PRECIO -->
                    <div class="carta mb-3 col-lg-1 col-6">
                        <label for="precio_0" class="form-label">PRECIO</label><br>
                        <input type="number" onchange="calcularPrecioIva(0)" style="width: 90px;" class="form-control" id="precio_0" name="precio_0" placeholder="0">
                    </div>


                    <!-- DESCUENTO -->
                    <div class="carta mb-3 col-lg-1 col-6">
                        <label for="descuento_0" class="form-label">DESC</label><br>
                        <input type="number" onchange="calcularPrecioIva(0)" style="width: 50px;" class="form-control" id="descuento_0" name="descuento_0" placeholder="0">
                    </div>


                    <!-- IVA CHECK -->
                    <div class="carta mb-3 col-lg-1 col-6" style="width: 30px;">
                        <label for="checkIva_0" class="form-label">IVA</label><br>
                        <input onchange="calcularPrecioIva(0)" class="form-check-input" type="checkbox" value="1" id="checkIva_0" disabled>
                    </div>


                    <!-- VALOR -->
                    <div class="carta mb-3 col-lg-1 col-6">
                        <label for="valor_0" class="form-label">VALOR</label><br>
                        <input type="number" onchange="calcularPrecioIva(0)" style="width: 90px;  cursor: not-allowed;" class="form-control" id="valor_0" name="valor_0" placeholder="0">
                    </div>


                    <!-- VALOR + IVA-->
                    <div class="carta mb-3 col-lg-1 col-6">
                        <label for="valorIva_0" class="form-label">$V + iva</label><br>
                        <input type="number" onchange="calcularPrecioIva(0)" style="width: 90px; cursor: not-allowed;" class="form-control" id="valorIva_0" name="valorIva_0" placeholder="0">
                    </div>


                    <!-- TOTAL PRECIO-->
                    <div class="carta mb-3 col-lg-1 col-6">
                        <label for="totPrecio_0" class="form-label">Tot Parc</label><br>
                        <input type="number" onchange="calcularPrecioIva(0)" style="width: 90px; cursor: not-allowed;" class="form-control" id="totPrecio_0" name="totPrecio_0" placeholder="0">
                    </div>


                    <!-- PVP NETO-->
                    <div class="carta mb-3 col-lg-1 col-6">
                        <label for="pvpNeto_0" class="form-label">Pvp neto</label><br>
                        <input type="number" onchange="calcularPrecioIva(0)" style="width: 90px; cursor: not-allowed;" class="form-control" id="pvpNeto_0" name="pvpNeto_0" placeholder="0">
                    </div>

                </div>


            </form>



            <!-- BOTON AGREGAR NUEVO PRODUCTO -->
            <div class="botonAgregar btn mb-4" onclick="agregarCarta()">+</div>


            <!-- CONTENEDOR DATOS PRECIO -->
            <div class="contenedorPrecio mb-4">

                <ul>
                    <li>
                        <span>Sub total:</span>
                        <span id="verSubTotal">$0.00</span>
                    </li>

                    <li>
                        <span>Base Cero:</span>
                        <span id="verBaseCero">0.00</span>
                    </li>

                    <li>
                        <span>Base Tarifa:</span>
                        <span id="verTarifa">0.00</span>
                    </li>

                    <li>
                        <span>Descuento:</span>
                        <span id="verDescuento">$0.00</span>
                    </li>

                    <li>
                        <span>I.V.A:</span>
                        <span>$ <b id="verIva">0.00</b> </span>
                    </li>

                    <li>
                        <span>Total Final:</span>
                        <span>$ <b id="verTotalFinal"> 0.00</b></span>
                    </li>
                </ul>

            </div>


            <div class="contenedorMainPago">

                <!-- PRECIO FINAL -->
                <div class="contenedorPrecioFinal mb-4">
                    <h5 id="totalFinalGrande">$0.00</h5>
                </div>

                <!-- INGRESO DE PAGOS -->
                <div class="contenedorIngresoPago" title="Ingreso de pago" id="ingresoDePago">
                    <img onclick="modalIngresoPago()" src="../../imagenes/pagoDinero.png" width="5px" alt="">
                </div>

            </div>


            <!-- BOTONES DE GUARDAR -->
            <div class="contenedorBotonesGuardar mb-4">
                <div class="contenedorBotonGuardar" id="contenedorBotonGuardar">
                    <div class="botonGuardar btn" id="botonGuardar" onclick="guardarDatos('')">Guardar</div>
                </div>
                <div id="contenedorImprimirGuardar">
                    <div class="botonGuardarImprimir btn btn-warning" onclick="guardarDatos('imprimir')">Guardar / Imprimir</div>
                </div>
            </div>


        </section>

    </main>


    <!-- ALERTA PERSONALIZADA -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script src="../../alertaPersonalizada.js"></script>

    <!-- FECHA -->
    <script src="../fuincionesJs/fechaActual.js"></script>


    <!-- NAVEGACION INTERACTIVA -->
    <script src="../fuincionesJs/navegacionIzquierda.js"></script>
    <script src="../fuincionesJs/truncarDecimal.js"></script>

    <script>
        // MODAL
        let contenedorModal = document.getElementById('contenedorModal')

        // DATOS PARA LA NAVEGACION
        let aside = document.getElementById('aside')
        let imagenBar = document.getElementById('imagenBar')
        let url = '../'
        let urlImagen = '../../'
        let navActivo = 'factura'
        aside.innerHTML = navegacionIzquierda(url, urlImagen, navActivo)


        // DATOS TABLA
        let contenedorMainInput = document.getElementById('contenedorMainInput')
        let contadorMainInput = 0
        let numeroReferencia = document.getElementById('numeroReferencia')



        // DATOS PARA LAS OPERACIONES MATEMATICAS
        let iva = <?php echo $iva / 100; ?>;
        let n_referencia = '<?php echo $n_referencia; ?>'; //n_referencia -> 00000010
        let num = parseInt(n_referencia, 10); // Convertir el string a número
        num += 1; // Incrementar el número
        let newStr = num.toString().padStart(n_referencia.length, '0'); // Convertir de nuevo a string y rellenar con ceros a la izquierda                    

        let punto_venta = '<?php echo $punto_venta; ?>';
        let caja = '<?php echo $caja; ?>';


        // setear el numero de referencia input
        numeroReferencia.value = punto_venta + '-' + caja + '-' + newStr


        // DATOS MODAL FORMA DE PAGO
        let arrayFormasPago = <?php echo json_encode($arrayFormaPago); ?>;
        let arrayBancos = <?php echo json_encode($arrayBancos); ?>;
        let contadorModalPago = 0


        // CERRAR MODAL
        const cerrarModal = () => {
            let subContenedorModal = document.getElementById('subContenedorModal')
            subContenedorModal.remove()
        }


        // LOADER
        const loader = () => {

            contenedorLoader.innerHTML = `
                <div class="subContenedorLoader">
                    <div class="custom-loader"></div>
                </div> 
            `
        }


        // cerrar loader
        const cerrarLoader = () => {

            contenedorLoader.innerHTML = ''
        }



        // AGREGAR NUEVO TR DE LA TABLA
        const agregarCarta = () => {

            contadorMainInput++

            contenedorMainInput.insertAdjacentHTML('beforeend', `

                <!-- CARTA -->
                <div class="contenedorInputs row" id="contenedorInputs_${contadorMainInput}">

                    <!-- CERRAR CARTA -->
                    <span id="borrarCarta" class="borrarCarta" onclick="borrarCarta(${contadorMainInput})">❌${contadorMainInput+1}</span>


                    <!-- ID -->
                    <div class="carta mb-3 col-lg-1 col-6" style="width: 50px;">
                        <label for="id_${contadorMainInput}" class="form-label">ID</label>
                        <input type="number" onchange="mostrarDatosTabla(event.target.value, ${contadorMainInput})" style="width: 50px;" class="form-control" id="id_${contadorMainInput}" name="id_${contadorMainInput}" placeholder="ID">
                    </div>


                    <!-- DETALLE -->
                    <div class="carta mb-3 col-lg-1 col-6" style="width: 250px;">
                        <label for="detalle_${contadorMainInput}" class="form-label">DETALLE</label>
                        <input type="text" class="form-control" id="detalle_${contadorMainInput}"  name="detalle_${contadorMainInput}" placeholder="DETALLE">
                    </div>


                    <!-- CANTIDAD -->
                    <div class="carta mb-3 col-lg-1 col-6">
                        <label for="cantidad_${contadorMainInput}" class="form-label">CANT</label>
                        <input type="number" onchange="calcularPrecioIva(${contadorMainInput})" style="width: 50px;" class="form-control" id="cantidad_${contadorMainInput}" value="1" name="cantidad_${contadorMainInput}">
                    </div>


                    <!-- SELECT PRCIOS -->
                    <div class="carta mb-3 col-lg-1 col-6">
                        <label for="selectPrecio_${contadorMainInput}" class="form-label">PRECIOS</label><br>
                        <select class="form-select" onchange="cambiarPrecio(event.target.value, ${contadorMainInput})" id="selectPrecio_${contadorMainInput}" name="selectPrecio_${contadorMainInput}" aria-label="Default select example">
                            <option value="1">pvp 1</option>
                            <option value="2">pvp 2</option>
                            <option value="3">pvp 3</option>
                            <option value="3">pvp 4</option>
                            <option value="3">pvp 5</option>
                        </select>
                    </div>

                    <!-- PRECIO -->
                    <div class="carta mb-3 col-lg-1 col-6">
                        <label for="precio_${contadorMainInput}" class="form-label">PRECIO</label><br>
                        <input type="number" onchange="calcularPrecioIva(${contadorMainInput})" style="width: 90px;" class="form-control" id="precio_${contadorMainInput}" name="precio_${contadorMainInput}">
                    </div>


                    <!-- DESCUENTO -->
                    <div class="carta mb-3 col-lg-1 col-6">
                        <label for="descuento_${contadorMainInput}" class="form-label">DESCUENTO </label><br>
                        <input type="number" onchange="calcularPrecioIva(${contadorMainInput})" style="width: 50px;" class="form-control" id="descuento_${contadorMainInput}" name="descuento_${contadorMainInput}" placeholder="0">
                    </div>


                    <!-- IVA CHECK -->
                    <div class="carta mb-3 col-lg-1 col-6" style="width: 30px;">
                        <label for="checkIva_${contadorMainInput}" class="form-label">IVA</label><br>
                        <input onchange="calcularPrecioIva(${contadorMainInput})" class="form-check-input" type="checkbox" value="1" id="checkIva_${contadorMainInput}" disabled>
                    </div>


                    <!-- VALOR -->
                    <div class="carta mb-3 col-lg-1 col-6">
                        <label for="valor_${contadorMainInput}" class="form-label">VALOR</label><br>
                        <input type="number" onchange="calcularPrecioIva(0)" style="width: 90px;  cursor: not-allowed;" class="form-control" id="valor_${contadorMainInput}" name="valor_${contadorMainInput}" placeholder="0" >
                    </div>


                    <!-- VALOR + IVA-->
                    <div class="carta mb-3 col-lg-1 col-6">
                        <label for="valorIva_${contadorMainInput}" class="form-label">$V + iva</label><br>
                        <input type="number" onchange="calcularPrecioIva(0)" style="width: 90px; cursor: not-allowed;" class="form-control" id="valorIva_${contadorMainInput}" name="valorIva_${contadorMainInput}" placeholder="0" >
                    </div>


                    <!-- TOTAL PRECIO-->
                    <div class="carta mb-3 col-lg-1 col-6">
                        <label for="totPrecio_${contadorMainInput}" class="form-label">Tot Parc</label><br>
                        <input type="number" onchange="calcularPrecioIva(0)" style="width: 90px; cursor: not-allowed;" class="form-control" id="totPrecio_${contadorMainInput}" name="totPrecio_${contadorMainInput}" placeholder="0" >
                    </div>


                    <!-- PVP NETO-->
                    <div class="carta mb-3 col-lg-1 col-6">
                        <label for="pvpNeto_${contadorMainInput}" class="form-label">Pvp neto</label><br>
                        <input type="number" onchange="calcularPrecioIva(0)" style="width: 90px; cursor: not-allowed;" class="form-control" id="pvpNeto_${contadorMainInput}" name="pvpNeto_${contadorMainInput}" placeholder="0" >

                    </div>

                </div>
            `)

        }



        // ELIMINAR EL TR DE LA TABLA
        const borrarCarta = (contador) => {

            let contenedorInputs = document.getElementById(`contenedorInputs_${contador}`)
            contenedorInputs.remove()

            // Reindexar los divs restantes
            reindexarCartas();

            // llamara  la funcion para calcular los datos nuevamente
            precioFinalConsumidor()
        }


        // REINDEXAR LOS DIVS RESTANTES
        const reindexarCartas = () => {

            const cartas = document.querySelectorAll('.contenedorInputs');

            contadorMainInput = 0; // Resetear el contador

            cartas.forEach((carta, index) => {

                contadorMainInput = index;

                carta.id = `contenedorInputs_${contadorMainInput}`;

                const borrarSpan = carta.querySelector('.borrarCarta');
                borrarSpan.setAttribute('onclick', `borrarCarta(${contadorMainInput})`);
                borrarSpan.innerHTML = `❌${contadorMainInput+1}`;

                const inputs = carta.querySelectorAll('input, span[id^="precio"], span[id^="descuento"], span[id^="valor"], span[id^="valorIva"], span[id^="totPrecio"], span[id^="pvpNeto"]');

                inputs.forEach(input => {

                    const oldId = input.id;
                    const newId = oldId.replace(/_\d+$/, `_${contadorMainInput}`);
                    input.id = newId;

                    const oldName = input.name;
                    if (oldName) {
                        const newName = oldName.replace(/_\d+$/, `_${contadorMainInput}`);
                        input.name = newName;
                    }

                });

            });

        };


        // ACTIVAR / DESACTIVAR boton guardar
        const activarDesactivarGuardar = (tipo) => {

            let contenedorBotonGuardar = document.getElementById('contenedorBotonGuardar')
            let contenedorImprimirGuardar = document.getElementById('contenedorImprimirGuardar')
            let ingresoDePago = document.getElementById('ingresoDePago')

            if (tipo == 'activar') {

                contenedorBotonGuardar.innerHTML = `<div class="botonGuardar btn" id="botonGuardar" onclick="guardarDatos()">Guardar</div>`

                contenedorImprimirGuardar.innerHTML = `<div class="botonGuardarImprimir btn btn-warning" onclick="imprimir(<?php echo $documento  ?>)">Guardar / Imprimir</div>`


            } else {

                contenedorBotonGuardar.innerHTML = `<div class="botonGuardar btn" id="botonGuardar" style="background-color: #424242; color:#fff;" ">Guardardo</div>`
                contenedorImprimirGuardar.innerHTML = `<div class="botonGuardarImprimir btn btn-warning" onclick="imprimir(<?php echo $documento  ?>)">Imprimir</div>`

                ingresoDePago.innerHTML = '<img src="../../imagenes/pagoDineroDenegar.png" width="5px" alt="">'
                ingresoDePago.style.cursor = 'not-allowed';

            }
        }


        // RETORNAR LAS VUELTAS DEL MODAL PAGO
        const vueltasModalPago = () => {
            let vueltasModalPago = document.querySelectorAll('#cartaPago')
            return vueltasModalPago.length - 1
        }


        // GUARDA LOS DATOS DEL MODAL
        const guardarModalPago = () => {

            let formularioModalPago = document.getElementById('formularioModalPago')
            let TotalFinal = document.getElementById('verTotalFinal').textContent
            let documento = document.getElementById('documento').value

            let FDModal = new FormData(formularioModalPago);


            let totalPagarModal = 0

            // obtenemos el total del valor para verificar que no se exeda al momento de guardar
            for (let i = 0; i <= vueltasModalPago(); i++) {

                let valor = document.getElementById(`valor_${i}`).value


                totalPagarModal += Number(valor)

            }


            // verificamos si no exede el valor total 
            if (Number(totalPagarModal).toFixed(2) > Number(TotalFinal).toFixed(2)) {
                alertaPersonalizada('ERROR', 'EL VALOR SUPERA AL TOTAL PAGAR', 'error', 'REGRESAR', '')
                return
            }


            // enviamos el total de vueltas
            FDModal.append('vueltasModalPago', vueltasModalPago())
            FDModal.append('documento', documento)
            FDModal.append('n_referencia', newStr)

            // guaramos los datos de la cabecera principal
            guardarDatos('modalPago')


            // guardar Datos Modal
            fetch('guardarDatosModal.php', {
                    method: 'POST',
                    body: FDModal
                })
                .then(res => res.json())
                .then(data => {

                    if (data.errorMensaje) {
                        alertaPersonalizada('ERROR', 'ALGO SALIO MAL', 'error', 'REGRESAR', '')
                        return
                    }

                    if (data.mensaje == 'ok') {
                        alertaPersonalizada('CORRECTO', 'DATOS INGRESADOS', 'success', 'REGRESAR', '')

                        cerrarModal()

                        // descativar el boton guardar (el boton de guardar todo )
                        activarDesactivarGuardar('descativar')

                        // desactvar el boton de guaradar modal
                        let botonGuardarModalPago = document.getElementById('botonGuardarModalPago')
                        botonGuardarModalPago.remove()


                        return
                    }

                })
                .finally()

        }


        // AGREGAR MAS CARTAS AL MODAL FORMA DE PAGO
        const agregarCartaModalPago = () => {

            let verTotalFinal = document.getElementById('verTotalFinal').textContent
            let contenedorCartaPago = document.getElementById('contenedorCartaPago')


            let valor_pagar = 0


            contadorModalPago++


            for (let i = 0; i < contadorModalPago; i++) {
                let saldo = document.getElementById(`saldo_${i}`).value
                let valor = document.getElementById(`valor_${i}`).value

                valor_pagar = (saldo - valor)
            }


            contenedorCartaPago.insertAdjacentHTML('beforeend', `
            
                <!-- CARTA  -->
                <div class="col-12 row" style="background-color: #c5c5c5;" id="cartaPago">

                    <div class="mb-3 col-3">
                        <label for="formaPago" class="form-label">Form pago*</label>
                        <select class="form-select" aria-label="Default select example" id="formaPago_${contadorModalPago}" name="formaPago_${contadorModalPago}" required>
                            ${arrayFormasPago.map(e => {

                                return (
                                    `<option value="${e.codigo}">${e.descripcion}</option>`
                                )

                            })}     
                        </select>
                    </div>


                    <div class="mb-3 col-3">
                        <label for="saldo" class="form-label">Saldo*</label>
                        <input type="text" class="form-control" id="saldo_${contadorModalPago}" value="${Number(valor_pagar).toFixed(2)}" name="saldo_${contadorModalPago}" required style="cursor: not-allowed;">
                    </div>


                    <div class="mb-3 col-3">
                        <label for="fecha_emision_modal" class="form-label">Emision*</label>
                        <input type="date" class="form-control" id="fecha_emision_modal_${contadorModalPago}" name="fecha_emision_modal_${contadorModalPago}" required>
                    </div>


                    <div class="mb-3 col-3">
                        <label for="fecha_vencimiento_modal" class="form-label">Venci*</label>
                        <input type="date" class="form-control" id="fecha_vencimiento_modal_${contadorModalPago}" name="fecha_vencimiento_modal_${contadorModalPago}"  required>
                    </div>

                    <div class="mb-3 col-3">
                        <label for="valor" class="form-label">Valor*</label>
                        <input type="number" step="0.01" class="form-control" id="valor_${contadorModalPago}" name="valor_${contadorModalPago}" value="${ Number(valor_pagar).toFixed(2)  }" placeholder="0.00" required autofocus >
                    </div>

                    <!-- DATOS DEL BANCO -->
                    <div class="mb-3 col-4">
                        <label for="banco" class="form-label">Banco:</label>
                        <select class="form-select" name="banco_${contadorModalPago}" id="banco_${contadorModalPago}" aria-label="Default select example" >
                            <option value="">Seleccionar Banco</option>
                            ${arrayBancos.map(e => {

                                return (
                                    `<option value="${e.codigo}">${e.banco}</option>`
                                )

                            })} 
                        </select>
                    </div>

                    
                    <div class="mb-3 col-4">
                        <label for="cuenta" class="form-label">Cuenta:</label>
                        <input type="text"  class="form-control" id="cuenta_${contadorModalPago}" name="cuenta_${contadorModalPago}" placeholder="#" >
                    </div>

                    <div class="mb-3 col-4">
                        <label for="cheque" class="form-label">Cheque:</label>
                        <input type="text"  class="form-control" id="cheque_${contadorModalPago}" name="cheque_${contadorModalPago}" placeholder="#" >
                    </div>

                </div>
                
                <hr>
            
            `)

            // PONER LAS FECHAS DE EMISION Y DE VENCIMIENTO
            let fecha_emision_modal = document.getElementById(`fecha_emision_modal_${contadorModalPago}`)
            let fecha_vencimiento_modal = document.getElementById(`fecha_vencimiento_modal_${contadorModalPago}`)

            let hoy = new Date()
            fecha_emision_modal.value = obtenerFechaFormateada(hoy)
            fecha_vencimiento_modal.value = obtenerFechaFormateada(hoy)

        }


        // MUESTRA UNA BUSQUEDA DE DATOS CON UN MODAL
        const modalIngresoPago = () => {

            let verTotalFinal = document.getElementById('verTotalFinal').textContent
            let entidad = document.getElementById('nombreCliente').value
            let documento = document.getElementById('documento').value


            contenedorModal.innerHTML = `

                <div class="subContenedorModal" id="subContenedorModal" style="z-index:5; ">
                
                    <form action="" id="formularioModalPago" class="formularioModal" style=" max-width:600px;">

                        <!-- TITULO -->
                        <div class="contenedorTituloModal">
                            <h2>Ingreso de pago <i style="background-color: #424242; color:#4CFF48;">($ ${verTotalFinal})</i> </h2>
                            <hr>
                        </div>

                        <!-- CERRAR -->
                        <span class="cerrarModal" onclick="cerrarModal()">X</span>

                        <div class="contenedorInputsModal row"  style="max-height:80vh; overflow-y: scroll;">

                            <!-- CONTENEDOR CARTA  -->
                            <div class="col-12 row" style="padding: 0; margin: 0;" id="contenedorCartaPago">
                            
                                <!-- CARTA  -->
                                <div class="col-12 row" style="background-color: #c5c5c5;" id="cartaPago">

                                    <div class="mb-3 col-3">
                                        <label for="formaPago" class="form-label">Form pago*</label>
                                        <select class="form-select" aria-label="Default select example" id="formaPago_0" name="formaPago_0" required>
                                            ${arrayFormasPago.map(e => {

                                                return (
                                                    `<option value="${e.codigo}">${e.descripcion}</option>`
                                                )

                                            })}     
                                        </select>
                                    </div>


                                    <div class="mb-3 col-3">
                                        <label for="saldo" class="form-label">Saldo*</label>
                                        <input type="text" class="form-control" id="saldo_0" value="${verTotalFinal}" name="saldo_0" required  style="cursor: not-allowed;">
                                    </div>


                                    <div class="mb-3 col-3">
                                        <label for="fecha_emision_modal" class="form-label">Emision*</label>
                                        <input type="date" class="form-control" id="fecha_emision_modal_0" name="fecha_emision_modal_0" required>
                                    </div>


                                    <div class="mb-3 col-3">
                                        <label for="fecha_vencimiento_modal" class="form-label">Venci*</label>
                                        <input type="date" class="form-control" id="fecha_vencimiento_modal_0" name="fecha_vencimiento_modal_0"  required>
                                    </div>

                                    <div class="mb-3 col-3">
                                        <label for="valor" class="form-label">Valor*</label>
                                        <input type="number" step="0.01" class="form-control" id="valor_0" name="valor_0"  placeholder="0.00" required autofocus >
                                    </div>

                                    <!-- DATOS DEL BANCO -->
                                    <div class="mb-3 col-4">
                                        <label for="banco" class="form-label">Banco:</label>
                                        <select class="form-select" name="banco_0" id="banco_0" aria-label="Default select example" >
                                            <option value="">Seleccionar Banco</option>
                                            ${arrayBancos.map(e => {

                                                return (
                                                    `<option value="${e.codigo}">${e.banco}</option>`
                                                )

                                            })} 
                                        </select>
                                    </div>

                                    
                                    <div class="mb-3 col-4">
                                        <label for="cuenta" class="form-label">Cuenta:</label>
                                        <input type="text"  class="form-control" id="cuenta_0" name="cuenta_0" placeholder="#" >
                                    </div>

                                    <div class="mb-3 col-4">
                                        <label for="cheque" class="form-label">Cheque:</label>
                                        <input type="text"  class="form-control" id="cheque_0" name="cheque_0" placeholder="#" >
                                    </div>

                                </div>
                                
                                <hr>

                            </div>
                           

                            <hr>

                            
                            <!-- BOTON AGREGAR NUEVA CARTA -->
                            <div class="mb-3 col-12">
                                <div class="botonAgregar btn " onclick="agregarCartaModalPago()" style="width: 150px; height:40px;">+</div>
                            </div>




                            <!-- CLIENTE -->
                            <div class="mb-3 col-4">
                                <label for="entidad" class="form-label">Entidad:</label>
                                <input type="text"  class="form-control" id="entidad" name="entidad" value="CONSUMIDOR FINAL">
                            </div>

                            <div class="mb-3 col-8">
                                <label for="observacion" class="form-label">Observación: </label>
                                <textarea class="form-control" id="observacion" name="observacion" rows="3"></textarea>
                            </div>

                        </div>

                        

                        <!-- BOTONES -->
                        <div class="row">
                            <div class="btn btn-danger col-6" onclick="cerrarModal()">REGRESAR</div>
                            <div class="btn botonConfirmarModal col-6" id="botonGuardarModalPago" onclick="guardarModalPago()">REGISTRAR</div>
                        </div>

                    </form>

                </div>
            `

            // PONER LAS FECHAS DE EMISION Y DE VENCIMIENTO
            let fecha_emision_modal = document.getElementById('fecha_emision_modal_0')
            let fecha_vencimiento_modal = document.getElementById('fecha_vencimiento_modal_0')

            let hoy = new Date()
            fecha_emision_modal.value = obtenerFechaFormateada(hoy)
            fecha_vencimiento_modal.value = obtenerFechaFormateada(hoy)

        }


        // MUESTRA LOS DATOS EN LA TABLA PRINCIPAL
        const mostrarDatosTabla = (id_producto, contador) => {

            loader()

            // capturar los datos para mostrar la info
            let detalle = document.getElementById(`detalle_${contador}`)
            let precio = document.getElementById(`precio_${contador}`)
            let checkIva = document.getElementById(`checkIva_${contador}`)
            let selectPrecio = document.getElementById(`selectPrecio_${contador}`)
            let valorIva = document.getElementById(`valorIva_${contador}`)
            let valor = document.getElementById(`valor_${contador}`)
            let totalParcial = document.getElementById(`totPrecio_${contador}`)
            let cantidad = document.getElementById(`cantidad_${contador}`)
            let descuento = document.getElementById(`descuento_${contador}`)
            let pvpNeto = document.getElementById(`pvpNeto_${contador}`)

            let FD = new FormData()

            FD.append('id_producto', id_producto)

            fetch('queryDatosTabla.php', {
                    method: 'POST',
                    body: FD
                })
                .then(res => res.json())
                .then(data => {


                    // si no existe el producto
                    if (data.length <= 0) {
                        alertaPersonalizada('ERROR', 'No existe producto', 'error', 'REGRESAR', '')
                        return
                    }


                    // separar el array del objeto
                    const [datos] = data


                    // VERIFICAR SI EL PRODUCTO LLEVA IVA
                    if (datos.IVA == 'S') {

                        //activamos el check del iva
                        checkIva.checked = true
                    }

                    // MOSTRAR LOS DATOS EN EL TR
                    detalle.value = datos.descripcion
                    precio.value = truncarDecimal(datos.pvp1, 4)


                    // Hacemos otro fetch para traer los precios de el producto
                    fetch('queryPrecioProducto.php', {
                            method: 'POST',
                            body: FD
                        }).then(res => res.json())
                        .then(dataPrecio => {

                            // mostrar los precios en el select
                            dataPrecio.map(e => {
                                selectPrecio.innerHTML = `
                                    <option value="${e.pvp1}">pvp 1 </option>
                                    <option value="${e.pvp2}">pvp 2</option>
                                    <option value="${e.pvp3}">pvp 3</option>
                                    <option value="${e.pvp4}">pvp 4</option>
                                    <option value="${e.pvp5}">pvp 5</option>
                            
                                `
                            })


                        })


                    //////////////////////////////////////////////////       CALCULOS       //////////////////////////////////////////////////
                    calcularPrecioIva(contador)

                })
                .finally(() => {
                    cerrarLoader()
                })

        }


        // mostrar valor/ v + iva / Tot Prarc / Pvp neto
        const calcularPrecioIva = (contador) => {


            let precio = document.getElementById(`precio_${contador}`)
            let checkIva = document.getElementById(`checkIva_${contador}`)
            let valorIva = document.getElementById(`valorIva_${contador}`)
            let valor = document.getElementById(`valor_${contador}`)
            let totalParcial = document.getElementById(`totPrecio_${contador}`)
            let cantidad = document.getElementById(`cantidad_${contador}`)
            let descuento = document.getElementById(`descuento_${contador}`)
            let pvpNeto = document.getElementById(`pvpNeto_${contador}`)

            if (checkIva.checked) { //producto con iva

                // mostrar el valor (ya viene con iva)
                valorIva.value = truncarDecimal(Number(precio.value), 4)

                // mostrar el valor (sin iva)
                valor.value = truncarDecimal(Number(precio.value) / ((iva + 1)), 4)

            } else { // produto sin iva

                // mostrar el valor (sin iva)
                valor.value = truncarDecimal(Number(precio.value), 4)

                // calcular el valor para el iva
                valorIva.value = truncarDecimal(Number(valor.value), 4)

            }


            // calcular valor parcial
            totalParcial.value = truncarDecimal(Number(valor.value) * Number(cantidad.value), 4)

            // calcular pvp neto
            pvpNeto.value = truncarDecimal(Number(valorIva.value) - (Number(valorIva.value) * (Number(descuento.value) / 100)), 4)


            // llamamos a la funcion para calcular el precio final del consumidor
            precioFinalConsumidor()
        }


        // cambia el precio dependiendo el select
        const cambiarPrecio = (precio, contador) => {

            let precioText = document.getElementById(`precio_${contador}`)

            precioText.value = truncarDecimal(precio, 4)

            // llamar a que se calcule los datos nuevamente
            calcularPrecioIva(contador)
        }


        // CONTAR CUANTAS FILAS TIENE LA TABLA PRINCIPAL
        const contarTr = () => {

            // Paso 2: Obtén todos los hijos directos del contenedor
            const hijosDirectos = contenedorMainInput.children;

            // Paso 3: Filtra solo los elementos que son divs
            const divsHijosDirectos = Array.from(hijosDirectos).filter(elemento => elemento.tagName.toLowerCase() === 'div');

            // Paso 4: Cuenta los divs hijos directos
            const cantidadDeDivsHijosDirectos = divsHijosDirectos.length;

            return cantidadDeDivsHijosDirectos
        }



        // CALCULAR EL PRECIO FINAL PARA EL CONSUMIDOR
        const precioFinalConsumidor = () => {

            // DIVS PARA MOSTRAR
            let verSubTotal = document.getElementById('verSubTotal')
            let verDescuento = document.getElementById('verDescuento')
            let verIva = document.getElementById('verIva')
            let verTotalFinal = document.getElementById('verTotalFinal')
            let totalFinalGrande = document.getElementById('totalFinalGrande')
            let verBaseCero = document.getElementById('verBaseCero')
            let verTarifa = document.getElementById('verTarifa')


            let subTotalTmp = 0
            let descuentoTmp = 0
            let ivaTmp = 0
            let baseCeroTmp = 0
            let baseTrifaTmp = 0

            for (let i = 0; i < contarTr(); i++) {

                let totParcial = document.getElementById(`totPrecio_${i}`).value
                let descuento = document.getElementById(`descuento_${i}`).value
                let valorIva = document.getElementById(`valorIva_${i}`).value
                let checkIva = document.getElementById(`checkIva_${i}`)


                // calculos
                descuentoTmp += (Number(totParcial) * (descuento / 100)) //descuento total

                // calcular solo los productos que vienen con iva
                if (checkIva.checked) {

                    ivaTmp += (Number(totParcial) * iva)
                    baseTrifaTmp += Number(totParcial)

                    //
                } else {

                    // los productos que no vienen con iva se le llama BASE 0
                    baseCeroTmp += Number(totParcial)

                }




                // sumatoria
                subTotalTmp += Number(totParcial)
            }

            let totalPagar = (subTotalTmp - descuentoTmp) + ivaTmp



            verSubTotal.innerHTML = '$ ' + truncarDecimal(Number(subTotalTmp), 2)
            verDescuento.innerHTML = '$ ' + truncarDecimal(Number(descuentoTmp), 2)
            verBaseCero.innerHTML = truncarDecimal(Number(baseCeroTmp), 2)
            verTarifa.innerHTML = truncarDecimal(Number(baseTrifaTmp), 2)
            verIva.innerHTML = Number(ivaTmp).toFixed(2)
            verTotalFinal.innerHTML = truncarDecimal(Number(totalPagar), 2)
            totalFinalGrande.innerHTML = '$ ' + truncarDecimal(Number(totalPagar), 2)


        }


        // LOGIA HEADER AMBURGUESA
        const togleBar = () => {

            aside.classList.toggle('asideActive')

            if (aside.classList.contains('asideActive')) {
                imagenBar.src = '../../imagenes/cerrarBar.png'

            } else {
                imagenBar.src = '../../imagenes/bar.png'

            }


        }


        // MOSTRAR / OCULTAR FORMA DE INGRESO DINERO
        const mostrarIngresoPago = (formaPago) => {

            let ingresoDePago = document.getElementById('ingresoDePago')

            if (formaPago != 'contado') {

                ingresoDePago.innerHTML = '<img src="../../imagenes/pagoDineroDenegar.png" width="5px" alt="">'
                ingresoDePago.style.cursor = 'not-allowed';

            } else {
                ingresoDePago.innerHTML = '<img onclick="modalIngresoPago()" src="../../imagenes/pagoDinero.png" width="5px" alt="">'

            }
        }


        // OBTENER DATOS DEL PROVEEDOR (cabecera auxuliar)
        const obtenerDatosProveedor = (dato) => {

            loader()
            let FD = new FormData()

            FD.append('dato', dato)

            fetch('queryDatosCliente.php', {
                    method: 'POST',
                    body: FD
                })
                .then(res => res.json())
                .then(data => {

                    // si no hay datos del proveedor
                    if (data.length <= 0) {

                        // hacemos otro fecht para ver si hay datos con la identificación
                        let urlWebService = `http://factura.omegas-apps.com:3001/administracion/getCedula/${dato}`

                        fetch(urlWebService)
                            .then(res => res.json())
                            .then(dataWeb => {


                                // mostramos un formulario lleno si el estatus es true
                                if (dataWeb.status) {

                                    console.log('si hay datos');

                                    contenedorModal.innerHTML = `
                                        <div class="subContenedorModal" id="subContenedorModal">
                                        
                                            <form action="" id="formularioModalProveedor" class="formularioModal">

                                                <!-- TITULO -->
                                                <div class="contenedorTituloModal">
                                                    <h2>Agregar Proveedor</h2>
                                                    <hr>
                                                </div>

                                                <!-- CERRAR -->
                                                <span class="cerrarModal" onclick="cerrarModal()">X</span>

                                                <!-- INPUTS -->
                                                <div class="row">
                                                
                                                    <div class="mb-3 col-6">
                                                        <label for="nombre" class="form-label">Nombres* </label>
                                                        <input type="text" class="form-control" id="nombre" name="nombre" value="${dataWeb.value.persona}"  requerid>
                                                    </div>

                                                    <div class="mb-3 col-6">
                                                        <label for="identificacion" class="form-label">Identificacion*</label>
                                                        <input type="text" class="form-control" id="identificacion" name="identificacion" value="${dataWeb.value.cedula}"   requerid>
                                                    </div>


                                                    <div class="mb-3 col-6">
                                                        <label for="direccion" class="form-label">Dirección:</label>
                                                        <input type="text" class="form-control" id="direccion" value="${dataWeb.value.direccion}"  name="direccion" >
                                                    </div>


                                                    <div class="mb-3 col-6">
                                                        <label for="telefono" class="form-label">Telefono:</label>
                                                        <input type="text" class="form-control" id="telefono" value=""  name="telefono" >
                                                    </div>


                                                    <div class="mb-3 col-6">
                                                        <label for="correo" class="form-label">Correo:</label>
                                                        <input type="email" class="form-control" id="correo" value=""  name="correo" >
                                                    </div>

                                                    <div class="mb-3 col-6">
                                                        <label for="ciudad" class="form-label">Ciudad:</label>
                                                        <input type="text" class="form-control" id="ciudad" value="${dataWeb.value.lugar_nacimiento}"  name="ciudad" >
                                                    </div>

                                                    
                                                
                                                </div>


                                                <!-- BOTONES -->
                                                <div class="row">
                                                    <div class="btn btn-danger col-6" onclick="cerrarModal()">CANCELAR</div>
                                                    <input type="submit" class="btn botonConfirmarModal col-6" value="REGISTRAR">
                                                </div>

                                            </form>

                                        </div>
                                    `

                                } else {


                                    // mostramos un formulario vacio
                                    contenedorModal.innerHTML = `
                                        <div class="subContenedorModal" id="subContenedorModal">
                                        
                                            <form action="" id="formularioModalProveedor" class="formularioModal">

                                                <!-- TITULO -->
                                                <div class="contenedorTituloModal">
                                                    <h2>Agregar Proveedor</h2>
                                                    <hr>
                                                </div>

                                                <!-- CERRAR -->
                                                <span class="cerrarModal" onclick="cerrarModal()">X</span>

                                                <!-- INPUTS -->
                                                <div class="row">
                                                
                                                    <div class="mb-3 col-6">
                                                        <label for="nombre" class="form-label">Nombres* </label>
                                                        <input type="text" class="form-control" id="nombre" name="nombre"   requerid>
                                                    </div>

                                                    <div class="mb-3 col-6">
                                                        <label for="identificacion" class="form-label">Identificacion*</label>
                                                        <input type="text" class="form-control" id="identificacion" name="identificacion"   requerid>
                                                    </div>


                                                    <div class="mb-3 col-6">
                                                        <label for="direccion" class="form-label">Dirección:</label>
                                                        <input type="text" class="form-control" id="direccion"  name="direccion" >
                                                    </div>


                                                    <div class="mb-3 col-6">
                                                        <label for="telefono" class="form-label">Telefono:</label>
                                                        <input type="text" class="form-control" id="telefono" value=""  name="telefono" >
                                                    </div>


                                                    <div class="mb-3 col-6">
                                                        <label for="correo" class="form-label">Correo:</label>
                                                        <input type="email" class="form-control" id="correo" value=""  name="correo" >
                                                    </div>

                                                    <div class="mb-3 col-6">
                                                        <label for="ciudad" class="form-label">Ciudad:</label>
                                                        <input type="text" class="form-control" id="ciudad"   name="ciudad" >
                                                    </div>

                                                    
                                                
                                                </div>


                                                <!-- BOTONES -->
                                                <div class="row">
                                                    <div class="btn btn-danger col-6" onclick="cerrarModal()">CANCELAR</div>
                                                    <input type="submit" class="btn botonConfirmarModal col-6" value="REGISTRAR">
                                                </div>

                                            </form>

                                        </div>
                                    `

                                }


                                // caputramos datos del formulario
                                let id_proveedorInput = document.getElementById('id_cliente_prov')
                                let proveedor = document.getElementById('nombreCliente')
                                let cedulaInput = document.getElementById('cedulaCliente')
                                let ubicacionInput = document.getElementById('ubicacionCliente')
                                let telefonoCliente = document.getElementById('telefonoCliente')
                                let formularioModalProveedor = document.getElementById('formularioModalProveedor')


                                // hacemos un fech para enviar los datos
                                formularioModalProveedor.addEventListener('submit', function(e) {
                                    e.preventDefault();

                                    console.log('enviando datos');
                                    let FD_modal_proveedor = new FormData(formularioModalProveedor);

                                    fetch('queryGuardarCliente.php', {
                                            method: 'POST',
                                            body: FD_modal_proveedor
                                        })
                                        .then(res => res.json())
                                        .then(data => {

                                            console.log(data);

                                            if (data.mensaje == 'ok') {

                                                cerrarModal()

                                                // seteamos los datos de la cabecera auxiliar
                                                id_proveedorInput.value = data.id
                                                proveedor.value = FD_modal_proveedor.get('nombre')
                                                cedulaInput.value = FD_modal_proveedor.get('identificacion')
                                                ubicacionInput.value = FD_modal_proveedor.get('direccion')
                                                telefonoCliente.value = FD_modal_proveedor.get('telefono')

                                                alertaPersonalizada('CORRECTO', 'Guardado Correctamente', 'success', 'Regresar', '')

                                            } else {
                                                alertaPersonalizada('ERROR', 'Algo salio mal', 'error', 'Regresar', '')
                                                return
                                            }

                                        })
                                })


                            })
                            .finally(() => {
                                cerrarLoader()
                            })

                        return
                    }

                    contenedorModal.innerHTML = `
                        <div class="subContenedorModal" id="subContenedorModal">
                        
                            <form action="" id="formularioModal" class="formularioModal">

                                <!-- TITULO -->
                                <div class="contenedorTituloModal">
                                    <h2>Proveedores</h2>
                                    <hr>
                                </div>

                                <!-- CERRAR -->
                                <span class="cerrarModal" onclick="cerrarModal()">X</span>

                                <!-- TABLA -->
                                <div class="contendorTabla mb-4">

                                    <table class="table table-striped">

                                        <thead>
                                            <tr>
                                                <th scope="col">Accion</th>
                                                <th scope="col">ID</th>
                                                <th scope="col">Nombre</th>
                                                <th scope="col">Identificación</th>
                                                <th scope="col">Direccion</th>
                                            </tr>
                                        </thead>

                                        <tbody>

                                            ${data.map((e,index) => {
                                                return (
                                                    `<tr>
                                                        <td scope="row"><input class="form-check-input" type="radio" value="${e.codigo}" name="proveedorModal" id="proveedorModal_${index}"></td>
                                                        <th scope="row"><label for="proveedorModal_${index}">${e.codigo}</label></th>
                                                        <td><label for="proveedorModal_${index}" id="nombreLabel_${e.codigo}">${e.nombre}</label></td>
                                                        <td><label for="proveedorModal_${index}" id="cedula_rucLabel_${e.codigo}">${e.cedula_ruc}</label></td>
                                                        <td><label for="proveedorModal_${index}" id="direccionLabel_${e.codigo}">${(e.direccion)}</label></td>
                                                    </tr>`
                                                )
                                            })}
                                            
                                        
                                            
                                        </tbody>

                                    </table>

                                </div>


                                <!-- BOTONES -->
                                <div class="row">
                                    <div class="btn btn-danger col-6" onclick="cerrarModal()">CANCELAR</div>
                                    <input type="submit" class="btn botonConfirmarModal col-6" value="REGISTRAR">
                                </div>

                            </form>

                        </div>
                    `


                    let formularioModal = document.getElementById('formularioModal')

                    // CUANDO HAGA SUBMIT EL MODAL
                    formularioModal.addEventListener('submit', function(e) {

                        e.preventDefault();

                        let FD_modal = new FormData(formularioModal)

                        let id_proveedor = FD_modal.get('proveedorModal')
                        let nombreLabel = document.getElementById(`nombreLabel_${id_proveedor}`).textContent
                        let cedula_rucLabel = document.getElementById(`cedula_rucLabel_${id_proveedor}`).textContent
                        let direccionLabel = document.getElementById(`direccionLabel_${id_proveedor}`).textContent


                        // CAPTURAR LOS DATOS A MOSTRAR EN LA CABECERA PRINCIPAL
                        let id_proveedorInput = document.getElementById('id_cliente_prov')
                        let proveedor = document.getElementById('nombreCliente')
                        let cedulaInput = document.getElementById('cedulaCliente')
                        let ubicacionInput = document.getElementById('ubicacionCliente')
                        let telefonoCliente = document.getElementById('telefonoCliente')

                        proveedor.value = nombreLabel
                        ubicacionInput.value = direccionLabel
                        cedulaInput.value = cedula_rucLabel
                        telefonoCliente.value = '099999999'
                        id_proveedorInput.value = id_proveedor

                        cerrarModal()
                    })


                })
                .finally(() => cerrarLoader())
        }


        // GUARDAR LOS DATOS EN LA BD
        const guardarDatos = (tipo) => {

            loader()

            // OBTENER DATOS
            let id_documento = document.getElementById('documento').value
            let fecha_emision = document.getElementById('fecha_emision').value
            let fecha_vence = document.getElementById('fecha_vence').value
            let id_cliente_prov = document.getElementById('id_cliente_prov').value
            let id_vendedor = document.getElementById('id_vendedor').value
            let verBaseCero = document.getElementById('verBaseCero').textContent
            let verTarifa = document.getElementById('verTarifa').textContent
            let vueltas = contarTr()
            let verTotalFinal = document.getElementById('verTotalFinal').textContent
            let totalIva = document.getElementById('verIva').textContent
            let entidad = document.getElementById('nombreCliente').value


            let formulario = document.getElementById('contenedorMainInput')
            let FD = new FormData(formulario)
            FD.append('id_documento', id_documento)
            FD.append('fecha_emision', fecha_emision)
            FD.append('fecha_vence', fecha_vence)
            FD.append('id_cliente_prov', id_cliente_prov)
            FD.append('id_vendedor', id_vendedor)
            FD.append('baseCero', verBaseCero)
            FD.append('tarifa', verTarifa)
            FD.append('vueltas', vueltas)
            FD.append('n_referencia', newStr)
            FD.append('subTotal', verTotalFinal)
            FD.append('totalPagar', verTotalFinal)
            FD.append('totalIva', totalIva)
            FD.append('entidad', entidad)

            if (tipo == 'modalPago') {
                FD.append('modalPago', true)
            }


            // FECHT 
            fetch('queryGuardarDatos.php', {
                    method: 'POST',
                    body: FD
                })
                .then(res => res.json())
                .then(data => {

                    if (data.errorMensaje) {
                        alertaPersonalizada('ERROR', 'ALGO SALIO MAL', 'error', 'REGRESAR', '')
                        return
                    }

                    if (data.mensaje == 'ok') {
                        alertaPersonalizada('CORRECTO', 'DATOS INGRESADOS', 'success', 'REGRESAR', '')

                        // imprimir documento
                        if (tipo == 'imprimir') {
                            imprimir(id_documento) //imprimimos el  
                        }

                        activarDesactivarGuardar('desactivar')

                        return
                    }

                })
                .finally(_ => cerrarLoader())



        }


        // IMPRIMIR
        const imprimir = (id_documento) => {

            let ulr = `./generarPdf/plantilla.php?id_orden=${id_documento}`

            window.open(ulr)
        }




        // FECHAS ACTUALES EN LA CABECERA AUXILIAR
        let fecha_emision = document.getElementById('fecha_emision')
        let fecha_vence = document.getElementById('fecha_vence')

        let hoy = new Date()
        fecha_emision.value = obtenerFechaFormateada(hoy)
        fecha_vence.value = obtenerFechaFormateada(hoy)
    </script>

</body>

</html>