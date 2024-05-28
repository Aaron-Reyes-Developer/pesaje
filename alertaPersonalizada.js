function alertaPersonalizada(titulo, texto, icono, TextoBotonregresar, refrescar) {
    Swal.fire({
        title: titulo,
        text: texto,
        icon: icono,
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        allowEnterKey: false,
        allowEscapeKey: false,
        allowOutsideClick: false,
        confirmButtonText: TextoBotonregresar
    }).then((result) => {
        if (result.isConfirmed) {

            if(refrescar == 'recargar'){
                window.location.reload()
            }else if(refrescar == 'regresar'){
                window.history.back()
            }
            
        }
    });
}