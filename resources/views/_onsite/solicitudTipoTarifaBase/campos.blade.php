<div class="main-card mb-3 card ">
	<div class="card-header bg-secondary text-light">Tarifas Bases Aplicables</div>
	<div class="card-body">
		<div class="form-row mt-3">
			@csrf
			@if(Request::segment(2)=='create')
			<input id="company_id" name="company_id" type="hidden" value="{{ (isset($company_id)) ? $company_id : 2 }}">
			@else
			<input id="company_id" name="company_id" type="hidden" value="{{ (isset($sucursalOnsite) &&  isset($sucursalOnsite->company_id)) ? $sucursalOnsite->company_id : null }}">
			@endif

			<div class="form-group col-12 col-md-4">
				<label>Tipo de Solicitud</label>

			</div>

			<div class="form-group col-12 col-md-2">
				<label>Monto</label>
			</div>

			<div class="form-group col-12 col-md-1">
				<label>Versión Ant.</label>
			</div>

			<div class="form-group col-12 col-md-1">
				<label>Versión Actual</label>
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
					<input type="text" step="0.01" name='precio[]' id="precio" class='form-control'
					 value="{{isset($solicitudTipo->solicitud_tipo_tarifa_base[0]) ? $solicitudTipo->solicitud_tipo_tarifa_base[0]->precio:null}}">
				</div>

			</div>

			<div class="form-group col-12 col-md-1">
				<input readonly type="number" name='version_anterior[]' id="version" class='form-control' value="{{isset($solicitudTipo->solicitud_tipo_tarifa_base[0]) ? $solicitudTipo->solicitud_tipo_tarifa_base[0]->version:0}}">
			</div>

			<div class="form-group col-12 col-md-1">
				<input readonly type="number" name='version[]' id="version" class='form-control' value="{{isset($solicitudTipo->solicitud_tipo_tarifa_base[0]) ? $solicitudTipo->solicitud_tipo_tarifa_base[0]->version+1:1}}">
			</div>

			<div class="form-group col-12 col-md-4">

				<input type="text" name='observaciones[]' id="observaciones" class='form-control' placeholder='Ingrese observaciones' value="{{isset($solicitudTipo->solicitud_tipo_tarifa_base[0]) ? $solicitudTipo->solicitud_tipo_tarifa_base[0]->observaciones:null}}">
			</div>

			@endforeach

			<small>Se observan monto, versión y observaciones de la última versión de tarifa registrada. Edite los valores y envie para actualizar.</small>



		</div>

	</div>

</div>