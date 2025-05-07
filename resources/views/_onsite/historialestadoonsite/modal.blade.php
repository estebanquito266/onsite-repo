<div class="modal fade" id="modalHistorialEstadosOnsite" tabindex="-1" role="dialog" aria-labelledby="modalLabelHistorialEstadosOnsite">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
				<h4 class="modal-title" id="modalLabelHistorialEstadosOnsite">Historial de notificaciones</h4>
			</div>
			<div class="modal-body">
			
				<div class="table-responsive">
					<table class="table table-striped" id="tablaHistorialEstadosOnsite">
						<thead>
							<tr>
								<th>#</th>
								<th>Reparacion Onsite</th>
								<th>Estado Onsite</th>
								<th>Usuario</th>
								<th>Fecha</th>							
								<th>Observacion</th>								
							</tr>
						</thead>
						<tbody>
							@foreach($historialEstadosOnsite as $historialEstadoOnsite)
							<tr>
								<td>{{$historialEstadoOnsite->id}}</td>
								<td>{{$historialEstadoOnsite->id_reparacion}}</td>
								<td>{{$historialEstadoOnsite->nombreestado}}</td>
								<td>{{$historialEstadoOnsite->nombreusuario}}</td>
								<td>{{$historialEstadoOnsite->fecha}}</td>
								<td>{{$historialEstadoOnsite->observacion}}</td>														
							</tr>
							@endforeach							
						</tbody>
					</table>
				</div>				
				
			</div>
			<div class="modal-footer">
				
			</div>
		</div>
	</div>
</div>