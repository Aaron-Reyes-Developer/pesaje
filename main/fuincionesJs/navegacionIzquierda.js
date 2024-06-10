let handelListaInforme = true


function navegacionIzquierda(url, urlImagen, navActivo) {

    return `

        <div class="contenedorLogo">
            <a href="${url}main.php">
                <img src="${urlImagen}imagenes/logos/logoEmpresa.jpg" alt="">
            </a>
            <img class="imagenBarAside" onclick="togleBar()" src="${urlImagen}imagenes/cerrarBar.png" width="35px" alt="">
        </div>


        <nav class="navegacionAside">

            <ul>
                <li class="${navActivo == 'agregarPersona' ? 'active' : ''}"><a href="${url}agregarPersonas/agregarPersona.php">Agregar Personas</a></li>
                <li class="${navActivo == 'factura' ? 'active' : ''}"><a href="${url}factura/factura.php">Factura</a></li> 
                <li class="${navActivo == 'pesajes' ? 'active' : ''}"><a href="${url}pesajes/pesajes.php">Pesajes</a></li>
                <li style="cursor: pointer;" onclick="mostrarListaInforme('${url}', '${urlImagen}', '${navActivo}')">Informes <img id="iamgenFlecha" src="${urlImagen}imagenes/down.png" width="25px" alt=""></li>

            </ul>

            <ul class="listaInformes" id="listaInformes"></ul>

        </nav>
    
    
    `

}

// mostrar las demas lista de informes
const mostrarListaInforme = (url, urlImagen, navActivo) => {

    let listaInformes = document.getElementById('listaInformes')
    let iamgenFlecha = document.getElementById('iamgenFlecha')
    

    if (handelListaInforme) {

        listaInformes.innerHTML = `
            
            <li class="${navActivo == 'informeOrdenCompra' ? 'active' : ''}"><a href="${url}informeFacturacion/informeOrdenCompra.php">Orden Compra</a> </li>
            <li class="${navActivo == 'Informe 2' ? 'active' : ''}"><a href="${url}">Informe 2</a> </li>
            <li class="${navActivo == 'Informe 3' ? 'active' : ''}"><a href="${url}">Informe 3</a> </li>
            <li class="${navActivo == 'Informe 4' ? 'active' : ''}"><a href="${url}">Informe 4</a> </li>
            
        `

        iamgenFlecha.src = `${urlImagen}imagenes/up.png`
        handelListaInforme = false

    } else {

        iamgenFlecha.src = `${urlImagen}imagenes/down.png`

        listaInformes.innerHTML = ''
        handelListaInforme = true
    }


}