$(document).ready(function () {
	var basePath = '/riparazione/public';

	$('button[name=visibilidadHistorialEstadoOnsite]').click(function () {

    if(!window.confirm("¿Desea ocultar notificación?")) {
      return;
    }

		var idHistorialEstadoOnsite = $(this).val();
		var idTrHistorialEstadoOnsite = '#trHistorialEstadoOnsite' + idHistorialEstadoOnsite;
		//$( idTrHistorialEstadoOnsite ).addClass('hidden');			
		$(idTrHistorialEstadoOnsite).addClass('d-none');

		var route = "/ocultarHistorialEstadoOnsite/" + idHistorialEstadoOnsite;
		var token = $("input[name=_token]").val();

		$.ajax({
			url: route,
			headers: { 'X-CSRF-TOKEN': token },
			type: 'POST',
			dataType: 'json',
			data: { 'id': idHistorialEstadoOnsite },
			success: function (data) {
				//console.log(' oculto');
			}
		});
	});




});	