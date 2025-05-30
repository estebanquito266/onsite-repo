$(function () {

    /* evita envío de formulario con tecla enter */
    $('form input').on('keydown', (function (e) {
        if (e.keyCode == 13 || e.keyCode == 10) {
            e.preventDefault();
            return false;
        }
    }));

    $('#cerrarmodalConfirmacion').on('click', (function (e) {
        $('#modalConfirmacion').modal('toggle');  
        setTimeout(() => {
            location.reload();
          }, 1000);      
    }));        

    $('#modalConfirmacion').on('hidden.bs.modal', (function (e) {        
        setTimeout(() => {
            location.reload();
          }, 1000);      
    }));  

    /* arranque y reseteo previo del formulario con loader */
    inicioFormularioSteps();     

    /* carga de esquemas (imagenes o archivos) */
    $(document).on('change', '.file-input', function () {

        var filesCount = $(this)[0].files.length;
        var textbox = $(this).prev();
        if (filesCount === 1) {
            var fileName = $(this).val().split('\\').pop();
            textbox.text(fileName);
        } else {
            textbox.text(filesCount + ' files selected');
        }
    });


    /* click en procesar archivo */
    $('#import_file').on('click', function (e) {
        e.preventDefault();
        storeReparacionesMirgor();
        disabledButton(this);

    });


    /* listado de reparaciones: A Recepcionar */
    $('#a_recepcionar').on('click', function (e) {
        e.preventDefault();
        getReparacionesRecepcionarFunction();
    });
    


});














