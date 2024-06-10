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
    <link rel="stylesheet" href="./estiloPersona.css">
    <link rel="stylesheet" href="../estiloLoader.css">
    <link rel="stylesheet" href="../../estiloAlerta.css">

    <!-- ALERTA -->
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
                <h1>Agregar Personas</h1>
            </div>



            <form action="" id="formulario" class="row mt-4 formulario">

                <div class="mb-3 col-lg-6 col-sm-12">
                    <label for="tipo" class="form-label">Tipo*</label>
                    <select class="form-select" id="tipo" name="tipo" aria-label="Default select example" required>
                        <option value="C">Cliente</option>
                        <option value="P">Proveedor</option>
                    </select>
                </div>


                <div class="mb-3 col-lg-6 col-sm-12">
                    <label for="identificacion" class="form-label">Identificacion*</label>
                    <input onchange="datosWebService(event.target.value)" type="number" class="form-control" id="identificacion" name="identificacion" placeholder="" required>
                </div>


                <div class="mb-3 col-lg-6 col-sm-12">
                    <label for="nombres" class="form-label">Nombres*</label>
                    <input type="text" class="form-control" id="nombres" name="nombres" placeholder="" required>
                </div>


                <div class="mb-3 col-lg-6 col-sm-12">
                    <label for="direccion" class="form-label">Direccion</label>
                    <input type="text" class="form-control" id="direccion" name="direccion" placeholder="">
                </div>


                <div class="mb-3 col-lg-6 col-sm-12">
                    <label for="telefono" class="form-label"># Telefono</label>
                    <input type="text" class="form-control" id="telefono" name="telefono" placeholder="">
                </div>



                <div class="mb-3 col-lg-6 col-sm-12">
                    <label for="institucion" class="form-label">Institución</label>
                    <input type="text" class="form-control" id="institucion" name="institucion" placeholder="">
                </div>

                <div class="mb-3 col-lg-6 col-sm-12">
                    <label for="ciudad" class="form-label">Ciudad</label>
                    <input type="text" class="form-control" id="ciudad" name="ciudad" placeholder="">
                </div>

                <div class="mb-3 col-12">
                    <div class="form-control botonSubmit" id="cambiar" style="text-align: center;" onclick="guardarDatos()">Guardar</div>
                </div>

            </form>


        </section>

    </main>


    <!-- ALERTA PERSONALIZADA -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>
    <script src="../../alertaPersonalizada.js"></script>


    <script src="../fuincionesJs/navegacionIzquierda.js"></script>


    <script>
        // DATOS PARA LA NAVEGACION
        let aside = document.getElementById('aside')
        let imagenBar = document.getElementById('imagenBar')
        let url = '../'
        let urlImagen = '../../'
        let navActivo = 'agregarPersona'
        aside.innerHTML = navegacionIzquierda(url, urlImagen, navActivo)

        const togleBar = () => {

            aside.classList.toggle('asideActive')

            if (aside.classList.contains('asideActive')) {
                imagenBar.src = '../../imagenes/cerrarBar.png'

            } else {
                imagenBar.src = '../../imagenes/bar.png'

            }


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



        // MOSTRAR LOS DATOS DESDE WEB SERVICE
        const datosWebService = (dato) => {

            loader()

            // OBETENER LOS INPUST
            let nombres = document.getElementById('nombres')
            let direccion = document.getElementById('direccion')
            let telefono = document.getElementById('telefono')
            let institucion = document.getElementById('institucion')
            let ciudad = document.getElementById('ciudad')



            let url = `http://factura.omegas-apps.com:3001/administracion/getCedula/${dato}`

            fetch(url)
                .then(res => res.json())
                .then(data => {

                    console.log(data);

                    if (data.status) {
                        nombres.value = data.value.persona
                        direccion.value = data.value.direccion
                        ciudad.value = data.value.lugar_nacimiento
                    }

                })
                .finally(() => {
                    cerrarLoader()
                })
        }



        const guardarDatos = () => {

            let formulario = document.getElementById('formulario')

            loader()

            let FD = new FormData(formulario)


            fetch('queryGuardarPersona.php', {
                    method: 'POST',
                    body: FD
                })
                .then(res => res.json())
                .then(data => {

                    if (data.mensaje == 'dato vacio') {
                        alertaPersonalizada('ERROR', 'Nombre Vacio', 'info', 'Regresar', '')
                        return
                    }


                    if (data.mensaje == 'existe') {
                        alertaPersonalizada('ERROR', 'Ya existe el cliente', 'error', 'Regresar', '')
                        return
                    }


                    if (data.mensaje == 'ok') {
                        alertaPersonalizada('CORRECTO', 'Agregado Correctamente', 'success', 'Regresar', 'recargar')
                    }

                })
                .finally(() => cerrarLoader())

        }
    </script>

</body>

</html>