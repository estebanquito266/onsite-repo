$(document).ready(function(){
	//terminal ONSITE
    $( "#terminalOnsiteForm" ).validate( {
        rules: {
            nro: "required",
            razon_social: "required",
            id_localidad: "required",

            empresa_onsite_id : "required",
            sucursal_onsite_id: "required",
        },
        messages: {
            nro: "Por favor, ingrese un nro o código",
            razon_social: "Por favor, ingrese una razón social",
            id_localidad: "Por favor, seleccione una localidad",

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


    $("#terminal_empresa_onsite_id").change(function () { // p/buscar
        limpiarSucursal();
		//getSucursalesTerminales();

	});

});

function limpiarSucursal() {
	$("#terminal_sucursal_onsite_id").empty();
}
