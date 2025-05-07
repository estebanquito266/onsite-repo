$(document).ready(function () {



	//HISTORIAL ESTADO ONSITE
	$("#historialEstadoOnsiteForm").validate({
		rules: {
			id_reparacion: "required",
			id_estado: "required",
			fecha: "required",
			id_usuario: "required",
			observacion: "required"
		},
		messages: {
			id_reparacion: "Por favor, seleccione una reparación",
			id_estado: "Por favor, seleccione un estado",
			fecha: "Por favor, ingrese una fecha",
			id_usuario: "Por favor, seleccione un usuario",
			observacion: "Por favor, ingrese una observación"
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

});	