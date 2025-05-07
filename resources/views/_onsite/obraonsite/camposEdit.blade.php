<div class="main-card mb-3 card " hidden>
	<div class="card-header text-white bg-secondary">
		Obra
	</div>
	<div class="card-body">
		<div class="form-row mt-3" hidden>
			<div class="form-group col-lg-12 col-md-12">
				<label>Clave</label>
				<input type="text" name='clave' id="clave" class='form-control' placeholder='Ingrese clave' value="{{ (isset($obraOnsite)?$obraOnsite->clave:null) }}" readonly="true">
			</div>
			

		</div>

	</div>
</div>