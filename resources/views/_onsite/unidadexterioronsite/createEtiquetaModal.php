<div class="modal fade bd-example-modal-lg" id="createEtiquetaModal" tabindex="-1" role="dialog" aria-labelledby="createEtiquetaModal" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="createEtiquetaModalTitle"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

				<div class="main-card mb-3 card ">
					<div class="card-header card-header-tab  ">
						<div class="card-header-title font-size-lg text-capitalize font-weight-normal">
							<i class="header-icon pe-7s-box2 mr-3 text-muted opacity-6"> </i>
							Datos Etiqueta
						</div>

					</div>
					<div class="card-body align-items-center bodycreateEtiquetaModal">

						<div class="card-body row">

							<div class="form-group col-12 col-md-12">
								<label>Nombre</label>
								<input type="text" name='nombre_etiqueta' id="nombre_etiqueta" class='form-control'>
								<input type="hidden" name="unidad_exterior_id" id="unidad_exterior_id"><!-- se setea dinamicamente -->
							</div>

							<div class="col-9">
								<button type="button" class="btn btn-primary col-12" id="guardarEtiquetaUI">Enviar</button>
							</div>
							<div class="col-3">
								<button type="button" class="btn btn-secondary col-12" id="cerrarEtiquetaUI">Cerrar</button>
							</div>

						</div>

					</div>
				</div>

				<div class="main-card mb-3 card ">

					<div class="card-header card-header-tab  ">
						<div class="card-header-title font-size-lg text-capitalize font-weight-normal">
							<i class="header-icon pe-7s-box2 mr-3 text-muted opacity-6"> </i>
							Etiquetas Creadas
						</div>
					</div>

					<div class="card-body row">
						<table class="table">
							<thead>
								<tr>
									<th>id</th>
									<th>nombre</th>
									<th>actions</th>
								</tr>
							</thead>
							<tbody class="filas_etiquetas">
								<!-- completa dinámicamente -->
							</tbody>
						</table>
					</div>
				</div>

			</div>
			<div class="modal-footer col-12 footer_modalUE">


			</div>
		</div>
	</div>
</div>