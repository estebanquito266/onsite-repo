$(document).ready(function () {

	//-----------------------------------------------------------------------//		
	$("#createTerminal").click(function () {

		limpiarModalFormTerminal();

		validarGenerarNumeroTerminal();

		var idempresa = $("#id_empresa_onsite").val();//trae valor de input hidden de campos.blade				
		var idsucursal = $("#sucursal_onsite_id").val();		
		console.log('createTerminal - id_empresa_onsite:' + idempresa + ' - sucursal_onsite_id' + idsucursal);

		$('.logica-terminal-empresa-modal').html('<input type="hidden" name="empresa_onsite_id" id="terminal_empresa_onsite_id" value="' + idempresa + '"></input>');
		$('.logica-terminal-sucursal-modal').html('<input type="hidden" name="sucursal_onsite_id" id="terminal_sucursal_onsite_id" value="' + idsucursal + '"></input>');

	});

	//---------------------------------------------------------------------------//	

	/*
	$("#createTerminal").click(function () {
		limpiarModalFormTerminal();

		validarGenerarNumeroTerminal();

		$("#terminal_empresa_onsite_id").val($("#id_empresa_onsite").val());
		getSucursalesTerminales();

		$('#storeModalTerminal').removeClass('d-none');

		$('#updateModalTerminal').addClass('d-none');

		var idEmpresaOnsite = $('#id_empresa_onsite').val();
		var idSucursalOnsite = $('#sucursal_onsite_id').val();
		console.log('createTerminal - - idEmpresaOnsite: '+idEmpresaOnsite+' - idSucursalOnsite:'+idSucursalOnsite);

		//$('#datosSucursales input[name=nro]').attr('readonly', false);
		$('#datosTerminales select[name=empresa_onsite_id]').val(idEmpresaOnsite);
		$('#datosTerminales select[name=empresa_onsite_id]').attr('readonly', true);

		$('#datosTerminales select[name=sucursal_onsite_id]').val(idSucursalOnsite);
		//$('#datosTerminales select[name=sucursal_onsite_id]').attr('readonly', true);

		//$('#datosTerminales input[name=id_localidad]').val(1);
		//$('#datosTerminales input[name=id_localidad]').attr('readonly', true);

		$('#datosTerminales input[name=empresa_onsite_id]').val(idEmpresaOnsite);
		$('#datosTerminales input[name=sucursal_onsite_id]').val(idSucursalOnsite);
	});
	*/

	//---------------------------------------------------------------------------//	
	$("#storeModalTerminal").click(function () { // si dentro del modal hace click en guardar, guarda datos		
		var datosTerminales = $("#datosTerminales").serialize();
		var route = "/terminalOnsite";
		//var token = $("#token").val();
		
		$.ajax({
			url: route,
			//headers: {'X-CSRF-TOKEN': token},
			type: 'POST',
			dataType: 'json',
			data: datosTerminales,
			success: function (data) {
				$("#modalTerminales").modal('toggle');
				$("#id_terminal").empty();
				$("#id_terminal").append("<option value='" + data.nro + "'> " + data.nro + " - " + data.marca + " - " + data.modelo + " - " + data.serie + "</option>");
				$('#editTerminal').prop('disabled', false); //habilito para editar								
			},
			error: function (data) {
				
				var mensajeErrorTerminal = 'Ha ocurrido un error en los siguientes campos: <br>';
				
				$.each(JSON.parse(data.responseText), function (key, value) {
					mensajeErrorTerminal = mensajeErrorTerminal + key + ': ' + value + ' <br>';
				});
				$('#mensaje-error-terminal').css('display', 'block');
				$('#mensaje-error-terminal').html(mensajeErrorTerminal);
			}
		});

	});

	//---------------------------------------------------------------------------//

	$("#editTerminal").click(function () { // si hace click en editar obtengo los datos
		$('#storeModalTerminal').addClass('d-none');
		$('#updateModalTerminal').removeClass('d-none');

		var idTerminal = $('#id_terminal').val();
		var route = "/terminalOnsite/" + idTerminal + "/edit";

		limpiarModalFormTerminal();

		$.get(route, function (res) {

			$.each(res, function (key, value) {

				if (key == 'empresa_onsite_id') {
					$('#datosTerminales select[name=empresa_onsite_id]').val(value);
				}
				else if (key == 'sucursal_onsite_id') {
					$('#datosTerminales select[name=sucursal_onsite_id]').val(value);
				}
				else {
					$('#datosTerminales input[name=' + key + ']').val(value);
				}

			});
		});

		$('#datosTerminales input[name=nro]').attr('readonly', true);

	});

	//---------------------------------------------------------------------------//

	$("#updateModalTerminal").click(function () { // si dentro del modal hace click en guardar, guarda los datos		
		var datosTerminales = $("#datosTerminales").serialize();
		var idTerminal = $('#id_terminal').val();

		var route = "/terminalOnsite/" + idTerminal;
		//var token = $("#token").val();

		$.ajax({
			url: route,
			//headers: {'X-CSRF-TOKEN': token},
			type: 'PUT',
			dataType: 'json',
			data: datosTerminales,
			success: function (data) {
				$("#modalTerminales").modal('toggle');
				$("#id_terminal").empty();
				$("#id_terminal").append("<option value='" + data.nro + "'> " + data.nro + " - " + data.marca + " - " + data.modelo + " - " + data.serie + "</option>");

			},
			error: function (data) {
				var mensajeErrorTerminal = 'Ha ocurrido un error en los siguientes campos: <br>';
				$.each(JSON.parse(data.responseText), function (key, value) {
					mensajeErrorTerminal = mensajeErrorTerminal + key + ': ' + value + ' <br>';
				});

				$('#mensaje-error-terminal').css('display', 'block');
				$('#mensaje-error-terminal').html(mensajeErrorTerminal);
			}
		});

	});

	$("#refreshSucursal").click(function () { // p/buscar
		limpiarSucursal();
		getSucursalesTerminales();
	});

	$("#buscarTerminalReparacion").click(function () { // p/buscar
		var textoBuscar = $("#textoBuscarTerminal").val();
		var i = 0;
		var route = "/buscarTerminalesOnsite/" + textoBuscar;

		$.get(route, function (response, state) {

			deshabilitar(); // al buscar, antes, limpio selects y deshabilito botones

			if (response.length <= 0)
				$("#id_terminal_reparacion").append("<option selected='selected' value=''>Terminal no encontrada</option>");

			if (response.length > 1)
				$("#id_terminal_reparacion").append("<option selected='selected' value=''>Seleccione la terminal - </option>");

			for (i = 0; i < response.length; i++) {
				$("#id_terminal_reparacion").append("<option value='" + response[i].nro + "'> " + response[i].nombreTerminal + "</option>");
			}

			if (i == 1) //si es una sola terminal
				$('#editarTerminal').prop('disabled', false);
		});

	});

	//---------------------------------------------------------------------------//

	$("#id_terminal_reparacion").change(function () { // si cambia 

		var idTerminal = event.target.value;

		if (idTerminal) {
			$('#editarTerminal').prop('disabled', false);
		}
		else {
			$('#editarTerminal').prop('disabled', true);
			//$("#id_terminal_reparacion").empty();
		}

	});

	//---------------------------------------------------------------------------//	

	$("#agregarTerminal").click(function () {
		limpiarFormTerminal();

		$('#guardarModalTerminal').removeClass('d-none');

		$('#modificarModalTerminal').addClass('d-none');

		$('#datosTerminales input[name=nro]').attr('readonly', false);
	});

	//---------------------------------------------------------------------------//	
	$("#guardarModalTerminal").click(function () { // si dentro del modal hace click en guardar, guarda datos		
		var datosTerminales = $("#datosTerminales").serialize();
		var route = "/terminalOnsite";
		//var token = $("#token").val();

		$.ajax({
			url: route,
			//headers: {'X-CSRF-TOKEN': token},
			type: 'POST',
			dataType: 'json',
			data: datosTerminales,
			success: function (data) {
				$("#modalTerminales").modal('toggle');
				$("#id_terminal_reparacion").empty();
				$("#id_terminal_reparacion").append("<option value='" + data.idTerminal + "'> " + data.nombreTerminal + "</option>");
				$('#editarTerminal').prop('disabled', false); //habilito para editar								
			},
			error: function (data) {
				var mensajeErrorTerminal = 'Ha ocurrido un error en los siguientes campos: <br>';
				$.each(JSON.parse(data.responseText), function (key, value) {
					mensajeErrorTerminal = mensajeErrorTerminal + key + ': ' + value + ' <br>';
				});
				$('#mensaje-error-terminal').css('display', 'block');
				$('#mensaje-error-terminal').html(mensajeErrorTerminal);
			}
		});

	});



	//---------------------------------------------------------------------------//

	$("#editarTerminal").click(function () { // si hace click en editar obtengo los datos
		$('#guardarModalTerminal').addClass('d-none');
		$('#modificarModalTerminal').removeClass('d-none');

		var idTerminal = $('#id_terminal_reparacion').val();
		var route = "/terminalOnsite/" + idTerminal + "/edit";

		limpiarFormTerminal();

		$.get(route, function (res) {

			$.each(res, function (key, value) {

				if (key == 'id_localidad') {
					$('#datosTerminales select[name=id_localidad]').val(value);
				}
				else {
					$('#datosTerminales input[name=' + key + ']').val(value);
				}

			});
		});

		$('#datosTerminales input[name=nro]').attr('readonly', true);

	});

	//---------------------------------------------------------------------------//

	$("#modificarModalTerminal").click(function () { // si dentro del modal hace click en guardar, guarda los datos		
		var datosTerminales = $("#datosTerminales").serialize();
		var idTerminal = $('#id_terminal_reparacion').val();

		var route = "/terminalOnsite/" + idTerminal;
		//var token = $("#token").val();

		$.ajax({
			url: route,
			//headers: {'X-CSRF-TOKEN': token},
			type: 'PUT',
			dataType: 'json',
			data: datosTerminales,
			success: function (data) {
				$("#modalTerminales").modal('toggle');
				$("#id_terminal_reparacion").empty();
				$("#id_terminal_reparacion").append("<option value='" + data.idTerminal + "'> " + data.nombreTerminal + "</option>");

			},
			error: function (data) {
				var mensajeErrorTerminal = 'Ha ocurrido un error en los siguientes campos: <br>';
				$.each(JSON.parse(data.responseText), function (key, value) {
					mensajeErrorTerminal = mensajeErrorTerminal + key + ': ' + value + ' <br>';
				});

				$('#mensaje-error-terminal').css('display', 'block');
				$('#mensaje-error-terminal').html(mensajeErrorTerminal);
			}
		});

	});

	//---------------------------------------------------------------------------//	


});

//---------------------------------------------------------------------------//
function deshabilitar() {
	$("#id_terminal_reparacion").empty();
	$('#editarTerminal').prop('disabled', true);
}

//---------------------------------------------------------------------------//

function limpiarFormTerminal() {
	$('#datosTerminales')[0].reset();
	$('#mensaje-error-terminal').html('');
	$('#mensaje-error-terminal').css('display', 'none');
}

//---------------------------------------------------------------------------//