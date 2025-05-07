<div class="modal fade" id="modalHistorialEstadosOnsite" tabindex="-1" role="dialog" aria-labelledby="modalLabelHistorialEstadosOnsite" aria-hidden="true">
	<div class="modal-dialog" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalLabelHistorialEstadosOnsite">Historial de notificaciones.</h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">



				<div class="main-card card">
					<div class="card-body">
						<h5 class="card-title" id="cardTitleModal">Reparación: {{ (isset($reparacionOnsite))?$reparacionOnsite->id:'' }} - Detalle</h5>
						<div class="scroll-area">
							<div class="scrollbar-container">
								<div class="vertical-timeline vertical-timeline--animate vertical-timeline--one-column" id="timelineHistorialEstadosOnsite">

									@foreach($historialEstadosOnsite as $historialEstadoOnsite)

									<div class="vertical-timeline-item vertical-timeline-element">
										<div>
											<span class="vertical-timeline-element-icon bounce-in">
												<i class="badge badge-dot badge-dot-xl badge-success"> </i>
											</span>
											<div class="vertical-timeline-element-content bounce-in">
												<h4 class="timeline-title"> {{ ($historialEstadoOnsite['estado_onsite'])}} [#{{$historialEstadoOnsite['id'] }}]</h4>
												<p>{{ $historialEstadoOnsite['observacion'] }}</p>
												<span class="vertical-timeline-element-date">
													{{ date_format( date_create($historialEstadoOnsite['fecha']), 'j/n/y' ) }}<br>
													{{ date_format( date_create($historialEstadoOnsite['fecha']), 'H:i' ) }}
												</span>
												<div class="avatar-icon-wrapper avatar-icon-sm">
													<div class="avatar-icon">
														<img src="/imagenes/{{ ($historialEstadoOnsite['usuario_foto_perfil'])  }}" alt="{{ ($historialEstadoOnsite['usuario']) }}" title="{{ ($historialEstadoOnsite['usuario'])  }}">
													</div>
												</div>
											</div>
										</div>
									</div>

									@endforeach

								</div>
							</div>
						</div>
					</div>
				</div>





			</div>
			<div class="modal-footer">
				<button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
			</div>
		</div>
	</div>
</div>