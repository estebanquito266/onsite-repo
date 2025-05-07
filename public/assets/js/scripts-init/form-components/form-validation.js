// Form Validation

$( document ).ready(function() {

    $( "#signupForm" ).validate( {
        rules: {
            firstname: "required",
            lastname: "required",
            username: {
                required: true,
                minlength: 2
            },
            password: {
                required: true,
                minlength: 5
            },
            confirm_password: {
                required: true,
                minlength: 5,
                equalTo: "#password"
            },
            email: {
                required: true,
                email: true
            },
            agree: "required"
        },
        messages: {
            firstname: "Please enter your firstname",
            lastname: "Please enter your lastname",
            username: {
                required: "Please enter a username",
                minlength: "Your username must consist of at least 2 characters"
            },
            password: {
                required: "Please provide a password",
                minlength: "Your password must be at least 5 characters long"
            },
            confirm_password: {
                required: "Please provide a password",
                minlength: "Your password must be at least 5 characters long",
                equalTo: "Please enter the same password as above"
            },
            email: "Please enter a valid email address",
            agree: "Please accept our policy"
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
	
	//REPARACIONES ONSITE
    $( "#reparacionesOnsiteForm" ).validate( {
        rules: {
            //clave: "required",
            id_terminal: "required",
            id_empresa_onsite: "required",
            id_tipo_servicio: "required",
            id_estado: "required",
            sucursal_onsite_id: "required",
        },
        messages: {
            //clave: "Por favor, ingrese una clave",
            id_terminal: "Por favor, seleccione una terminal",
            id_empresa_onsite: "Por favor, seleccione una empresa",
            id_tipo_servicio: "Por favor, seleccione un tipo de servicio",
            id_estado: "Por favor, seleccione un estado",
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
	
	
	//SLA ONSITE
    $( "#slaOnsiteForm" ).validate( {
        rules: {
            codigo: "required",
            id_tipo_servicio: "required",
            id_nivel: "required",
            horas: "required"
        },
        messages: {
            codigo: "Por favor, ingrese un codigo",
            id_tipo_servicio: "Por favor, seleccione un tipo de servicio",
            id_nivel: "Por favor, seleccione un nivel",
            horas: "Por favor, ingrese la cantidad de horas",
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
	
	
	//LOCALIDAD ONSITE
    $( "#localidadOnsiteForm" ).validate( {
        rules: {
            id_provincia: "required",
            localidad: "required",
            id_nivel: "required",
            id_usuario_tecnico: "required",
            codigo: {
                required: true,
                number: true,
            },
            atiende_desde: "required",
        },
        messages: {
            id_provincia: "Por favor, seleccione una provincia",
            localidad: "Por favor, ingrese una localidad",
            id_nivel: "Por favor, seleccione un nivel",
            id_usuario_tecnico: "Por favor, seleccione un técnico",
            codigo: {
                required: "Por favor, ingrese el código",
                number: "El código debe ser numérico"
            },
            atiende_desde: "Por favor, igrese el valor",
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
	
	
		

});

