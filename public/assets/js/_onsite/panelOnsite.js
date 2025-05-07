//$.mobile.changePage.defaults.changeHash = false;

$(document).bind('mobileinit', function(){
	$.mobile.ajaxEnabled = 'false';
} );


$(".cambioEstado").click(function(){	
	var idReparacionOnsite = $(this).attr('value');
	var idEstadoOnsite = $(this).attr('name');
	
	if(idEstadoOnsite == 2){
		$("#divFechaHora").css('display','block');
	}
	else{
		$("#divFechaHora").css('display','none');
	}
	
	$("#id_reparacion_onsite").val(idReparacionOnsite);
	$("#id_estado_onsite").val(idEstadoOnsite);
});


$("#guardarObservacion").click(function(){
	if( $("#id_estado_onsite").val() == 2 ){
		if( $("#fecha_hora").val() == "" ) {
			$("#fecha_hora").focus();
			return false;
		}
	}
});