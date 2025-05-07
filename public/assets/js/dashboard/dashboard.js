$(document).ready(function () {


	indicadoresObras(); //en public/assets/js/indicadores.js

	
	 

	$('#visitas_por_tecnico').on('click', function(){
		visitasPorTecnico();
	});

	$('#visitas_por_obra').on('click', function(){
		visitasPorObra();
	});


	$('#observadas_por_empresa').on('click', function(){
		
		resultadosPorEmpresa();
	});


	$('#observadas_por_tecnico').on('click', function(){
		
		resultadosPorTecnico();
	});

	





	



	$("#reparacionEstadoDiagnosticar").click(function () {
		location.href = "/reparacion";
	});

	$("#reparacionEstadoReparar").click(function () {
		location.href = "/reparacion";
	});

	//-----------------------------------//

	$("#reparacionEstadoPresupuestar").click(function () {
		location.href = "/reparacionEstadoPresupuestar";
	});

	$("#reparacionEstadoEsperandoAprob").click(function () {
		location.href = "/reparacionEstadoEsperandoAprob";
	});

	$("#reparacionEstadoPresupuestoAprob").click(function () {
		location.href = "/reparacionEstadoPresupuestoAprob";
	});

	$("#presupuestoRechazado").click(function () {
		location.href = "/presupuestoRechazado";
	});

	//-----------------------------------//

	$("#reparacionEstadoEnviarTallerExt").click(function () {
		location.href = "/reparacionEstadoEnviarTallerExt";
	});

	$("#reparacionEstadoTallerExterno").click(function () {
		location.href = "/reparacionEstadoTallerExterno";
	});

	//-----------------------------------//

	$("#reparacionEstadoPedirRepuesto").click(function () {
		location.href = "/reparacionEstadoPedirRepuesto";
	});

	$("#reparacionEstadoEsperandoRep").click(function () {
		location.href = "/reparacionEstadoEsperandoRep";
	});

	//-----------------------------------//

	$("#reparacionTotal").click(function () {
		location.href = "/reparacion";
	});


	//-----------------------------------//
	//-----------------------------------//

	$("#reparacionOnsiteIN").click(function () {
		location.href = "/reparacionOnsite";
	});

	$("#reparacionOnsiteIN24").click(function () {
		location.href = "/reparacionOnsite";
	});

	$("#reparacionOnsiteOUT").click(function () {
		location.href = "/reparacionOnsite";
	});

	$("#reparacionOnsiteINCERRADO").click(function () {
		location.href = "/reparacionOnsite";
	});

	$("#reparacionOnsiteOUTCERRADO").click(function () {
		location.href = "/reparacionOnsite";
	});

	$("#reparacionOnsiteTotal").click(function () {
		location.href = "/reparacionOnsite";
	});

	//-----------------------------------//
	//-----------------------------------//

	$("#reparacionEstadoDiagnosticarTecnico").click(function () {
		location.href = "/reparacionEstadoDiagnosticar";
	});

	$("#reparacionEstadoRepararTecnico").click(function () {
		location.href = "/reparacionEstadoReparar";
	});

	$("#reparacionTotalTecnico").click(function () {
		location.href = "/reparacionEstadoDiagnosticar";
	});

});