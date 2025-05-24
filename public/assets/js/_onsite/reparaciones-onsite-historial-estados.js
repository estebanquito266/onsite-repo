$(document).ready(function () {

	var basePath = '/App/public';

	$("button[name=consultarHistorialEstadoOnsite]").click(function () {

		var idReparacionOnsite = event.target.value;


		$("#tablaHistorialEstadosOnsite > tbody").empty();

		$("#cardTitleModal").empty();

		$("#timelineHistorialEstadosOnsite").empty();

		$("#cardTitleModal").html('Reparación: ' + idReparacionOnsite + ' - Detalle ');

		var route = "/buscarHistorialEstadosOnsite/" + idReparacionOnsite;

		$.get(
			route,
			function (response, state) {

				for (i = 0; i < response.length; i++) {

					var d = new Date(response[i].fecha);

					var month = d.getMonth() + 1;
					var day = d.getDate();
					var year = d.getFullYear() - 2000;
					var fechaFormat = day + "/" + month + "/" + year;

					var hour = d.getHours();
					var minute = appendLeadingZeroes(d.getMinutes());
					var second = d.getSeconds();
					var horaFormat = hour + ":" + minute;
					
					$('.vertical-timeline').append('<div class="vertical-timeline-item vertical-timeline-element"> <div><span class="vertical-timeline-element-icon bounce-in"><i class="badge badge-dot badge-dot-xl badge-success"> </i></span> <div class="vertical-timeline-element-content bounce-in"><h4 class="timeline-title">  ' + (response[i].estado_onsite !== null ? response[i].estado_onsite : 'nota') + ' [#' + response[i].id + ']</h4> <p>' + response[i].observacion + '</p><span class="vertical-timeline-element-date">' + fechaFormat + '<br>' + horaFormat + '</span> <div class="avatar-icon-wrapper avatar-icon-sm"> <div class="avatar-icon"><img src="/imagenes/' + response[i].usuario_foto_perfil + '" alt="' + response[i].usuario + '" title="' + response[i].usuario + '"></div> </div> </div> </div> </div>');
				}
			});
	});

	//-----------------------------------------------------------------------//	


});

function appendLeadingZeroes(n) {
	if (n <= 9) {
		return "0" + n;
	}
	return n
}