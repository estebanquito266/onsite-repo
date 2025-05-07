$(document).ready(function(){
	//sistema ONSITE
    $( "#sistemaOnsiteForm" ).validate( {
        rules: {
            nombre: "required",
            empresa_onsite_id : "required",
            sucursal_onsite_id: "required",
        },
        messages: {
            nombre: "Por favor, ingrese un nombre",
            empresa_onsite_id : "Por favor, seleccione una empresa",
            sucursal_onsite_id: "Por favor, seleccione una sucursal",

        },
        errorElement: "em",
        errorPlacement: function ( error, element ) {
            // Add the `invalid-feedback` class to the error element
            error.addClass( "invalid-feedback" );
            if ( element.prop( "type" ) === "checkbox" ) {
                error.insertAfter( element.next( "label" ) );
            } else {
                error.insertAfter( element );
            }
        },
        highlight: function ( element, errorClass, validClass ) {
            $( element ).addClass( "is-invalid" ).removeClass( "is-valid" );
        },
        unhighlight: function (element, errorClass, validClass) {
            $( element ).addClass( "is-valid" ).removeClass( "is-invalid" );
        }
    } );



    $("#empresa_onsite_id").change(function () { // p/buscar
        limpiarSucursal();
		getSucursalesOnsite();

	});

    $('#botonEliminar').on('click', function (event) {
        event.preventDefault();
        let id = $(this).data('sistema_id');
        let nombre = $(this).data('sistema_nombre');
        let unidades = $(this).data('sistema_unidades');
        $('#modalConfirmacionTitle').html('Eliminar sistema');
        $('.subtitleModalConfirmacion').html(nombre);
        $('.bodymodalConfirmacion').html('¿Desea eliminar este sistema?'+(unidades ? '<br/> El sistema cuenta con unidades cargadas.' : ''));        
        $("#modalConfirmacion").modal('toggle');
    });

    $('#aceptarmodalConfirmacion').on('click', function () {
        $('#formEliminar').trigger("submit");
    });

    $('#cerrarmodalConfirmacion').on('click', function () {
        $("#modalConfirmacion").modal('toggle');
    });      

});

function getSucursalesOnsite() {
    
    var idEmpresaOnsite = $("#empresa_onsite_id").val();

    var route = "/buscarSucursalesOnsite/" + idEmpresaOnsite ;

    $.get(route, function (response, state) {

        limpiarSucursal(); // al buscar, antes, limpio selects y deshabilito botones

        if (response.length <= 0)
            $("#sucursal_onsite_id").append("<option selected='selected' value=''>Sucursal Onsite no encontrada</option>");

        if (response.length > 1)
            $("#sucursal_onsite_id").append("<option selected='selected' value=''>Seleccione la sucursal onsite - </option>");

        for (i = 0; i < response.length; i++) {
            $("#sucursal_onsite_id").append("<option value='" + response[i].id + "'> " + response[i].razon_social + " [" + response[i].codigo_sucursal + "] " + "</option>");
        }


    });
}
function limpiarSucursal() {
	$("#sucursal_onsite_id").empty();
}



function storeSistema(){
$("#storeModalSistema").click(function(){ // si dentro del modal hace click en guardar, guarda datos		
 
    var datosTerminales = $("#datosSistema").serialize();
    var route = "/sistemaOnsite";
    //var token = $("#token").val();
   
    
    $.ajax({
        url: route,
        //headers: {'X-CSRF-TOKEN': token},
        type: 'POST',
        dataType: 'json',
        data: datosSistemas,
        success: function(data){					
            $("#modalSistemas").modal('toggle');				
            $("#id_terminal_reparacion").empty();				
            $("#id_terminal_reparacion").append("<option value='"+data.idTerminal+"'> "+data.nombreTerminal+ "</option>" );				
            $('#editarTerminal').prop('disabled', false); //habilito para editar								
        },
        error: function(data){				
            var mensajeErrorTerminal = 'Ha ocurrido un error en los siguientes campos: <br>';
            $.each( JSON.parse(data.responseText) , function(key, value){
                mensajeErrorTerminal = mensajeErrorTerminal + key+': '+value+' <br>';
            });				
            $('#mensaje-error-terminal').css('display','block');
            $('#mensaje-error-terminal').html(mensajeErrorTerminal);
        }
    });
    
} );
}