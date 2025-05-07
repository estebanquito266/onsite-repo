<div class="main-card mb-3 card ">
	<div class="card-header bg-secondary text-light">Tarifas Aplicables</div>
	<div class="card-body">
		<div class="form-row mt-3">
			@csrf
			@if(Request::segment(2)=='create')
			<input id="company_id" name="company_id" type="hidden" value="{{ (isset($company_id)) ? $company_id : 2 }}">
			@else
			<input id="company_id" name="company_id" type="hidden" value="{{ (isset($sucursalOnsite) &&  isset($sucursalOnsite->company_id)) ? $sucursalOnsite->company_id : null }}">
			@endif

			<div class="row mt-3">
						<div class="form-group col-12 col-md-12">
							<label>Seleccione Empresa Instaladora</label>
							<select name="empresa_instaladora_admins" id="empresa_instaladora_admins" class="multiselect-dropdown form-control">
								<option value="0">Seleccione</option>
								@foreach ($empresas_instaladoras_admins as $empresa)
								<option value="{{ $empresa->id }}" data-email="{{ $empresa->email }}" data-nombre="{{ $empresa->nombre }}" data-telefono="{{ $empresa->celular }}" {{old('empresa')==$empresa->id ? 'selected':null}}>{{$empresa->nombre}}</option>
								@endforeach
							</select>
						</div>

						<div class="form-group col-12 col-md-3">
							<label>Empresa Instaladora</label>
							<input readonly name='empresa_instaladora_nombre' id="empresa_instaladora_nombre" class='form-control'>
							<input readonly type="hidden" name="empresa_instaladora_id" id="empresa_instaladora_id">
						</div>
						<div class="form-group col-12 col-md-3">
							<label>Email </label>
							<input readonly name='responsable_email' id="responsable_email" class='form-control'>
						</div>
						<div class="form-group col-12 col-md-3">
							<label>Nombre </label>
							<input readonly name='responsable_nombre' id="responsable_nombre" class='form-control'>
						</div>
						<div class="form-group col-12 col-md-3">
							<label>Teléfono </label>
							<input readonly name='responsable_telefono' id='responsable_telefono' class='form-control'>
						</div>


						<div class="col-12 form-group">
							<label>Obras</label>
							<div class="row">
								<div class="col-10">
									<select name="obra_onsite_id" id="obra_onsite_id" class="form-control multiselect-dropdown">
										<option value="0"> -- Seleccione uno --</option>
										@if(isset($obrasOnsite))
										@foreach ($obrasOnsite as $obra)
										<option value="{{ $obra->id }}" data-idobra="{{ $obra->id }}" data-nombre_sistema="{{$obra->nombre}}">{{$obra->nombre}}
										</option>
										@endforeach
										@endif
									</select>
								</div>

								<div class="input-group-append col-2">
									<button id="refreshsistemasbutton" class="btn btn-secondary"><i class="pe-7s-refresh"> </i></button>
								</div>

							</div>
						</div>

						<div class="col-12 table-responsive">
							<table class="table table-striped table-bordered">
								<thead>
									<tr>
										<th></th>
										<th></th>
										<th></th>
									</tr>
								</thead>
								<tbody class="unidades">

								</tbody>
							</table>
						</div>

						

					</div>

			<div class="form-group col-12 col-md-4">
				<label>Tipo de Solicitud</label>

			</div>

			<div class="form-group col-12 col-md-2">
				<label>Monto</label>
			</div>

			<div class="form-group col-12 col-md-2">
				<label>Versión</label>
			</div>

			<div class="form-group col-12 col-md-4">
				<label>Observaciones</label>
			</div>

			@foreach ($solicitudesTipos as $solicitudTipo)

			<div class="form-group col-12 col-md-4">
				<input readonly type="text" class='form-control' value="{{$solicitudTipo->nombre}}">
				<input type="hidden" value="{{$solicitudTipo->id}}" name='solicitud_tipo_id[]' id="solicitud_tipo_id">
				<input id="company_id" name="company_id[]" type="hidden" value="2">
			</div>

			<div class="form-group col-12 col-md-2">

				<div class="input-group">
					<div class="input-group-prepend">
						<span class="input-group-text">$</span>
					</div>
					<input type="number" step="0.01" name='precio[]' id="precio" class='form-control' value="{{isset($solicitudTipo->solicitud_tipo_tarifa[0]) ? number_format($solicitudTipo->solicitud_tipo_tarifa[0]->precio, 2, '.', ','):null}}">
				</div>
				
			</div>

			<div class="form-group col-12 col-md-1">
				<input type="number" name='version[]' id="version" class='form-control' value="{{isset($solicitudTipo->solicitud_tipo_tarifa[0]) ? $solicitudTipo->solicitud_tipo_tarifa[0]->version:null}}">
			</div>

			<div class="form-group col-12 col-md-5">

				<input type="text" name='observaciones[]' id="observaciones" class='form-control' placeholder='Ingrese observaciones' value="{{isset($solicitudTipo->solicitud_tipo_tarifa[0]) ? $solicitudTipo->solicitud_tipo_tarifa[0]->observaciones:null}}">
			</div>

			@endforeach

			<small>Se observan monto, versión y observaciones de la última versión de tarifa registrada. Edite los valores y envie para actualizar.</small>



		</div>

	</div>

</div>