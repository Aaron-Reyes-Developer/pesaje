<?php


session_start();


if (!isset($_SESSION['usuario'])) {
    header('Location: ../../index.php');
    die();
}

include('../../conexion.php');


// QUERY MOSTRAR LOS PROVEEDORES
$queryProveedores = odbc_exec($conn, "SELECT * FROM fac_clientes_proveedores WHERE tipo = 'P'");
$arrayProveedores = [];
while ($row = odbc_fetch_array($queryProveedores)) {
    $arrayProveedores[] = $row;
}


// QUERY CABECERA AUXILIAR (documento)
$queryCabeceraAuxiliar = odbc_exec($conn, "SELECT count(*) totalRegistroCabecera FROM inv_cabecera_auxiliar WHERE tipo = 'PC'");
$arrayCabeceraAuxiliar = odbc_fetch_array($queryCabeceraAuxiliar);

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
    <link rel="stylesheet" href="./estiloPesaje.css">
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">

    <title>BrainSoft</title>
</head>

<body style="background-color: #24242C;">

    <!-- MODAL -->
    <div class="contenedorModal" id="contenedorModal">

        <!-- LOADER -->
        <div class="contenedorLoader" id="contenedorLoader">

        </div>

    </div>

    <!-- IZQUERDA -->
    <aside class="aside" id="aside">

        <div class="contenedorLogo">
            <a href="../main.php">
                <img src="../../imagenes/logos/logoEmpresa.jpg" alt="">
            </a>
            <img class="imagenBarAside" onclick="togleBar()" src="../../imagenes/cerrarBar.png" width="35px" alt="">

        </div>


        <nav class="navegacionAside">

            <ul>
                <li class=""><a href="../agregarPersonas/agregarPersona.php">Agregar Personas</a></li>
                <!-- <li class=""><a href="../proforma/proforma.php">Proforma</a></li> -->
                <li class="active"><a href="../pesajes/pesajes.php">Pesajes</a></li>
                <li class=""><a href="../informeFacturacion/informeOrdenCompra.php">Informes</a></li>
            </ul>

        </nav>

    </aside>


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


        <!-- BOTONES DE REPRODUCCION -->
        <div class="contenedorReproduccion">

            <div class="subContenedorReproduccion">

                <div class="contenedorIrAtras botonesReproduccion">

                    <!-- IR ATRAS -->
                    <a href="./editarPesaje.php?id_documento=<?php echo $arrayCabeceraAuxiliar['totalRegistroCabecera']  ?>"><img src="../../imagenes/reproduccion/irAtras.png" alt=""></a>

                </div>

                <!-- NUEVO -->
                <a href="./pesajes.php"><img src="../../imagenes/reproduccion/nuevo.png" width="40px" alt=""></a>

                <!-- BUSCAR -->
                <form action="./editarPesaje.php">
                    <input style="width:  50px;" type="number" name="id_documento" id="id_documento" placeholder="ID">
                </form>
            </div>

        </div>

        <!-- CUERPO -->
        <section class="cuerpo">

            <div class="contenedorTitulo">
                <h1>Pesajes</h1>
            </div>

            <!-- CABECERA AUXILIAR -->
            <div class="cabeceraAuxiliar mt-4 p-2 row mb-5">


                <!-- CONTENEDOR IZQUIERDO INPUTS -->
                <div class="conetenedorInputs col-lg-4 col-sm-12 d-flex flex-column gap-2 mb-5">

                    <div class="d-flex gap-2 justify-content-between align-items-center ">
                        <label for="">Documento:</label>
                        <input type="text" class="form-control inputDatos" id="documento" name="documento" value="<?php echo $arrayCabeceraAuxiliar['totalRegistroCabecera'] + 1 ?>" disabled style="cursor: not-allowed;">
                    </div>

                    <div class="d-flex gap-2 justify-content-between align-items-center ">
                        <label for="">Fecha Emisión:</label>
                        <input type="date" class="form-control inputDatos" id="fecha_emicion" name="fecha_emicion">
                    </div>


                    <!-- <div class="d-flex gap-2 justify-content-between align-items-center ">
                        <label for="">Fecha Vence:</label>
                        <input type="date" class="form-control inputDatos" id="documento" name="documento">
                    </div> -->


                    <div class="d-flex gap-2 justify-content-between align-items-center ">
                        <label for="">ID Proveedor:</label>
                        <input type="number" class="form-control inputDatos" id="id_proveedorInput" name="id_proveedorInput" value="<?php echo $arrayProveedores[0]['codigo'] ?>" disabled style="cursor: not-allowed;">
                    </div>


                    <!-- <div class="d-flex gap-2 justify-content-between align-items-center ">
                        <label for="">Vendedor:</label>
                        <select class="form-select inputDatos" aria-label="Default select example">
                            <option value="Cambiar">Cambiar</option>
                            <option value="Cambiar">Cambiar</option>
                            <option value="Cambiar">Cambiar</option>
                        </select>
                    </div> -->

                </div>


                <!-- CONTENEDOR DATOS DERECHO -->
                <div class="conetenedorInputs col-lg-8 col-sm-12 d-flex flex-column gap-2 align-items-end">

                    <input type="text" onchange="obtenerDatosProveedor(event.target.value)" class="inputNombre" name="proveedor" id="proveedor" value="" placeholder="<?php echo $arrayProveedores[0]['nombre'] ?>">
                    <input type="text" onchange="obtenerDatosProveedor(event.target.value)" class="inputCedula" name="cedulaInput" id="cedulaInput" value="<?php echo $arrayProveedores[0]['cedula_ruc'] ?>" placeholder="">
                    <input type="text" class="inputUbicacion inputSecundario" name="ubicacionInput" id="ubicacionInput" value="<?php echo $arrayProveedores[0]['direccion'] ?>" placeholder="------------------------------" disabled style="cursor: not-allowed;">
                    <input type="text" class="inputOpcionalCabecera form-control" name="detalle" id="detalle" placeholder="Detalle">
                    <input type="text" class="inputOpcionalCabecera form-control" name="placa" id="placa" placeholder="Placa">
                    <input type="text" class="inputOpcionalCabecera form-control" name="comentario" id="comentario" placeholder="Comentario">

                </div>

            </div>


            <!-- CONTENEDOR INPUTS PRODUCTO -->
            <div class="contenedorInputProducto row">

                <!-- ID -->
                <div class="mb-3 col-lg-3 col-sm-4">
                    <label for="id_producto_0" class="form-label">ID*</label>
                    <input onchange="mostrarDatosTabla(event.target.value, 0, 'id')" style="width: 100px; color:red;" class="form-control" type="number" name="id_producto_0" id="id_producto_0">
                </div>


                <!-- DETALLE -->
                <div class="mb-3 col-lg-3 col-sm-4">
                    <label for="detalle_producto_0" class="form-label">Detalle:</label>
                    <input onchange="mostrarDatosTabla(event.target.value, 0, 'detalle')" style="color:red;" class="form-control" type="text" name="detalle_producto_0" id="detalle_producto_0">
                </div>


                <!-- MEDIDA -->
                <div class="mb-3 col-lg-3 col-sm-4">
                    <label for="id_producto_0" class="form-label">Medida:</label> <br>
                    <span id="medida_0" style=" color:red;">--</span>
                </div>


                <!-- CANTIDAD -->
                <div class="mb-3 col-4 col-lg-3 ">
                    <label for="cantidad_0" class="form-label">Cantidad:</label>
                    <input onchange="calcularTotalPrecio(0)" style="min-width: 80px; max-width: 80px; color:red;" class="form-control" type="number" step="0.01" value="" placeholder="0.00" name="cantidad_0" id="cantidad_0">
                </div>


                <!-- PRECIO -->
                <div class="mb-3 col-4 col-lg-3 ">
                    <label for="precio_0" class="form-label">Precio:</label>
                    <input onchange="calcularTotalPrecio(0)" style="min-width: 80px; max-width: 80px; color:red;" class="form-control" type="number" step="0.01" value="" name="precio_0" id="precio_0">
                </div>


                <!-- HUMEDAD -->
                <div class="mb-3 col-4 col-lg-3 ">
                    <label for="humedad_0" class="form-label">Humedad:</label>
                    <input onchange="" style="min-width: 80px; max-width: 80px; color:red;" class="form-control " type="number" step="0.01" value="0" name="humedad_0" id="humedad_0">
                </div>


                <!-- IMPUREZA -->
                <div class="mb-3 col-4 col-lg-3 ">
                    <label for="id_producto_0" class="form-label">% Impureza:</label>
                    <input onchange="calcularQqNeto(0)" style="min-width: 80px; max-width: 80px; color:red;" class="form-control" type="number" step="0.01" value="2" name="inpureza_0" id="inpureza_0">
                </div>
            </div>

            <hr>

            <!-- TABLA  (Desactivada)-->
            <form id="formularioTabla" class="contenedorTabla mb-4" style="display: none;">

                <table id="tablaProducto" class="table  table-dark table-striped table-hover">

                    <thead class="table-light">

                        <tr>
                            <th scope="col " style="font-size: 13px; width: 50px;">Accio</th>
                            <th scope="col" style="font-size: 13px;  min-width: 30px;">ID</th>
                            <th scope="col" class="descripcionTabla" style="min-width: 280px; font-size: 13px;">Descripción</th>
                            <th scope="col" style="width: 80px; font-size: 13px; text-align: center;">Medida</th>
                            <th scope="col" style="width: 80px; font-size: 13px; text-align: center; display: none;">Entrada </th>
                            <th scope="col" style="width: 80px; font-size: 13px; text-align: center; display: none;">Salida </th>
                            <th scope="col" style="width: 80px; font-size: 13px; text-align: center; display: none;">Neto </th>
                            <th scope="col" style="width: 80px; font-size: 13px; text-align: center;">Canti</th>
                            <th scope="col" style="width: 80px; font-size: 13px; text-align: center;">Precio</th>
                            <th scope="col" style="width: 80px; font-size: 13px; text-align: center;">Humedad</th>
                            <th scope="col" style="min-width: 100px; font-size: 13px; text-align: center;">% Impureza</th>
                            <th scope="col" style="min-width: 100px;  font-size: 13px; text-align: center; display: none;">% Reten</th>
                            <th scope="col" style="min-width: 100px;  font-size: 13px; text-align: center; display: none;">QQ Neto</th>
                            <th scope="col" style="min-width: 100px;  font-size: 13px; text-align: center; display: none;">Sub Total</th>
                            <th scope="col" style="min-width: 100px;  font-size: 13px; text-align: center; display: none;">Reten Total</th>
                            <th scope="col" style="min-width: 100px;  font-size: 13px; text-align: center; display: none;">Total</th>
                        </tr>

                    </thead>

                    <tbody id="tbodyProducto">

                        <tr id="trProducto_0">

                            <!-- CABECERA AUXILIAR -->
                            <td style="cursor: pointer;" onclick="eliminarTr(0)">❌</td>

                            <!-- ID -->
                            <th scope="row"><input onchange="mostrarDatosTabla(event.target.value, 0, 'id')" style="min-width: 50px; max-width: 50px; color:red;" class="form-input" type="number" name="id_producto_0" id="id_producto_0"></th> <!-- ID PRODUCTO -->

                            <!-- DETALLE -->
                            <td class=""><input onchange="mostrarDatosTabla(event.target.value, 0, 'detalle')" style="width: 100%; color:red;" class="form-input" type="text" name="detalle_producto_0" id="detalle_producto_0"></td>

                            <!-- MEDIDA -->
                            <td class="numero"> <span id="medida_0" style="color:red;">--</span> </td>

                            <!-- CANTIDAD -->
                            <td class="numero"> <span><input onchange="calcularTotalPrecio(0)" style="min-width: 80px; max-width: 80px; color:red;" class="form-input" type="number" step="0.01" value="" placeholder="0.00" name="cantidad_0" id="cantidad_0"></span> </td>

                            <!-- PRECIO -->
                            <td class="numero"> <span><input onchange="calcularTotalPrecio(0)" style="min-width: 80px; max-width: 80px; color:red;" class="form-input" type="number" step="0.01" value="" name="precio_0" id="precio_0"></span> </td>

                            <!-- HUMEDAD -->
                            <td class="numero"> <span><input onchange="" style="min-width: 80px; max-width: 80px; color:red;" class="form-input " type="number" step="0.01" value="0" name="humedad_0" id="humedad_0"></span> </td>

                            <!-- % IMPUREZA -->
                            <td class="numero"> <span><input onchange="calcularQqNeto(0)" style="min-width: 80px; max-width: 80px; color:red;" class="form-input" type="number" step="0.01" value="2" name="inpureza_0" id="inpureza_0"></span> </td>

                            <!-- QQ NETO -->
                            <td class="numero" style="display: none; color:red;"> <span id="qqNeto_0">0</span> </td>

                            <!-- SUB TOTAL -->
                            <td class="numero" style="display: none; color:red;"> $ <span id="subTotal_0">0</span> </td>

                            <!-- RETEN TOTAL -->
                            <td class="numero" style="display: none; color:red;"> $ <span id="reten_0">0</span> </td>


                            <!-- TOTAL -->
                            <td class="numero" style="display: none;"> $ <span id="total_0">0</span> </td>
                        </tr>

                    </tbody>

                </table>

            </form>


            <!-- DATOS ENTRADA Y SALIDA -->
            <div class="contenedorEntradaSalida row mb-4">

                <div class="mb-3 col-lg-3  col-6">
                    <label for="entrada_0" class="form-label">Entrada:</label>
                    <input type="number" class="form-control" step="0.01" id="entrada_0" name="entrada_0" placeholder="0">
                </div>


                <div class="mb-3 col-lg-3  col-6">
                    <label for="salida_0" class="form-label">Salida:</label>
                    <input type="number" onchange="calcularNeto(0)" class="form-control" step="0.01" id="salida_0" name="salida_0" placeholder="0">
                </div>


                <div class="mb-3 col-lg-3  col-6">
                    <label for="neto_0" class="form-label">Neto:</label>
                    <input type="number" onchange="calcularCantidad(0)" class="form-control" step="0.01" id="neto_0" name="neto_0" placeholder="0">
                </div>


                <div class="mb-3 col-lg-3  col-6">
                    <label for="retenInput_0" class="form-label"> % Reten:</label>
                    <input type="number" onchange="calcularReten(0)" class="form-control" step="0.01" id="retenInput_0" name="retenInput_0" value="1" placeholder="0">
                </div>
            </div>

            <!-- BOTON AGREGAR NUEVO PRODUCTO -->
            <!-- <div class="botonAgregar btn mb-4" onclick="agregarTr()">+</div> -->


            <!-- CONTENEDOR DATOS PRECIO -->
            <div class="contenedorPrecio mb-4">

                <ul>
                    <li>
                        <span>Entrada:</span>
                        <span id="verEntrada">0.00</span>
                    </li>

                    <li>
                        <span>Salida:</span>
                        <span id="verSalida">0.00</span>
                    </li>

                    <li>
                        <span>Neto:</span>
                        <span id="verNeto">0.00</span>
                    </li>

                    <li>
                        <span>QQ Bruto:</span>
                        <span id="verQQBruto">0.00</span>
                    </li>

                    <li>
                        <span>Impureza:</span>
                        <span id="verImpureza">0.00 %</span>
                    </li>

                    <li>
                        <span>QQ Neto:</span>
                        <span id="verQQneto">0.00</span>
                    </li>

                    <li>
                        <span>Precio:</span>
                        <span id="verPrecio">$0.00</span>
                    </li>

                    <li>
                        <span>Humedad:</span>
                        <span id="verHumedad">0.00</span>
                    </li>

                    <li>
                        <span>Sub total:</span>
                        <span id="verSubTotal">$0.00</span>
                    </li>

                    <li>
                        <span>(-) Retención 1%:</span>
                        <span id="verDescuento">$0.00</span>
                    </li>

                    <li>
                        <span>Total Final:</span>
                        <span id="verTotalFinal">$0.00</span>
                    </li>
                </ul>

            </div>


            <!-- PRECIO FINAL -->
            <div class="contenedorPrecioFinal mb-4">
                <h5 id="precioFinalGrande">$0.00</h5>
            </div>


            <!-- BOTONES DE GUARDAR -->
            <div class="contenedorBotonesGuardar mb-4" id="contenedorBotonesGuardar">
                <div id="botonGuardar" class="botonGuardar btn" onclick="guardarDatos('')">Guardar</div>

                <div id="botonImprimir" class="botonGuardarImprimir btn btn-warning" onclick="guardarDatos('imprimir')">Guardar / Imprimir</div>
            </div>

            <!-- NUEVO -->
            <!-- <a href="./pesajes.php"><img src="../../imagenes/reproduccion/nuevo.png" width="10px" alt=""></a> -->

        </section>

    </main>


    <!-- ALERTA PERSONALIZADA -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script src="../../alertaPersonalizada.js"></script>


    <script src="../fuincionesJs/truncarDecimal.js"></script>
    <script src="../fuincionesJs/fechaActual.js"></script>

    <script>
        let contenedorBotonesGuardar = document.getElementById('contenedorBotonesGuardar')

        // MODAL
        let contenedorModal = document.getElementById('contenedorModal')
        let contenedorLoader = document.getElementById('contenedorLoader')

        let aside = document.getElementById('aside')
        let imagenBar = document.getElementById('imagenBar')


        // DATOS CABECERA AUXILIAR
        const fecha_emicion = document.getElementById('fecha_emicion')
        const inputNombre = document.getElementById('proveedor')
        const cedulaInput = document.getElementById('cedulaInput')
        const ubicacionInput = document.getElementById('ubicacionInput')
        const id_proveedorInput = document.getElementById('id_proveedorInput')


        // DATOS TABLA
        let tbodyProducto = document.getElementById('tbodyProducto')
        let contadorTr = 0


        // DATOS PARA LAS OPERACIONES MATEMATICAS
        let iva = 0.15


        // DATOS MOSTRAR PRECIOS ULTIMOS
        let verSubTotal = document.getElementById('verSubTotal')
        let verDescuento = document.getElementById('verDescuento')
        let verTotalFinal = document.getElementById('verTotalFinal')
        let precioFinalGrande = document.getElementById('precioFinalGrande')
        let verEntrada = document.getElementById(`verEntrada`)
        let verSalida = document.getElementById(`verSalida`)
        let verNeto = document.getElementById(`verNeto`)
        let verQQBruto = document.getElementById(`verQQBruto`)
        let verImpureza = document.getElementById(`verImpureza`)
        let verQQneto = document.getElementById(`verQQneto`)
        let verPrecio = document.getElementById(`verPrecio`)
        let verHumedad = document.getElementById(`verHumedad`)


        // LOADER
        const loader = () => {

            contenedorLoader.innerHTML = `
            <div class="subContenedorLoader">
                <div class="custom-loader"></div>
            </div> 
            `
        }

        const cerrarLoader = () => {

            contenedorLoader.remove()
        }


        // CERRAR MODAL
        const cerrarModal = () => {
            let subContenedorModal = document.getElementById('subContenedorModal')
            subContenedorModal.remove()
        }



        // AGREGAR NUEVO TR DE LA TABLA
        const agregarTr = () => {

            contadorTr++

            tbodyProducto.insertAdjacentHTML('beforeend', `

                <tr id="trProducto_${contadorTr}">

                    <!-- CABECERA AUXILIAR -->
                    <td style="cursor: pointer;" onclick="eliminarTr(${contadorTr})">❌</td>

                    <!-- ID -->
                    <th scope="row"><input onchange="mostrarDatosTabla(event.target.value, ${contadorTr})" style="min-width: 50px; max-width: 50px;" class="form-input" type="number" name="id_producto_${contadorTr}" id="id_producto_${contadorTr}"></th> <!-- ID PRODUCTO -->

                    <!-- DETALLE -->
                    <td class=""><input onchange="mostrarModal(event, ${contadorTr})" style="width: 100%;" class="form-input" type="text" name="detalle_producto_${contadorTr}" id="detalle_producto_${contadorTr}"></td>

                    <!-- MEDIDA -->
                    <td class="numero"> <span id="medida_${contadorTr}">--</span> </td>

                    <!-- ENTRADA -->
                    <td class="numero"><input onchange="" style="min-width: 80px; max-width: 80px;" class="form-input" type="number" step="0.01" value="" placeholder="0" name="entrada_${contadorTr}" id="entrada_${contadorTr}"></td>

                    <!-- SALIDA -->
                    <td class="numero"><input onchange="calcularNeto(${contadorTr})" style="min-width: 80px; max-width: 80px;" class="form-input" type="number" step="0.01" value="" placeholder="0" name="salida_${contadorTr}" id="salida_${contadorTr}"></td>

                    <!-- NETO -->
                    <td class="numero"> <span><input onchange="calcularCantidad(${contadorTr})" style="min-width: 80px; max-width: 80px;" class="form-input" type="number" step="0.01" value="" placeholder="0" name="neto_${contadorTr}" id="neto_${contadorTr}"></span> </td>

                    <!-- CANTIDAD -->
                    <td class="numero"> <span><input onchange="calcularTotalPrecio(${contadorTr})" style="min-width: 80px; max-width: 80px;" class="form-input" type="number" step="0.01" value="" placeholder="0" name="cantidad_${contadorTr}" id="cantidad_${contadorTr}"></span> </td>

                    <!-- PRECIO -->
                    <td class="numero"> <span><input onchange="calcularTotalPrecio(${contadorTr})" style="min-width: 80px; max-width: 80px;" class="form-input" type="number" step="0.01" value="" name="precio_${contadorTr}" id="precio_${contadorTr}"></span> </td>

                    <!-- HUMEDAD -->
                    <td class="numero"> <span><input onchange="" style="min-width: 80px; max-width: 80px;" class="form-input" type="number" step="0.01" value="0" name="humedad_${contadorTr}" id="humedad_${contadorTr}"></span> </td>

                    <!-- % IMPUREZA -->
                    <td class="numero"> <span><input onchange="calcularQqNeto(${contadorTr})" style="min-width: 80px; max-width: 80px;" class="form-input" type="number" step="0.01" value="2" name="inpureza_${contadorTr}" id="inpureza_${contadorTr}"></span> </td>

                    <!-- RETEN INPUT -->
                    <td class="numero"> <span><input onchange="calcularReten(${contadorTr})" style="min-width: 80px; max-width: 80px;" class="form-input" type="number" step="0.01" value="1" name="retenInput_${contadorTr}" id="retenInput_${contadorTr}"></span> </td>

                    <!-- QQ NETO -->
                    <td class="numero" style="display: none;"> <span id="qqNeto_${contadorTr}">0</span> </td>

                    <!-- SUB TOTAL -->
                    <td class="numero" style="display: none;"> $ <span id="subTotal_${contadorTr}">0</span> </td>

                    <!-- RETEN TOTAL -->
                    <td class="numero" style="display: none;"> $ <span id="reten_${contadorTr}">0</span> </td>


                    <!-- TOTAL -->
                    <td class="numero" style="display: none;"> $ <span id="total_${contadorTr}">0</span> </td>
                </tr>
            `)

        }



        // ELIMINAR EL TR DE LA TABLA
        const eliminarTr = (contador) => {

            let trProducto = document.getElementById(`trProducto_${contador}`)
            trProducto.remove()

            // Recorrer todos los TRs y actualizar sus nombres
            let trs = document.querySelectorAll('[id^="trProducto_"]');

            trs.forEach((tr, index) => {

                let newId = `trProducto_${index}`;
                tr.setAttribute('id', newId);

                // Actualizar los nombres de los elementos dentro del TR
                tr.querySelectorAll('[name]').forEach(element => {
                    let oldName = element.getAttribute('name');
                    let newName = oldName.replace(/\d+/, index);
                    element.setAttribute('name', newName); // actualizar NAMEs
                    element.setAttribute('id', newName); // actualizar IDs
                });

            });

            contadorTr = tbodyProducto.rows.length - 1
        }



        // MUESTRA UNA BUSQUEDA DE DATOS CON PRIDUCTO CON UN MODAL
        const mostrarModal = (data) => {


            contenedorModal.innerHTML = `
                <div class="subContenedorModal" id="subContenedorModal">
                
                    <form action="" id="formularioModal" class="formularioModal">

                        <!-- TITULO -->
                        <div class="contenedorTituloModal">
                            <h2>Productos (${data.length}) </h2>
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
                                    <th scope="col">Precio</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    ${data.map((e, index)=> {
                                        return (
                                            `
                                            <tr>
                                                <td scope="row"><input class="form-check-input" type="radio" value="${e.codigo}" name="id_productoModal" id="id_productoModal_${index}"></td>
                                                <th scope="row"><label for="id_productoModal_${index}">${e.codigo}</label></th>
                                                <td><label for="id_productoModal_${index}">${e.descripcion}</label></td>
                                                <td><label for="id_productoModal_${index}">$${e.costoultimo}</label></td>
                                                
                                            </tr>
                                            `
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

            formularioModal.addEventListener('submit', function(e) {

                e.preventDefault();

                let FD_modal = new FormData(formularioModal);

                let id_producto = FD_modal.get('id_productoModal')

                cerrarModal()

                mostrarDatosTabla(id_producto, 0, 'id')
            })
        }


        // MUESTRA LOS DATOS EN LA TABLA PRINCIPAL
        const mostrarDatosTabla = (dato, contador, tipo) => {


            // capturar el tr que envio la informacion
            let id_productoInput = document.getElementById(`id_producto_${contador}`)
            let detalle_producto = document.getElementById(`detalle_producto_${contador}`)
            let medida = document.getElementById(`medida_${contador}`)
            let precioInput = document.getElementById(`precio_${contador}`)

            let FD = new FormData()

            if (tipo == 'id') {

                FD.append('id_producto', dato)

            } else if (tipo == 'detalle') {

                FD.append('detalle', dato)

            }


            fetch('queryDatosTabla.php', {
                    method: 'POST',
                    body: FD
                })
                .then(res => res.json())
                .then(data => {


                    if (data.length <= 0) {
                        alertaPersonalizada('ERROR', 'No se encontro producto', 'info', 'Regresar', '')
                        return
                    }

                    if (data.length > 1) {

                        // mostrar el modal para mostrar todos los productos
                        mostrarModal(data)

                    } else {

                        // capturar el unico dato que trajo la bd
                        let costoUltimo = data[0].costoultimo

                        // MOSTRAR LOS DATOS EN EL TR
                        id_productoInput.value = data[0].codigo
                        detalle_producto.value = data[0].descripcion
                        medida.innerHTML = data[0].medida
                        precioInput.value = Number(costoUltimo)
                    }




                })
                .finally()
        }


        // CALCULAR NETO
        const calcularNeto = (contador) => {

            let entrada = document.getElementById(`entrada_${contador}`)
            let salida = document.getElementById(`salida_${contador}`)
            let neto = document.getElementById(`neto_${contador}`)
            let id_producto = document.getElementById(`id_producto_${contador}`)

            if (entrada.value == '') {
                alertaPersonalizada('ERROR', 'Sin dato de "Entrada" ', 'error', 'Regresar', '')
                salida.value = ''
                return
            }


            if (id_producto.value == '') {
                alertaPersonalizada('ERROR', 'Sin Producto', 'error', 'Regresar', '')
                salida.value = ''
                return
            }

            neto.value = (Number(entrada.value) - Number(salida.value))


            // llamamos a la funcion para mostrar el total a pagar
            calcularCantidad(contador)
            calcularTotalPrecio(contador)
        }


        // CALCULAR CANTIDAD
        const calcularCantidad = (contador) => {


            let neto = document.getElementById(`neto_${contador}`).value
            let cantidad = document.getElementById(`cantidad_${contador}`)



            cantidad.value = (neto * 0.022)


            // llamamos a la funcion para mostrar el reten
            // calcularReten(contador)
        }


        // CALCULAR EL PRECIO total precio / sub total  (tabla)
        const calcularTotalPrecio = (contador) => {



            // capturar datos
            let detalle_producto = document.getElementById(`detalle_producto_${contador}`)
            let cantidad = document.getElementById(`cantidad_${contador}`) //cantidad 
            let precio = document.getElementById(`precio_${contador}`).value //precio de el producto
            let humedad = document.getElementById(`humedad_${contador}`).value //humedad
            let inpureza = document.getElementById(`inpureza_${contador}`).value //impureza (descuento)
            let reten = document.getElementById(`reten_${contador}`) //mostrar el sub total (sin descuento) en la tabla



            let subTotalInput = document.getElementById(`subTotal_${contador}`) //mostrar el sub total (sin descuento) en la tabla
            let totalMostrar = document.getElementById(`total_${contador}`) //mostrar el total en la tabla


            // verificar el prodcuto no esta vacio
            if (detalle_producto.value == '') {
                cantidad.value = ''
                alertaPersonalizada('ERROR', 'Sin producto', 'error', 'Regresar', '')
                return
            }

            // calcular el sub total, que es el resultado de el descuento de impureza
            let subTotal = Number(cantidad.value) * Number(precio)
            subTotal = subTotal - (subTotal * (inpureza / 100))

            // Mostrar el sub total
            subTotalInput.innerHTML = subTotal

            // caluclar el total a pagar y mostrarlo
            let totalPagarTmp = Number(subTotalInput.textContent) - Number(reten.textContent)
            totalMostrar.innerHTML = totalPagarTmp


            // llamar a la funcion para mostrar el total final consumidor
            precioFinalConsumidor()
            calcularReten(contador)
        }


        // CALCULAR QQ NETO
        const calcularQqNeto = (contador) => {

            let qqNeto = document.getElementById(`qqNeto_${contador}`)
            let cantidad = Number(document.getElementById(`cantidad_${contador}`).value)
            let inpureza = Number(document.getElementById(`inpureza_${contador}`).value)

            // calcula la cantidad - el resultado de el descuento de inpureza
            qqNeto.innerHTML = (cantidad - (cantidad * (inpureza / 100)))


            calcularTotalPrecio(contador)
        }


        // CALCULAR RETEN 
        const calcularReten = (contador) => {

            let subTotal = Number(document.getElementById(`subTotal_${contador}`).textContent)
            let retenInput = Number(document.getElementById(`retenInput_${contador}`).value)
            let reten = document.getElementById(`reten_${contador}`)


            let retenTotalTmp = (subTotal * (retenInput / 100))

            reten.innerHTML = retenTotalTmp


            calcularTotalPrecio(contador)
        }


        // CALCULAR EL PRECIO FINAL PARA EL CONSUMIDOR
        const precioFinalConsumidor = () => {

            let subTotalPrecioTmp = 0
            let totalPrecioTmp = 0
            let descuento = 0


            // DATOS INPUST ENTRADA, SALIDA, ETC
            let entrada = document.getElementById('entrada_0').value
            let salida = document.getElementById('salida_0').value
            let cantidad = document.getElementById('cantidad_0').value
            let impureza = document.getElementById('inpureza_0').value
            let precio = document.getElementById('precio_0').value
            let humedad = document.getElementById('humedad_0').value
            // let entrada = document.getElementById('entrada_0')
            // let entrada = document.getElementById('entrada_0')


            // DATOS MOSTRAR
            let verEntrada = document.getElementById(`verEntrada`)
            let verSalida = document.getElementById(`verSalida`)
            let verNeto = document.getElementById(`verNeto`)
            let verQQBruto = document.getElementById(`verQQBruto`)
            let verImpureza = document.getElementById(`verImpureza`)
            let verQQneto = document.getElementById(`verQQneto`)
            let verPrecio = document.getElementById(`verPrecio`)
            let verHumedad = document.getElementById(`verHumedad`)

            for (let i = 0; i < contarTr(); i++) {

                // DATOS MOSTRAR
                let subTotal = Number(document.getElementById(`subTotal_${i}`).textContent)
                let totalPrecio = Number(document.getElementById(`total_${i}`).textContent)

                subTotalPrecioTmp += subTotal
                totalPrecioTmp += totalPrecio


            }

            descuento = subTotalPrecioTmp - totalPrecioTmp // calculo descuento

            // OPERACIONES MATEMATICAS
            let neto = 0
            let qqBruto = 0
            if (entrada == 0 || entrada == '') {
                neto = Number(cantidad)
                qqBruto = Number(cantidad)
            } else {
                neto = Number(entrada) - Number(salida)

                qqBruto = neto * 0.022
            }


            let qqNeto = qqBruto - (qqBruto * (impureza / 100))

            // MOSTRAR LOS DAOTS
            verEntrada.innerHTML = truncarDecimal(Number(entrada), 2) //salida
            verSalida.innerHTML = truncarDecimal(Number(salida), 2) //entrada 
            verNeto.innerHTML = truncarDecimal(neto, 2) //neto
            verImpureza.innerHTML = truncarDecimal(Number(impureza), 2) + ' %' //impureza
            verQQBruto.innerHTML = truncarDecimal(qqBruto, 2) //qq burto
            verQQneto.innerHTML = truncarDecimal(qqNeto, 4) //qq neto
            verPrecio.innerHTML = '$ ' + truncarDecimal(Number(precio), 2) //precio
            verHumedad.innerHTML = truncarDecimal(Number(humedad), 2) // humedad


            verSubTotal.innerHTML = '$' + truncarDecimal(subTotalPrecioTmp, 4) //sub total
            verDescuento.innerHTML = '$' + truncarDecimal(descuento, 4) //reten
            verTotalFinal.innerHTML = '$' + truncarDecimal(totalPrecioTmp, 2) // total pagar
            precioFinalGrande.innerHTML = '$' + truncarDecimal(totalPrecioTmp, 2)

        }


        // CONTAR CUANTAS FILAS TIENE LA TABLA PRINCIPAL
        const contarTr = () => {
            let totalRows = tbodyProducto.rows.length
            return totalRows
        }


        // OPERACION MATEMATICA PARA MOSTRAR LOS PRECIOS (valor y valor + iva)
        const operacionContavilidad = (cantidad, contador) => {



            // obtener los datos de el tr actual 
            let lPrecio = document.getElementById(`lPrecio_${contador}`).textContent
            let checkIva = document.getElementById(`checkIVA_${contador}`).checked

            // let promo = document.getElementById(`promo_${contador}`).textContent   //se uculta la promocion porque aun no se va a usar


            let valor = document.getElementById(`valor_${contador}`) // precio * cantidad
            let vIva = document.getElementById(`vIva_${contador}`) //valor + iva
            let totalPrecio = document.getElementById(`totalPrecio_${contador}`) // no c aun
            let pvpNeto = document.getElementById(`pvpNeto_${contador}`) // no c aun


            if (lPrecio == '0') {
                document.getElementById(`cantidadProducto_${contador}`).value = ''
                alertaPersonalizada('ERROR', 'Sin producto ingresado', 'error', 'Regresar', '')
                return
            }

            // mostrar el precio final del consumidor
            precioFinalConsumidor()

            // OPERACION MATEMATICA
            let valorTmp = Number(cantidad) * lPrecio
            let precioIvaTmp = 0

            // CALCULAR EL IVA
            if (checkIva) {

                // con iva
                precioIvaTmp = valorTmp + (valorTmp * iva)
            } else {

                // sin iva
                precioIvaTmp = valorTmp
            }



            // MOSTRAR LOS DATOS
            valor.innerHTML = valorTmp
            vIva.innerHTML = precioIvaTmp
        }



        // OBTENER DATOS DEL PROVEEDOR (cabecera auxuliar)
        const obtenerDatosProveedor = (dato) => {

            loader()
            let FD = new FormData()

            FD.append('dato', dato)

            fetch('queryDatosProveedores.php', {
                    method: 'POST',
                    body: FD
                })
                .then(res => res.json())
                .then(data => {

                    // si no hay datos del proveedor
                    if (data.length <= 0) {

                        // hacemos otro fecht para ver si hay datos con la identificación
                        let urlWebService = `http://factura.omegas-apps.com:3001/administracion/getCedula/${dato}`

                        console.log(urlWebService);

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
                                                        <label for="institucion" class="form-label">Institución:</label>
                                                        <input type="text" class="form-control" id="institucion" value=""  name="institucion" >
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

                                    console.log('no hay datos');

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
                                                        <label for="institucion" class="form-label">Institución:</label>
                                                        <input type="text" class="form-control" id="institucion" value=""  name="institucion" >
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
                                let id_proveedorInput = document.getElementById('id_proveedorInput')
                                let proveedor = document.getElementById('proveedor')
                                let cedulaInput = document.getElementById('cedulaInput')
                                let ubicacionInput = document.getElementById('ubicacionInput')
                                let formularioModalProveedor = document.getElementById('formularioModalProveedor')


                                // hacemos un fech para enviar los datos
                                formularioModalProveedor.addEventListener('submit', function(e) {
                                    e.preventDefault();

                                    let FD_modal_proveedor = new FormData(formularioModalProveedor);

                                    fetch('queryGuardarProveedor.php', {
                                            method: 'POST',
                                            body: FD_modal_proveedor
                                        })
                                        .then(res => res.json())
                                        .then(data => {

                                            if (data.mensaje == 'ok') {

                                                cerrarModal()

                                                // seteamos los datos de la cabecera auxiliar
                                                id_proveedorInput.value = data.id
                                                proveedor.value = FD_modal_proveedor.get('nombre')
                                                cedulaInput.value = FD_modal_proveedor.get('identificacion')
                                                ubicacionInput.value = FD_modal_proveedor.get('direccion')

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


                        inputNombre.value = nombreLabel
                        ubicacionInput.value = direccionLabel
                        cedulaInput.value = cedula_rucLabel
                        id_proveedorInput.value = id_proveedor

                        cerrarModal()
                    })





                })
                .finally()
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


        // MOSTRAR LA FECHA DE EMICION EN EL INPUT, CABECERA AUXILIAR
        const hoy = new Date();
        const fechaFormateada = obtenerFechaFormateada(hoy);
        // let fecha_actual = new Date().toISOString().split('T')[0];
        fecha_emicion.value = fechaFormateada



        // GUARDAR DATOS 
        const guardarDatos = (handelImprimir) => {

            let botonGuardar = document.getElementById('botonGuardar')
            let botonImprimir = document.getElementById('botonImprimir')
            /////////////////////// CAPTURAR TODOS LOS DATOS QUE NECESITO   ///////////////////////


            // DATOS CABECERA
            let tipo = 'PC'
            let documento = document.getElementById('documento').value
            let fecha_emicion = document.getElementById('fecha_emicion').value
            let id_proveedorInput = document.getElementById('id_proveedorInput').value
            let detalle = document.getElementById('detalle').value
            let placa = document.getElementById('placa').value
            let comentario = document.getElementById('comentario').value


            // DATOS PRODUCTO
            let total_tr = contarTr()
            let entrada = document.getElementById('entrada_0').value
            let salida = document.getElementById('salida_0').value
            // let neto = document.getElementById('neto_0') //no se usa para la base de datos
            let retenInput = document.getElementById('retenInput_0').value
            let id_producto = document.getElementById('id_producto_0').value
            let cantidad = document.getElementById('cantidad_0').value
            let precio = document.getElementById('precio_0').value
            let humedad = document.getElementById('humedad_0').value
            let inpureza = document.getElementById('inpureza_0').value


            // VERIFICAR SI NO HAY DATOS VACIOS
            if (cantidad == '') {
                alertaPersonalizada('ERROR', 'Sin datos', 'error', 'Regresar', '')
                return
            }


            let FD = new FormData();
            FD.append('tipo', tipo)
            FD.append('documento', documento)
            FD.append('fecha_emicion', fecha_emicion)
            FD.append('id_proveedorInput', id_proveedorInput)
            FD.append('detalle', detalle)
            FD.append('placa', placa)
            FD.append('comentario', comentario)
            FD.append('total_tr', total_tr)
            FD.append('entrada', entrada)
            FD.append('salida', salida)
            FD.append('retenInput', retenInput)
            FD.append('id_producto', id_producto)
            FD.append('cantidad', cantidad)
            FD.append('precio', precio)
            FD.append('humedad', humedad)
            FD.append('inpureza', inpureza)


            // mandar la peticion
            fetch('queryGuardarTodo.php', {
                    method: 'POST',
                    body: FD
                })
                .then(res => res.json())
                .then(data => {

                    console.log(data.mensaje);

                    if (data.errorMensaje) {
                        alertaPersonalizada('ERROR', data.mensaje, 'Error', 'Regresar', '')
                        return
                    }

                    if (!data.errorMensaje) {

                        if (handelImprimir == 'imprimir') {
                            alertaPersonalizada('CORRECTO', 'Guardado Correctamente', 'success', 'Regresar', 'recarga')
                            imprimir(documento)

                        } else {

                            alertaPersonalizada('CORRECTO', 'Guardado Correctamente', 'success', 'Regresar', '')

                            // desctivar el boton guardar
                            botonGuardar.style.display = 'none'

                            // ocultar el boton guardar/imprimir
                            botonImprimir.style.display = 'none'

                            // mostrar el boton solo imprimir
                            contenedorBotonesGuardar.innerHTML = ` 
                                <div id="botonGuardar" class="botonGuardar botonGuardado btn" >Guardado</div>

                                <div id="botonImprimir" class="botonGuardarImprimir btn btn-warning" onclick="imprimir(${documento})">Imprimir</div>
                            `
                        }




                    }




                })
                .finally()

        }



        // IMPRIMIR
        const imprimir = (id_orden) => {

            let url = `./generarPdf/plantilla.php?id_orden=${id_orden}`

            window.open(url, '_blank');
        }
    </script>

</body>

</html>