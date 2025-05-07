<div class="main-card mb-3 card ">			
	<div class="card-header bg-secondary">	</div>
	<div class="card-body">	
		<div class="form-row mt-3">
			<div class="form-group  col-lg-6 col-md-6">
				<label>Provincia</label>
				<select name="id_provincia" id="id_provincia" class="form-control multiselect-dropdown" placeholder='Seleccione la provincia'>
					<option value=""> -- Seleccione una --</option>
					@foreach ($provincias as $provincia)
						<option value="{{ $provincia->id }}" {{ ((isset($localidadOnsite) && $localidadOnsite->id_provincia == $provincia->id) ? 'selected' : '') }}>{{ $provincia->nombre }}</option>
					@endforeach
				</select>
			</div>
			<div class="form-group  col-lg-6 col-md-6">
				<label>Localidad</label>
				<input type="text" name="localidad" id="localidad" class="form-control" placeholder="Ingrese la localidad " value="{{ (isset($localidadOnsite) ? $localidadOnsite->localidad : null) }}">
			</div>

			<div class="form-group col-lg-6 col-md-6">
				<label>Localidad Estandard</label>
				<input type="text" name="localidad_estandard" id="localidad_estandard" class="form-control" placeholder="Ingrese la localidad estandard " value="{{ (isset($localidadOnsite) ? $localidadOnsite->localidad_estandard : null) }}">
			</div>

			<div class="form-group col-lg-6 col-md-6">
				<label>Código</label>
				<input type="text" name="codigo" id="codigo" class="form-control" placeholder="Ingrese el código " value="{{ (isset($localidadOnsite) ? $localidadOnsite->codigo : null) }}">
			</div>

			<div class="form-group col-lg-6 col-md-6">
				<label>Km</label>
				<input type="text" name="km" id="km" class="form-control" placeholder="Ingrese los km " value="{{ (isset($localidadOnsite) ? $localidadOnsite->km : null) }}">
			</div>
			<div class="form-group col-lg-6 col-md-6">
				<label>Nivel Onsite</label>
				<select name="id_nivel" id="id_nivel" class="form-control multiselect-dropdown" placeholder='Seleccione el nivel'>
					<option value=""> -- Seleccione uno --</option>
					@foreach ($nivelesOnsite as $id => $nivelOnsite)
						<option value="{{ $id }}" {{ ((isset($localidadOnsite) && $localidadOnsite->id_nivel == $id) ? 'selected' : '') }}>{{ $nivelOnsite }}</option>
					@endforeach
				</select>
			</div>
			<div class="form-group col-lg-6 col-md-6">
				<label>Atiende desde</label>
				<input type="text" name="atiende_desde" id="atiende_desde" class="form-control" placeholder="Ingrese desde dónde atiente " value="{{ (isset($localidadOnsite) ? $localidadOnsite->atiende_desde : null) }}">
			</div>
			<div class="form-group col-lg-6 col-md-6">
				<label>Técnico Onsite</label>
				<select name="id_usuario_tecnico" id="id_usuario_tecnico" class="form-control multiselect-dropdown" placeholder='Seleccione el técnico onsite'>
					<option value=""> -- Seleccione uno --</option>
					@foreach ($tecnicosOnsite as $id => $tecnicoOnsite)
						<option value="{{ $id }}" {{ ((isset($localidadOnsite) && $localidadOnsite->id_usuario_tecnico == $id) ? 'selected' : '') }}>{{ $tecnicoOnsite }}</option>
					@endforeach
				</select>
			</div>

		</div>
	</div>	
</div>	