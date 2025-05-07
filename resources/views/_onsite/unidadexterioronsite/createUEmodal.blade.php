<div class="modal fade bd-example-modal-lg" id="modalUE" tabindex="-1" role="dialog" aria-labelledby="modalUE" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="modalUETitle"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

				<div class="main-card mb-3 card ">
					<div class="card-header card-header-tab  ">
						<div class="card-header-title font-size-lg text-capitalize font-weight-normal">
							<i class="header-icon lnr-apartment mr-3 text-muted opacity-6"> </i>
							Unidad Exterior
						</div>

					</div>
					<div class="card-body align-items-center bodymodalUE">

						<form method="POST" action="{{ url('unidadExterior') }}" id="unidadExteriorOnsiteForm" novalidate="novalidate" enctype="multipart/form-data">
							{{ csrf_field() }}
							@include('_onsite.unidadexterioronsite.camposRelaciones')
							@include('_onsite.unidadexterioronsite.campos')



						</form>
					</div>
				</div>

			</div>
			<div class="modal-footer col-12 footer_modalUE">
				<div class="col-9">
					<button type="button" class="btn btn-primary col-12" id="guardarModalUE">Enviar</button>
				</div>
				<div class="col-3">
					<button type="button" class="btn btn-secondary col-12" id="cerrarModalUE">Cerrar</button>
				</div>

			</div>
		</div>
	</div>
</div>