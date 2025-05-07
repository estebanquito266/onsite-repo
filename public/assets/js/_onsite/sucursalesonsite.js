$(document).ready(function () {

    //---------------------------------------------------------------------------//		
    $("#sucursalOnsiteForm").validate({
        rules: {
            codigo_sucursal: 'required', 
            razon_social: 'required', 
            empresa_onsite_id: 'required', 
            localidad_onsite_id: 'required',
            telefono_contacto: {
                required: true,
                number: true
            },
        },
        messages: {
            codigo_sucursal: 'Por favor, ingrese un codigo_sucursal', 
            razon_social: 'Por favor, ingrese un razon_social', 
            empresa_onsite_id: 'Por favor, ingrese un empresa_onsite_id', 
            localidad_onsite_id: 'Por favor, ingrese un localidad_onsite_id', 
            telefono_contacto: 'El campo telefono_contacto debe ser numerico',
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
});
