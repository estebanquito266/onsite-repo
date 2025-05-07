$(document).ready(function () {

	$('#table_unidades_interiores').DataTable({
		/* data: dataSet, */
		columns: [
            { title: "id" },     
            { title: "Descripción" },         
            { title: "Imagenes" },            

        ],
		/* dom: 'Bfrtip'  */
	  });

	  $('#table_unidades_exteriores').DataTable({
		/* data: dataSet, */
		columns: [
            { title: "id" },     
            { title: "Descripción" },         
            { title: "Imagenes" },            

        ],
		/* dom: 'Bfrtip'  */
	  });


	  


	//-----------------------------------------------------------------------//		
	$("#createSistema").click(function () {

		limpiarModalFormSistema();

		$('#storeModalSistema').removeClass('d-none');

		$('#updateModalSistema').addClass('d-none');

		var idempresa = $("#id_empresa_onsite").val();//trae valor de input hidden de campos.blade				
		var idsucursal = $("#sucursal_onsite_id").val();

		console.log('createSistema - id_empresa_onsite:' + idempresa + ' - sucursal_onsite_id' + idsucursal);

		$('.logica-sistema-empresa-modal').html('<input type="hidden" name="empresa_onsite_id" id="sistema_empresa_onsite_id" value="' + idempresa + '"></input>');
		$('.logica-sistema-sucursal-modal').html('<input type="hidden" name="sucursal_onsite_id" id="sistema_sucursal_onsite_id" value="' + idsucursal + '"></input>');

	});


	

	//---------------------------------------------------------------------------// 
	$("#storeModalSistema").click(function () { // si dentro del modal hace click en guardar, guarda datos      
		var datosSistemas = $("#datosSistemas").serialize();
		var route = "/sistemaOnsite";
		//var token = $("#token").val();
		$.ajax({
			url: route,
			//headers: {'X-CSRF-TOKEN': token},
			type: 'POST',
			dataType: 'json',
			data: datosSistemas,
			success: function (data) {
				$("#modalSistemas").modal('toggle');
				$('#editarSistema').prop('disabled', false); //habilito para editar


				$("#sistema_onsite_id").empty();
				$("#sistema_onsite_id").append("<option value='" + data.id + "'> " + data.id + " - " + data.nombre + "</option>");

			},
			error: function (data) {
				var mensajeErrorSistema = 'Ha ocurrido un error en los siguientes campos: <br>';

				$.each(JSON.parse(data.responseText).errors, function (key, value) {
					mensajeErrorSistema = mensajeErrorSistema + key + ': ' + value + ' <br>';
				});
				$('#mensaje-error-sistema').css('display', 'block');
				$('#mensaje-error-sistema').html(mensajeErrorSistema);
			}

		});

	});


	//---------------------------------------------------------------------------//	


});

function limpiarModalFormSistema() {
	$('#datosSistemas')[0].reset();
	$('#mensaje-error-sistema').html('');
	$('#mensaje-error-sistema').css('display', 'none');

}