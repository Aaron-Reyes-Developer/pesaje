<?php

session_start();

if (!isset($_SESSION['usuario'])) {
    header('Location: ../index.php');
    die();
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0C0C0C">
    <link rel="icon" href="../imagenes/icono.ico" type="image/x-icon">


    <!-- BOOSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    <!-- CSS -->
    <link rel="stylesheet" href="estiloMain.css">


    <title>BrainSoft</title>
</head>

<body style="background-color: #24242C;">

    <!-- IZQUERDA -->
    <aside class="aside" id="aside">

        <div class="contenedorLogo">
            <img src="../imagenes/logos/logoEmpresa.jpg" alt="">
            <img class="imagenBarAside" onclick="togleBar()" src="../imagenes/cerrarBar.png" width="35px" alt="">

        </div>


        <nav class="navegacionAside">

            <ul>
                <li class=""><a href="./agregarPersonas/agregarPersona.php">Agregar Personas</a></li>
                <!-- <li><a href="./proforma/proforma.php">Proforma</a></li> -->
                <li><a href="./pesajes/pesajes.php">Pesajes</a></li>
                <li><a href="./informeFacturacion/informeOrdenCompra.php">Informes</a></li>
            </ul>

        </nav>

    </aside>


    <main>

        <!-- HEADER -->
        <header class="header">

            <ul>
                <li class="liBar"><img id="imagenBar" onclick="togleBar()" src="../imagenes/bar.png" width="35px" alt=""></li>
                <li><a href="">Configuración</a></li>
                <li>

                </li>

            </ul>

            <a href="../cerrarSesion.php" class="salir">Salir</a>
        </header>


        <!-- CUERPO -->
        <section class="cuerpo">
            <img src="../imagenes/logos/logo portada.png" alt="">
        </section>

    </main>


    <script>
        let aside = document.getElementById('aside')
        let imagenBar = document.getElementById('imagenBar')

        const togleBar = () => {

            aside.classList.toggle('asideActive')

            if (aside.classList.contains('asideActive')) {
                imagenBar.src = '../imagenes/cerrarBar.png'

            } else {
                imagenBar.src = '../imagenes/bar.png'

            }


        }
    </script>

</body>

</html>