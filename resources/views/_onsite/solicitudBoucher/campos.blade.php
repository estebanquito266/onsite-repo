<div class="main-card mb-3 card ">
	<div class="card-header bg-secondary text-light">Bouchers Iniciales</div>
	<div class="card-body">
		<div class="form-row mt-3">
			@csrf

			<div class="form-group col-12 col-md-6">
				<label>Empresa Instaladora</label>

				<select name="empresa_instaladora_id" id="empresa_instaladora_id" class="form-control multiselect-dropdown">

					<option>Seleccione Empresa</option>

					@foreach ($empresas_instaladoras as $empresa)
					<option value="{{$empresa->id}}">{{$empresa->nombre}}</option>
					@endforeach

				</select>

				<input type="hidden" id="company_id" name="company_id" value="2">
			</div>

			<div class="form-group col-12 col-md-6">
				<label>Obra</label>

				<select name="obra_id" id="obra_onsite_id" class="form-control multiselect-dropdown">

					<option>Seleccione Obra</option>

					@foreach ($obras as $obra)
					<option value="{{$obra->id}}">{{$obra->nombre}}</option>
					@endforeach

				</select>

				<input type="hidden" id="company_id" name="company_id" value="2">
			</div>

			<div class="form-group col-12 col-md-2">
				<label>Cantidad</label>

				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text">#</span>
					</div>
					<input type="number" name='cantidad' id="cantidad" class='form-control' >
				</div>
				
			</div>

			<div class="form-group col-12 col-md-4">
				<label>Fecha vigencia</label>

				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text"><i class="pe-7s-date"> </i></span>
					</div>
					<input type="date" name='fecha_expira'  class='form-control' value="{{date('Y-m-d')}}" >
				</div>
				
			</div>

			<div class="form-group col-12 col-md-6">
			<label>Observaciones</label>

				<input type="text" name='observaciones' id="observaciones" class='form-control' placeholder='Ingrese observaciones' >
			</div>


			<small>Ingrese la información del lote de bouchers y presione guardar</small>



		</div>

	</div>

</div>