<div class="modal fade bd-example-modal-lg" id="CompradorModal" tabindex="-1" role="dialog" aria-labelledby="CompradorModal" aria-hidden="true">
	<div class="modal-dialog modal-lg" role="document">
		<div class="modal-content">
			<div class="modal-header">
				<h5 class="modal-title" id="CompradorModalTitle"></h5>
				<button type="button" class="close" data-dismiss="modal" aria-label="Close">
					<span aria-hidden="true">&times;</span>
				</button>
			</div>
			<div class="modal-body">

				<div class="main-card mb-3 card ">
					<div class="card-header card-header-tab  ">
						<div class="card-header-title font-size-lg text-capitalize font-weight-normal">
							<i class="header-icon lnr-apartment mr-3 text-muted opacity-6"> </i>
							Comprador
						</div>

					</div>
					<div class="card-body align-items-center bodyCompradorModal">

						<form method="POST" enctype="multipart/form-data">
							{{ csrf_field() }}
							@include('_onsite.sistemaonsite.camposComprador')
						</form>
					</div>
				</div>

			</div>
			<div class="modal-footer col-12 footer_CompradorModal">
				<div class="col-9">
					<button type="button" class="btn btn-primary col-12" id="guardarCompradorModal">Enviar</button>
				</div>
				<div class="col-3">
					<button type="button" class="btn btn-secondary col-12" id="cerrarCompradorModal">Cerrar</button>
				</div>

			</div>
		</div>
	</div>
</div>