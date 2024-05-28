<?php

include_once('./conexion.php');


// QUERY PARA BUSCAR LOS DATOS DE EL LOGIN
$queryLogin = odbc_exec($conn, "SELECT usuario FROM sys_usuarios_sistema ");



?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="theme-color" content="#0C0C0C">
    <link rel="icon" href="./imagenes/icono.ico" type="image/x-icon">

    <!-- BOOSTRAP -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>


    <!-- ALERTA -->
    <link href="https://cdn.jsdelivr.net/npm/@sweetalert2/theme-dark@4/dark.css" rel="stylesheet">


    <!-- CSS -->
    <link rel="stylesheet" href="estiloIndex.css">

    <title>BrianSoft</title>


</head>

<body style="background-color: #24242c;">

    <section class="vh-100">

        <div class="container-fluid">
            <div class="row">

                <div class="col-sm-6 text-black seccionIzquierda">

                    <div class="px-5 ms-xl-4 py-1 contedorLogoEmpresa">

                        <img class="logoImagen" src="./imagenes/logos/logoEmpresa.jpg" width="80px" alt="">

                    </div>

                    <div class="d-flex align-items-center h-custom-2 px-5 ms-xl-4 pt-5 pt-xl-0 mt-xl-n5">

                        <form style="width: 23rem;" id="formulario">

                            <h3 class="fw-normal mb-3 pb-3" style="letter-spacing: 1px;">Log in</h3>


                            <!-- INPUT USUARIO -->
                            <label for="usuario" class="mb-1 text-light">Usuario*</label>
                            <select class="form-select mb-3 bg-dark text-white" id="usuario" name="usuario" aria-label="Default select example">

                                <?php
                                while ($row = odbc_fetch_array($queryLogin)) {
                                ?>
                                    <option value="<?php echo $row['usuario'] ?>"><?php echo $row['usuario'] ?></option>
                                <?php
                                }

                                ?>

                            </select>



                            <!-- INPUT CONTRASEÑA -->
                            <label for="contra" class="mb-1 text-light">Contraseña*</label>
                            <div class="form-floating mb-3 form-outline">
                                <input type="text" class="form-control form-control-lg" id="contra" name="contra" placeholder="asd">
                                <label for="contra"> ● ● ● ● ● ● ● ●</label>
                            </div>


                            <div class="pt-1 mb-4">
                                <button data-mdb-button-init data-mdb-ripple-init class="btn btn-info btn-lg btn-block " type="submit">Login</button>
                            </div>


                            <i>
                                <!-- <p> Todos los derechos reservados <a target="_blank" href="https://wa.me/+593963484856" class="link-info">Aaron Reyes ©</a></p> -->

                            </i>
                        </form>

                    </div>

                </div>
                <div class="col-sm-6 px-0 d-none d-sm-block contenedorPortada">
                    <img src="./imagenes/logos/PortadaLogin.jpg" alt="Login image" class="w-100 vh-100 imagenPortada" style="object-fit: cover; object-position: center;">
                </div>
            </div>
        </div>
    </section>


    <script src="./alertaPersonalizada.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.js"></script>

    <script>
        let formulario = document.getElementById('formulario')

        formulario.addEventListener('submit', function(e) {
            e.preventDefault();

            let FD = new FormData(formulario);


            fetch('queryLogin.php', {
                    method: 'POST',
                    body: FD
                })
                .then(res => res.json())
                .then(data => {

                    if (data.mensaje == 'ok') {
                        window.location.href = './main/main.php';
                    } else {
                        alertaPersonalizada('ERROR', 'No se encontro Resultado', 'info', 'Regresar', '')
                        return
                    }
                })
        })
    </script>

</body>

</html>