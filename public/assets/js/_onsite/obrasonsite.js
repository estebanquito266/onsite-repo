$(document).ready(function () {

    //---------------------------------------------------------------------------//		


    $("#obraOnsiteForm").validate({
        rules: {
            nombre: 'required',
            estado: 'required',  

        },
        messages: {
            nombre: 'Por favor, ingrese un nombre', 
            estado: 'Por favor, ingrese un estado', 

        },
        errorElement: "em",
        errorPlacement: function (error, element) {
            // Add the `invalid-feedback` class to the error element
            error.addClass("invalid-feedback");
            if (element.prop("type") === "checkbox") {
                error.insertAfter(element.next("label"));
            } else {
                error.insertAfter(element);
            }
        },
        highlight: function (element, errorClass, validClass) {
            $(element).addClass("is-invalid").removeClass("is-valid");
        },
        unhighlight: function (element, errorClass, validClass) {
            $(element).addClass("is-valid").removeClass("is-invalid");
        }
    });  
	
    //---------------------------------------------------------------------------//	


    $('#botonEliminar').on('click', function (event) {
        event.preventDefault();
        let id = $(this).data('obra_id');
        let nombre = $(this).data('obra_nombre');
        let msj = $(this).data('obra_msj');
        $('#modalConfirmacionTitle').html('Eliminar obra');
        $('.subtitleModalConfirmacion').html(nombre);
        $('.bodymodalConfirmacion').html('¿Desea eliminar esta obra?<br/><br/>'+msj);        
        $("#modalConfirmacion").modal('toggle');
    });

    $('#aceptarmodalConfirmacion').on('click', function () {
        $('#formEliminar').trigger("submit");
    });

    $('#cerrarmodalConfirmacion').on('click', function () {
        $("#modalConfirmacion").modal('toggle');
    }); 

});
