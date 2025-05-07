<div class="main-card mb-3 card ">
	<div class="card-header bg-secondary text-light">Datos Sucursal</div>
	<div class="card-body">
		<div class="form-row mt-3">
			@csrf
			@if(Request::segment(2)=='create')
				<input id="company_id" name="company_id" type="hidden" value="{{ (isset($company_id)) ? $company_id : null }}">
			@else
				<input id="company_id" name="company_id" type="hidden" value="{{ (isset($sucursalOnsite) &&  isset($sucursalOnsite->company_id)) ? $sucursalOnsite->company_id : null }}">
			@endif

			<div class="form-group col-lg-6 col-md-6 logica-sucursal-modal">
				<label>Empresa Onsite Id</label>

				<select name="empresa_onsite_id" id="empresa_onsite_id" class="form-control multiselect-dropdown" placeholder='Seleccione empresa onsite id'>
					<option value=""> -- Seleccione uno --</option>
					@foreach ($empresasOnsite as $empresa_onsite_id)
					<option value="{{ $empresa_onsite_id->id }}" {{ ((isset($sucursalOnsite) && isset($sucursalOnsite->empresa_onsite_id) && $sucursalOnsite->empresa_onsite_id == $empresa_onsite_id->id)?'selected':'') }}>{{ $empresa_onsite_id->nombre }}</option>
					@endforeach
				</select>
			</div>

			<div class="form-group col-lg-6 col-md-6">
				<label>Código Sucursal</label>
				<input type="text" name='codigo_sucursal' id="codigo_sucursal" class='form-control' placeholder='Ingrese código sucursal' value="{{ ( ( isset($sucursalOnsite) && $sucursalOnsite)?$sucursalOnsite->codigo_sucursal:null) }}">
			</div>

			<div class="form-group col-lg-6 col-md-6">
				<label>Razón Social</label>
				<input type="text" name='razon_social' id="razon_social" class='form-control' placeholder='Ingrese razón social' value="{{ ( ( isset($sucursalOnsite) && $sucursalOnsite)?$sucursalOnsite->razon_social:null) }}">
			</div>

			

			<div class="form-group col-lg-6 col-md-6 logica-localidad-modal">
				<label>Localidad Onsite Id</label>

				<select name="localidad_onsite_id" id="localidad_onsite_id" class="form-control multiselect-dropdown" placeholder='Seleccione localidad onsite id'>
					<option value=""> -- Seleccione uno --</option>
					@foreach ($localidadesOnsite as $id => $localidadOnsite)
						<option value="{{ $id }}" {{ ((isset($sucursalOnsite) && isset($sucursalOnsite->localidad_onsite_id) && $sucursalOnsite->localidad_onsite_id == $id) ? 'selected' : '' ) }}>{{ $localidadOnsite }}</option>
					@endforeach
				</select>
			</div>
			<div class="form-group col-lg-6 col-md-6">
				<label>Dirección</label>

				<input type="text" name='direccion' id="direccion" class='form-control' placeholder='Ingrese dirección' value="{{ ( ( isset($sucursalOnsite) && $sucursalOnsite)?$sucursalOnsite->direccion:null) }}">

			</div>
			<div class="form-group col-lg-6 col-md-6">
				<label>Teléfono Contacto</label>

				<input type="text" name='telefono_contacto' id="telefono_contacto" class='form-control' placeholder='Ingrese teléfono contacto' value="{{ ( ( isset($sucursalOnsite) && $sucursalOnsite)?$sucursalOnsite->telefono_contacto:null) }}">

			</div>
			<div class="form-group col-lg-6 col-md-6">
				<label>Nombre Contacto</label>

				<input type="text" name='nombre_contacto' id="nombre_contacto" class='form-control' placeholder='Ingrese nombre contacto' value="{{ ( ( isset($sucursalOnsite) && $sucursalOnsite)?$sucursalOnsite->nombre_contacto:null) }}">

			</div>
			<div class="form-group col-lg-6 col-md-6">
				<label>Horarios Atención</label>

				<input type="text" name='horarios_atencion' id="horarios_atencion" class='form-control' placeholder='Ingrese horarios atención' value="{{ ( ( isset($sucursalOnsite) && $sucursalOnsite)?$sucursalOnsite->horarios_atencion:null) }}">

			</div>
			<div class="form-group col-lg-12 col-md-12">
				<label>Observaciones</label>

				<input type="text" name='observaciones' id="observaciones" class='form-control' placeholder='Ingrese observaciones' value="{{ ( ( isset($sucursalOnsite) && $sucursalOnsite)?$sucursalOnsite->observaciones:null) }}">

			</div>
			

		</div>

	</div>
	
</div>