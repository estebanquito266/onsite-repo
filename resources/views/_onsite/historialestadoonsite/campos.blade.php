<div class="main-card mb-3 card ">
	<div class="card-header bg-secondary"> </div>
	<div class="card-body">
		<div class="form-row mt-3">

			<div class="form-group col-lg-6 col-md-6">
				<label>Reparación Onsite</label>


				<input class="form-control" placeholder="Ingrese el id_reparacion " name="id_reparacion" id="id_reparacion" type="text" value="{{ (isset($historialEstadoOnsite)?$historialEstadoOnsite->id_reparacion:null) }}">
			</div>
			<div class="form-group col-lg-6 col-md-6">
				<label>Estado Onsite</label>


				<select name="id_estado" id="id_estado" class="form-control multiselect-dropdown" placeholder='Seleccione estado onsite'>
					<option value=""> -- Seleccione uno --</option>
					@foreach ($estadosOnsite as $estadoOnsite)
						<option value="{{ $estadoOnsite->id }}" {{ ((isset($historialEstadoOnsite) && $historialEstadoOnsite->id_estado == $estadoOnsite->id)?'selected':'') }}>{{ $estadoOnsite->nombre }}</option>
					@endforeach
				</select>
			</div>
			<div class="form-group col-lg-6 col-md-6">
				<label>Fecha</label>

				<div class="input-group date" id="fecha" data-target-input="nearest">

					<div class="input-group-append" data-target="#fecha" data-toggle="datetimepicker">
						<div class="input-group-text"><i class="fa fa-calendar"></i></div>
					</div>

					<input class="form-control datetimepicker-input" data-target="#fecha" placeholder="Ingrese el fecha " name="fecha" id="fecha" type="text" value="{{ (isset($historialEstadoOnsite)?$historialEstadoOnsite->fecha:null) }}"> 
				</div>
				<p class="help-block">AAAA-MM-DD HH:MM:SS (Ejemplo 2017-08-24 17:12:10)</p>
			</div>
			

			<div class="form-group col-lg-6 col-md-6">
				<label>Usuario</label>
				<select name="id_usuario" id="id_usuario" class="form-control multiselect-dropdown" placeholder='Seleccione usuario'>
					<option value=""> -- Seleccione uno --</option>
					@foreach ($usuarios as $id => $usuario)
						<option value="{{ $id }}" {{ ((isset($historialEstadoOnsite) && $historialEstadoOnsite->id_usuario == $id)? 'selected' : '' ) }}>{{ $usuario }}</option>
					@endforeach
				</select>
			</div>


			<div class="form-group col-lg-6 col-md-6">
				<label>Observación</label>

				<input class="form-control" placeholder="Ingrese el observacion " name="observacion" id="observacion" type="text" value="{{ (isset($historialEstadoOnsite)?$historialEstadoOnsite->observacion:null) }}">
			</div>

		</div>

	</div>
</div>