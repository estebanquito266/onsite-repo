$(document).ready(function () {

	$("#createSucursal").click(function () {
		limpiarModalFormSucursal();

		var idempresa = $("#id_empresa_onsite").val();//trae valor de input hidden de campos.blade		
		$('.logica-sucursal-modal').html('<input type="hidden" name="empresa_onsite_id" id="empresa_onsite_id" value="' + idempresa + '"></input>');
		$('.logica-localidad-modal').removeClass('col-lg-6 col-md-6');
		$('.logica-localidad-modal').addClass('col-lg-12 col-md-12');
		/* $("#empresa_onsite_id").prop('disabled', true); */
	});

	//---------------------------------------------------------------------------//	

	/*
	$("#createSucursal").click(function () {
		limpiarModalFormSucursal();

		$('#storeModalSucursal').removeClass('d-none');

		$('#updateModalSucursal').addClass('d-none');

		var idEmpresaOnsite = $('#id_empresa_onsite').val();
		console.log('createSucursal - - idEmpresaOnsite: '+idEmpresaOnsite);
	    
		//$('#datosSucursales input[name=nro]').attr('readonly', false);
		$('#datosSucursales select[name=empresa_onsite_id]').val(idEmpresaOnsite);
		$('#datosSucursales select[name=empresa_onsite_id]').attr('readonly', true);

		$('#datosSucursales input[name=empresa_onsite_id]').val(idEmpresaOnsite);
	});
	*/
	//---------------------------------------------------------------------------//	
	$("#storeModalSucursal").click(function () { // si dentro del modal hace click en guardar, guarda datos		
		var datosSucursales = $("#datosSucursales").serialize();
		var route = "/sucursalesOnsite";
		//var token = $("#token").val();

		$.ajax({
			url: route,
			//headers: {'X-CSRF-TOKEN': token},
			type: 'POST',
			dataType: 'json',
			data: datosSucursales,
			success: function (data) {
				$("#modalSucursales").modal('toggle');
				$("#sucursal_onsite_id").empty();
				$("#sucursal_onsite_id").append("<option value='" + data.id + "'> " + data.razon_social + " [" + data.codigo_sucursal + "] </option>");
				$('#editarSucursal').prop('disabled', false); //habilito para editar								
			},
			error: function (data) {
				var mensajeErrorSucursal = 'Ha ocurrido un error en los siguientes campos: <br>';

				$.each(JSON.parse(data.responseText).errors, function (key, value) {
					mensajeErrorSucursal = mensajeErrorSucursal + key + ': ' + value + ' <br>';
				});
				$('#mensaje-error-sucursal').css('display', 'block');
				$('#mensaje-error-sucursal').html(mensajeErrorSucursal);
			}
		});

	});

	//-----------------------------------------------------------------------//		


	$("#buscarSucursalReparacion").click(function () { // p/buscar
		$("#sucursal_onsite_id").empty();
		var idEmpresaOnsite = $("#id_empresa_onsite").val();
		var textoBuscar = $("#textoBuscarSucursal").val();
		var i = 0;
		var route = "/searchSucursalesOnsite/" + idEmpresaOnsite + "/" + textoBuscar;

		$.get(route, function (response, state) {
			deshabilitar(); // al buscar, antes, limpio selects y deshabilito botones

			if (response.length <= 0)
				$("#sucursal_onsite_id").append("<option selected='selected' value=''>Sucursal no encontrada</option>");

			if (response.length >= 1)
				$("#sucursal_onsite_id").append("<option selected='selected' value=''>Seleccione la sucursal - </option>");

			for (i = 0; i < response.length; i++) {
				$("#sucursal_onsite_id").append("<option value='" + response[i].id + "'> " + response[i].razon_social + " [" + response[i].codigo_sucursal + "] - " + response[i].localidad + "</option>");
			}

			if (i == 1) //si es una sola terminal
				$('#editarSucursal').prop('disabled', false);
		});

	});
	//---------------------------------------------------------------------------//

	$("#editSucursal").click(function () { // si hace click en editar obtengo los datos
		$('#storeModalSucursal').addClass('d-none');
		$('#updateModalSucursal').removeClass('d-none');

		var idSucursal = $('#sucursal_onsite_id').val();
		//var route = "/sucursalesOnsite/"+idSucursal+"/edit";
		var route = "/sucursalesOnsite/" + idSucursal;

		limpiarModalFormSucursal();

		$.get(route, function (res) {

			$.each(res, function (key, value) {

				if (key == 'empresa_onsite_id') {
					$('#datosSucursales select[name=empresa_onsite_id]').val(value);
				}
				else if (key == 'localidad_onsite_id') {
					$('#datosSucursales select[name=localidad_onsite_id]').val(value);
				}
				else {
					$('#datosSucursales input[name=' + key + ']').val(value);
				}

			});
		});

		//$('#datosSucursales input[name=id]').attr('readonly', true);

	});

	//---------------------------------------------------------------------------//

	$("#updateModalSucursal").click(function () { // si dentro del modal hace click en guardar, guarda los datos		
		var datosSucursales = $("#datosSucursales").serialize();
		var idSucursal = $('#sucursal_onsite_id').val();

		var route = "/sucursalesOnsite/" + idSucursal;
		//var token = $("#token").val();

		$.ajax({
			url: route,
			//headers: {'X-CSRF-TOKEN': token},
			type: 'PUT',
			dataType: 'json',
			data: datosSucursales,
			success: function (data) {
				$("#modalSucursales").modal('toggle');
				$("#sucursal_onsite_id").empty();
				$("#sucursal_onsite_id").append("<option value='" + data.id + "'> " + data.razon_social + " [" + data.codigo_sucursal + "]</option>");

			},
			error: function (data) {
				var mensajeErrorSucursal = 'Ha ocurrido un error en los siguientes campos: <br>';
				$.each(JSON.parse(data.responseText), function (key, value) {
					mensajeErrorSucursal = mensajeErrorSucursal + key + ': ' + value + ' <br>';
				});

				$('#mensaje-error-sucursal').css('display', 'block');
				$('#mensaje-error-sucursal').html(mensajeErrorSucursal);
			}
		});

	});

	$("#refreshSucursal").click(function () { // p/buscar
		limpiarSucursal();
		getSucursalesTerminales();
	});
	//-----------------------------------------------------------------------//    

});

function getSucursalesTerminales() {
	var idEmpresaOnsite = $("#terminal_empresa_onsite_id").val();

	var route = "/buscarSucursalesOnsite/" + idEmpresaOnsite;

	$.get(route, function (response, state) {

		//limpiar(); // al buscar, antes, limpio selects y deshabilito botones

		if (response.length <= 0)
			$("#terminal_sucursal_onsite_id").append("<option selected='selected' value=''>Sucursal Onsite no encontrada</option>");

		if (response.length > 1)
			$("#terminal_sucursal_onsite_id").append("<option selected='selected' value=''>Seleccione la sucursal onsite - </option>");

		for (i = 0; i < response.length; i++) {
			$("#terminal_sucursal_onsite_id").append("<option value='" + response[i].id + "'> " + response[i].codigo_sucursal + " - " + response[i].razon_social + "</option>");
		}


	});
}