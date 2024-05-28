function alertaPersonalizadaBack(titulo, texto, icono, TextoBotonregresar, retroceder) {
    Swal.fire({
        title: titulo,
        text: texto,
        icon: icono,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        allowEscapeKey: false,
        allowOutsideClick: false,
        confirmButtonText: TextoBotonregresar
    }).then((result) => {
        if (result.isConfirmed) {

            if(retroceder == 'si'){
                window.history.back(1);
            } 
            
            
        }
    });
}