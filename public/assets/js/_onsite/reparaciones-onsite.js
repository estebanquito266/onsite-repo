$(function () {



	$('#empresa_instaladora_id').on('change', function () {

		idEmpresa = $(this).find(':selected').val();
		getObrasPorEmpresa(idEmpresa);
	});

	//-----------------------------------------------------------------------//			

	$(".justificado").click(function () {
		var idReparacionOnsite = event.target.id;
		var justificado = $(this).prop("checked");

		var route = "/reparacionOnsiteJustificado/" + idReparacionOnsite;
		var token = $("input[name=_token]").val();

		if (justificado)
			justificado = 1;
		else
			justificado = 0;
		//alert(idReparacionOnsite + ' ' +justificado);

		$.ajax({
			url: route,
			headers: { 'X-CSRF-TOKEN': token },
			type: 'POST',
			dataType: 'json',
			data: { 'justificado': justificado },
			success: function (data) {
				console.log('Reparación Onsite --> justficado: registrado correctamente!');
			},
			error: function (data) {
				console.log('Reparación Onsite --> error al intentar registrar: justficado!');
			}
		});
	});

	//-----------------------------------------------------------------------//			

	$(".fechavencimiento").focusout(function () {
		var idReparacionOnsite = event.target.id;
		var fechaVencimiento = event.target.value;

		var route = "/reparacionOnsiteFechaVencimiento/" + idReparacionOnsite;
		var token = $("input[name=_token]").val();

		//alert(idReparacionOnsite + ' ' + monto + ' ' + token);

		$.ajax({
			url: route,
			headers: { 'X-CSRF-TOKEN': token },
			type: 'POST',
			dataType: 'json',
			data: { 'fecha_vencimiento': fechaVencimiento },
			success: function (data) {
				console.log('Reparación Onsite --> fechaVencimiento: registrado correctamente!');
			},
			error: function (data) {
				console.log('Reparación Onsite --> error al intentar registrar: fechaVencimiento!');
			}
		});
	});


	$('.datepicker').datepicker({
		language: "es",
	});


	//-----------------------------------------------------------------------//			

	$(".monto").focusout(function () {
		var idReparacionOnsite = event.target.id;
		var monto = event.target.value;

		var route = "/reparacionOnsiteMonto/" + idReparacionOnsite;
		var token = $("input[name=_token]").val();

		//alert(idReparacionOnsite + ' ' + monto + ' ' + token);

		$.ajax({
			url: route,
			headers: { 'X-CSRF-TOKEN': token },
			type: 'POST',
			dataType: 'json',
			data: { 'monto': monto },
			success: function (data) {
				console.log('Reparación Onsite --> monto: registrado correctamente!');
			},
			error: function (data) {
				console.log('Reparación Onsite --> error al intentar registrar: monto!');
			}
		});
	});
	//-----------------------------------------------------------------------//	

	$(".montoextra").focusout(function () {
		var idReparacionOnsite = event.target.id;
		var montoExtra = event.target.value;

		var route = "/reparacionOnsiteMontoExtra/" + idReparacionOnsite;
		var token = $("input[name=_token]").val();

		//alert(idReparacionOnsite + ' ' + monto + ' ' + token);

		$.ajax({
			url: route,
			headers: { 'X-CSRF-TOKEN': token },
			type: 'POST',
			dataType: 'json',
			data: { 'monto_extra': montoExtra },
			success: function (data) {
				console.log('Reparación Onsite --> monto extra: registrado correctamente!');
			},
			error: function (data) {
				console.log('Reparación Onsite --> error al intentar registrar: monto extra!');
			}
		});
	});

});

function getObrasPorEmpresa(idEmpresa) {
	var rutaModelos = "/getObrasPorEmpresa/" + idEmpresa;


	$.get(rutaModelos, function (response) {

		$("#sistema_onsite_id").html('');

		if (response.length <= 0)
			$("#sistema_onsite_id").append("<option selected='selected' value=''>Modelos no encontradas</option>");
		else {
			$("#sistema_onsite_id").append("<option selected='selected' value=''>Seleccione el modelo - </option>");

			for (i = 0; i < response.length; i++) {
				$("#sistema_onsite_id").append("<optgroup label='Obra: " + response[i].nombre + "' >");

				if (response[i].sistema_onsite.length > 0) {

					for (j = 0; j < response[i].sistema_onsite.length; j++) {
						$("#sistema_onsite_id").append(
							"<option value="
							+ response[i].sistema_onsite[j].id
							+ " data-idobra="
							+ response[i].sistema_onsite[j].obra_onsite_id
							+ " data-nombre_sistema='"
							+ response[i].sistema_onsite[j].nombre
							+ "'>"
							+ response[i].sistema_onsite[j].nombre
							+ '<small> (Obra: '
							+ response[i].nombre
							+ ')</small>'
							+ "</option>");
					}
				}
			};

		};


	});
}