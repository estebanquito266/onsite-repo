<p>Estimado {{$tecnicoOnsite->name}},</p>

<p>Le hemos asignado un nuevo servicio a realizar, según el siguiente detalle:</p>

<table class="table table-striped table-bordered">
	<tr>
		<td><strong>Tipo de Servicio<strong></td>
		<td>{{ ($tipoServicioOnsite?$tipoServicioOnsite->nombre:'') }}</td>
	</tr>	
	<tr>
		<td><strong>Fecha de Asignación<strong></td>
		<td>{{ ($reparacionOnsite?$reparacionOnsite->fecha_ingreso:'') }}</td>
	</tr>	
	<tr>
		<td><strong>Sla acordado<strong></td>
		<td>{{ ($slaOnsite?$slaOnsite->horas:'')}} horas</td>
	</tr>	
	
	<tr>
		<td><strong>Número de Servicio<strong></td>
		<td>{{ ($reparacionOnsite?$reparacionOnsite->clave:'') }}</td>
	</tr>
	<tr>
		<td><strong>Terminal<strong></td>
		<td>{{ ($terminalOnsite?$terminalOnsite->nro:'') }} / {{ ($sucursalOnsite?$sucursalOnsite->razon_social:'') }}</td>
	</tr>	
	<tr>
		<td><strong>Datos de contacto<strong></td>
		<td>{{ ($reparacionOnsite?$reparacionOnsite->observacion_ubicacion:'') }}</td>
	</tr>		
	
	<tr>
		<td><strong>Tarea<strong></td>
		<td>{{ ($reparacionOnsite?$reparacionOnsite->tarea:'') }}</td>
	</tr>	
	<tr>
		<td><strong>Detalle de Tarea<strong></td>
		<td>{{ ($reparacionOnsite?$reparacionOnsite->tarea_detalle:'') }}</td>
	</tr>	
	
</table>

<p>Aguardamos su confirmación sobre la coordinación del servicio, dentro del SLA acordado.</p>
<p>Muchas gracias por su ayuda,</p>
<p> &nbsp;</p>
<p> <a href="https://onsite.speedup.com.ar/app/">Panel Onsite</a> </p>