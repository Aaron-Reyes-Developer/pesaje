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
                <li class="active"><a href="">Proforma</a></li>
                <li class=""><a href="../pesajes/pesajes.php">Pesajes</a></li>
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


        <!-- CUERPO -->
        <section class="cuerpo">

            <div class="contenedorTitulo">
                <h1>Proforma</h1>
            </div>

            <!-- CABECERA AUXILIAR -->
            <div class="cabeceraAuxiliar mt-4 p-2 row mb-5">


                <!-- CONTENEDOR IZQUIERDO INPUTS -->
                <div class="conetenedorInputs col-lg-4 col-sm-12 d-flex flex-column gap-2 mb-5">

                    <div class="d-flex gap-2 justify-content-between align-items-center ">
                        <label for="">Documento:</label>
                        <input type="text" class="form-control inputDatos" id="documento" name="documento" value="0" disabled style="cursor: not-allowed;">
                    </div>

                    <div class="d-flex gap-2 justify-content-between align-items-center ">
                        <label for="">Fecha Emisión:</label>
                        <input type="date" class="form-control inputDatos" id="documento" name="documento">
                    </div>


                    <div class="d-flex gap-2 justify-content-between align-items-center ">
                        <label for="">Fecha Vence:</label>
                        <input type="date" class="form-control inputDatos" id="documento" name="documento">
                    </div>


                    <div class="d-flex gap-2 justify-content-between align-items-center ">
                        <label for="">ID Cliente:</label>
                        <input type="number" class="form-control inputDatos" id="documento" name="documento" value="0" disabled style="cursor: not-allowed;">
                    </div>


                    <div class="d-flex gap-2 justify-content-between align-items-center ">
                        <label for="">Vendedor:</label>
                        <select class="form-select inputDatos" aria-label="Default select example">
                            <option value="Cambiar">Cambiar</option>
                            <option value="Cambiar">Cambiar</option>
                            <option value="Cambiar">Cambiar</option>
                        </select>
                    </div>

                </div>


                <!-- CONTENEDOR DATOS DERECHO -->
                <div class="conetenedorInputs col-lg-8 col-sm-12 d-flex flex-column gap-2 align-items-end">
                    <input type="text" class="inputNombre" value="" placeholder="Consumidor Final">
                    <input type="text" class="inputCedula" value="" placeholder="999999998">
                    <input type="text" class="inputUbicacion inputSecundario" value="" placeholder="###" disabled style="cursor: not-allowed;">
                    <input type="text" class="inputTelfono inputSecundario" value="" placeholder="099999999" disabled style="cursor: not-allowed;">
                </div>

            </div>



            <!-- TABLA -->
            <form id="formularioTabla" class="contenedorTabla mb-4">

                <table id="tablaProducto" class="table table-secondary table-striped">

                    <thead>

                        <tr>
                            <th scope="col " style="font-size: 13px; width: 50px;">Accio</th>
                            <th scope="col" style="font-size: 13px;  min-width: 30px;">ID</th>
                            <th scope="col" class="descripcionTabla" style="min-width: 280px; font-size: 13px;">Descripción</th>
                            <th scope="col" style="width: 80px; font-size: 13px; text-align: center;">L precio</th>
                            <!-- <th scope="col" style="width: 80px; font-size: 13px; text-align: center;">Prom</th> -->
                            <th scope="col" style="width: 80px; font-size: 13px; text-align: center;">Canti</th>
                            <th scope="col" style="width: 80px; font-size: 13px; text-align: center;">Desc.</th>
                            <th scope="col" style="width: 80px; font-size: 13px; text-align: center;">IVA</th>
                            <th scope="col" style="min-width: 100px; font-size: 13px; text-align: center;">Valor</th>
                            <th scope="col" style="min-width: 100px;  font-size: 13px; text-align: center;">$V + iva</th>
                            <th scope="col" style="min-width: 80px; font-size: 13px; text-align: center;">Tot Preci</th>
                            <th scope="col" style="min-width: 100px; font-size: 13px; text-align: center;">pvp neto</th>
                        </tr>

                    </thead>

                    <tbody id="tbodyProducto">

                        <tr id="trProducto_0">
                            <td style="cursor: pointer;" onclick="eliminarTr(0)">❌</td>
                            <th scope="row"><input onchange="mostrarDatosTabla(event.target.value, 0)" style="min-width: 50px; max-width: 50px;" class="form-input" type="number" name="id_producto_0" id="id_producto_0"></th> <!-- ID PRODUCTO -->
                            <td class=""><input onchange="mostrarModal(event, 0)" style="width: 100%;" class="form-input" type="text" name="detalle_producto_0" id="detalle_producto_0"></td> <!-- DETALLE PRODUCTO -->
                            <td class="numero">$ <span id="lPrecio_0">0</span></td>
                            <!-- <td class="numero" id="promo_0">$0</td> -->
                            <td class="numero"><input onchange="operacionContavilidad(event.target.value, 0)" style="min-width: 50px; max-width: 50px;" class="form-input" type="number" value="" name="cantidadProducto_0" id="cantidadProducto_0"></td>
                            <td class="numero">$ <span id="descuento_0">0</span> </td>
                            <td class="numero"><input onchange="calcularIva(0)" class="form-check-input" type="checkbox" value="1" name="checkIVA_0" id="checkIVA_0"></td> <!-- CHECK IVA -->
                            <td class="numero">$ <span id="valor_0">0</span></td>
                            <td class="numero">$ <span id="vIva_0">0</span></td>
                            <td class="numero">$ <span id="totalPrecio_0">0</span></td>
                            <td class="numero">$ <span id="pvpNeto_0">0</span></td>
                        </tr>

                    </tbody>

                </table>

            </form>


            <!-- BOTON AGREGAR NUEVO PRODUCTO -->
            <div class="botonAgregar btn mb-4" onclick="agregarTr()">+</div>


            <!-- CONTENEDOR DATOS PRECIO -->
            <div class="contenedorPrecio mb-4">

                <ul>
                    <li>
                        <span>Sub total:</span>
                        <span id="verSubTotal">$0.00</span>
                    </li>

                    <li>
                        <span>Descuento:</span>
                        <span id="verDescuento">$0.00</span>
                    </li>

                    <li>
                        <span>I.V.A:</span>
                        <span id="verIva">$0.00</span>
                    </li>

                    <li>
                        <span>Total Final:</span>
                        <span id="verTotalFinal">$0.00</span>
                    </li>
                </ul>

            </div>


            <!-- PRECIO FINAL -->
            <div class="contenedorPrecioFinal mb-4">
                <h5>$0.00</h5>
            </div>


            <!-- BOTONES DE GUARDAR -->
            <div class="contenedorBotonesGuardar mb-4">
                <div class="botonGuardar btn">Guardar</div>
                <div class="botonGuardarImprimir btn btn-warning">Guardar / Imprimir</div>
            </div>


        </section>

    </main>


    <!-- ALERTA PERSONALIZADA -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script src="../../alertaPersonalizada.js"></script>

    <script>
        // MODAL
        let contenedorModal = document.getElementById('contenedorModal')

        let aside = document.getElementById('aside')
        let imagenBar = document.getElementById('imagenBar')


        // DATOS TABLA
        let tbodyProducto = document.getElementById('tbodyProducto')
        let contadorTr = 0


        // DATOS PARA LAS OPERACIONES MATEMATICAS
        let iva = 0.15


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
                    <td style="cursor: pointer;" onclick="eliminarTr(${contadorTr})">❌</td>
                    <th scope="row"><input onchange="mostrarDatosTabla(event.target.value, ${contadorTr})" style="min-width: 50px; max-width: 50px;" class="form-input" type="number" name="id_producto_${contadorTr}" id="id_producto_${contadorTr}"></th> <!-- ID PRODUCTO -->
                    <td class=""><input onchange="mostrarModal(event, ${contadorTr})" style="width: 100%;" class="form-input" type="text" name="detalle_producto_${contadorTr}" id="detalle_producto_${contadorTr}"></td> <!-- DETALLE PRODUCTO -->
                    <td class="numero" >$ <span id="lPrecio_${contadorTr}">0</span></td>
                    <!--<td class="numero" >$ <span id="promo_${contadorTr}">0</span></td>-->
                    <td class="numero"><input onchange="operacionContavilidad(event.target.value, ${contadorTr})" style="min-width: 50px; max-width: 50px;" class="form-input" type="number" value="" name="cantidadProducto_${contadorTr}" id="cantidadProducto_${contadorTr}"></td>
                    <td class="numero" >$ <span id="descuento_${contadorTr}">0</span></td>
                    <td class="numero"><input onchange="calcularIva(${contadorTr})" class="form-check-input" type="checkbox" value="1" name="checkIVA_${contadorTr}" id="checkIVA_${contadorTr}"></td> <!-- CHECK IVA -->
                    <td class="numero" >$ <span id="valor_${contadorTr}">0</span></td>
                    <td class="numero" >$ <span id="vIva_${contadorTr}">0</span></td>
                    <td class="numero" >$ <span id="totalPrecio_${contadorTr}">0</span></td>
                    <td class="numero" >$ <span id="pvpNeto_${contadorTr}">0</span></td>
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



        // MUESTRA UNA BUSQUEDA DE DATOS CON UN MODAL
        const mostrarModal = (event) => {

            console.log(event.target.value);

            contenedorModal.innerHTML = `
                <div class="subContenedorModal" id="subContenedorModal">
                
                    <form action="" id="formularioModal" class="formularioModal">

                        <!-- TITULO -->
                        <div class="contenedorTituloModal">
                            <h2>Productos</h2>
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
                                    <th scope="col">otros</th>
                                    </tr>
                                </thead>

                                <tbody>

                                    <tr>
                                        <td scope="row"><input class="form-check-input" type="radio" name="productoModal" id="productoModal"></td>
                                        <th scope="row"><label for="productoModal">1</label></th>
                                        <td><label for="productoModal">Carro a control</label></td>
                                        <td><label for="productoModal">$5.00</label></td>
                                        <td><label for="productoModal">...</label></td>
                                    </tr>


                                    <tr>
                                        <td scope="row"><input class="form-check-input" type="radio" name="productoModal" id="productoModal"></td>
                                        <th scope="row"><label for="productoModal">1</label></th>
                                        <td><label for="productoModal">Carro a control</label></td>
                                        <td><label for="productoModal">$5.00</label></td>
                                        <td><label for="productoModal">...</label></td>
                                    </tr>

                                    <tr>
                                        <td scope="row"><input class="form-check-input" type="radio" name="productoModal" id="productoModal"></td>
                                        <th scope="row"><label for="productoModal">1</label></th>
                                        <td><label for="productoModal">Carro a control</label></td>
                                        <td><label for="productoModal">$5.00</label></td>
                                        <td><label for="productoModal">...</label></td>
                                    </tr>


                                    <tr>
                                        <td scope="row"><input class="form-check-input" type="radio" name="productoModal" id="productoModal"></td>
                                        <th scope="row"><label for="productoModal">1</label></th>
                                        <td><label for="productoModal">Carro a control</label></td>
                                        <td><label for="productoModal">$5.00</label></td>
                                        <td><label for="productoModal">...</label></td>
                                    </tr>


                                    <tr>
                                        <td scope="row"><input class="form-check-input" type="radio" name="productoModal" id="productoModal"></td>
                                        <th scope="row"><label for="productoModal">1</label></th>
                                        <td><label for="productoModal">Carro a control</label></td>
                                        <td><label for="productoModal">$5.00</label></td>
                                        <td><label for="productoModal">...</label></td>
                                    </tr>

                                    <tr>
                                        <td scope="row"><input class="form-check-input" type="radio" name="productoModal" id="productoModal"></td>
                                        <th scope="row"><label for="productoModal">1</label></th>
                                        <td><label for="productoModal">Carro a control</label></td>
                                        <td><label for="productoModal">$5.00</label></td>
                                        <td><label for="productoModal">...</label></td>
                                    </tr>


                                    <tr>
                                        <td scope="row"><input class="form-check-input" type="radio" name="productoModal" id="productoModal"></td>
                                        <th scope="row"><label for="productoModal">1</label></th>
                                        <td><label for="productoModal">Carro a control</label></td>
                                        <td><label for="productoModal">$5.00</label></td>
                                        <td><label for="productoModal">...</label></td>
                                    </tr>

                                    <tr>
                                        <td scope="row"><input class="form-check-input" type="radio" name="productoModal" id="productoModal"></td>
                                        <th scope="row"><label for="productoModal">1</label></th>
                                        <td><label for="productoModal">Carro a control</label></td>
                                        <td><label for="productoModal">$5.00</label></td>
                                        <td><label for="productoModal">...</label></td>
                                    </tr>


                                    <tr>
                                        <td scope="row"><input class="form-check-input" type="radio" name="productoModal" id="productoModal"></td>
                                        <th scope="row"><label for="productoModal">1</label></th>
                                        <td><label for="productoModal">Carro a control</label></td>
                                        <td><label for="productoModal">$5.00</label></td>
                                        <td><label for="productoModal">...</label></td>
                                    </tr>


                                    <tr>
                                        <td scope="row"><input class="form-check-input" type="radio" name="productoModal" id="productoModal"></td>
                                        <th scope="row"><label for="productoModal">1</label></th>
                                        <td><label for="productoModal">Carro a control</label></td>
                                        <td><label for="productoModal">$5.00</label></td>
                                        <td><label for="productoModal">...</label></td>
                                    </tr>
                                    
                                </tbody>

                            </table>

                        </div>


                        <!-- BOTONES -->
                        <div class="row">
                            <div class="btn btn-danger col-6" onclick="cerrarModal()">CANCELAR</div>
                            <div class="btn botonConfirmarModal col-6">REGISTRAR</div>

                        </div>
                    </form>

                </div>
            `

        }


        // MUESTRA LOS DATOS EN LA TABLA PRINCIPAL
        const mostrarDatosTabla = (id_producto, contador) => {

            console.log(contador);


            // capturar el tr que envio la informacion
            let detalle_producto = document.getElementById(`detalle_producto_${contador}`)
            let lPrecio = document.getElementById(`lPrecio_${contador}`)
            let pvpNeto = document.getElementById(`pvpNeto_${contador}`)
            // let promo = document.getElementById(`promo_${contador}`)     //por el momento no lo necesito


            let FD = new FormData()

            FD.append('id_producto', id_producto)

            fetch('queryDatosTabla.php', {
                    method: 'POST',
                    body: FD
                })
                .then(res => res.json())
                .then(data => {

                    // MOSTRAR LOS DATOS EN EL TR
                    detalle_producto.value = data.detalleProducto
                    lPrecio.innerHTML = data.precio
                    pvpNeto.innerHTML = data.precio
                    // promo.innerHTML = data.promo //por el momento no lo necesito
                })
                .finally()
        }


        // CALCULAR EL IVA CUANDO EL INPUT CHECK CAMBIA
        const calcularIva = (contador) => {

            // capturar datos
            let inputIvaCheck = document.getElementById(`checkIVA_${contador}`).checked //input check iva
            let lPrecio = document.getElementById(`lPrecio_${contador}`).textContent //precio de el producto
            let cantidadProducto = document.getElementById(`cantidadProducto_${contador}`).value //cantidad a comprar
            let vIva = document.getElementById(`vIva_${contador}`) //mostrar el iva en la tabla

            if (lPrecio == '0') {
                document.getElementById(`checkIVA_${contador}`).checked = false
                alertaPersonalizada('ERROR', 'Sin producto ingresado', 'error', 'Regresar', '')
                return
            }


            // calcular el precio final al consumidor (esto se ejecuta si cambia el check del iva)
            precioFinalConsumidor()

            let precioIvaTmp = 0

            if (inputIvaCheck) {

                // valor con iva
                precioIvaTmp = lPrecio * cantidadProducto
                precioIvaTmp = precioIvaTmp + (precioIvaTmp * iva)


            } else {

                // valor sin iva
                precioIvaTmp = lPrecio * cantidadProducto


            }

            vIva.innerHTML = precioIvaTmp
        }


        // CALCULAR EL PRECIO total precio (tabla)
        const calcularTotalPrecio = (contador) => {

            // capturar datos
            let inputIvaCheck = document.getElementById(`checkIVA_${contador}`).checked //input check iva
            let lPrecio = document.getElementById(`lPrecio_${contador}`).textContent //precio de el producto
            let cantidadProducto = document.getElementById(`cantidadProducto_${contador}`).value //cantidad a comprar
            let vIva = document.getElementById(`vIva_${contador}`) //mostrar el iva en la tabla




        }


        // CALCULAR EL PRECIO FINAL PARA EL CONSUMIDOR
        const precioFinalConsumidor = () => {

            for (let i = 0; i < contarTr(); i++) {

                let totalPrecio = document.getElementById(`totalPrecio_${i}`).textContent
                console.log(totalPrecio);
            }

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


        // LOGIA HEADER AMBURGUESA
        const togleBar = () => {

            aside.classList.toggle('asideActive')

            if (aside.classList.contains('asideActive')) {
                imagenBar.src = '../../imagenes/cerrarBar.png'

            } else {
                imagenBar.src = '../../imagenes/bar.png'

            }


        }
    </script>

</body>

</html>