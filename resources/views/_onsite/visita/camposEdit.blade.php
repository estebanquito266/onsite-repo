@if( Request::segment(3) == 'edit' )
<div class="main-card mb-3 card ">
	<div class="card-header bg-secondary">
	</div>
	<div class="card-body">

		<div class="form-row mt-3">

			<div class="form-group col-lg-12 col-md-12">
				<label>Observación Ubicación</label>
				<input type="text" class="form-control" placeholder="Ingrese observación ubicación" name="observacion_ubicacion" value="{{ $reparacionOnsite->observacion_ubicacion }}">
			</div>

			<div class="form-group col-lg-6 col-md-6 ">
				<label>Fecha Coordinada</label>
				<div class="input-group">
					<div class="input-group-prepend datepicker-trigger">
						<div class="input-group-text">
							<i class="fa fa-calendar-alt"></i>
						</div>
					</div>

					<input type="date" class="form-control" id="fecha_coordinada" name="fecha_coordinada" value="{{ $reparacionOnsite->fecha_coordinada }}">
				</div>

			</div>

			<div class="form-group col-lg-6 col-md-6 ">
				<label for="ventana_horaria_coordinada">Ventana horaria coordinada</label>
				<select name="ventana_horaria_coordinada" id="ventana_horaria_coordinada" class="form-control">
					<option {{ (!isset($reparacionOnsite->ventana_horaria_coordinada) ? 'selected="selected"' : '') }} value="">Seleccione una ventana horaria</option>
					<option {{ ($reparacionOnsite->ventana_horaria_coordinada == '07 a 11' ? 'selected="selected"' : '') }} value="07 a 11" label="07 a 11">07 a 11</option>
					<option {{ ($reparacionOnsite->ventana_horaria_coordinada == '11 a 15' ? 'selected="selected"' : '') }} value="11 a 15" label="11 a 15">11 a 15</option>
					<option {{ ($reparacionOnsite->ventana_horaria_coordinada == '15 a 19' ? 'selected="selected"' : '') }} value="15 a 19" label="15 a 19">15 a 19</option>
					<option {{ ($reparacionOnsite->ventana_horaria_coordinada == '19 a 23' ? 'selected="selected"' : '') }} value="19 a 23" label="19 a 23">19 a 23</option>
				</select>
			</div>

			<div class="form-group col-lg-6 col-md-6 ">
				<label>Fecha Registración Coordinación</label>
				<div class="input-group">
					<div class="input-group-prepend datepicker-trigger">
						<div class="input-group-text">
							<i class="fa fa-calendar-alt"></i>
						</div>
					</div>

					<input type="text" class="form-control" readonly id="fecha_registracion_coordinacion" name="fecha_registracion_coordinacion" value="{{ $reparacionOnsite->fecha_registracion_coordinacion }}">
				</div>

			</div>

			<div class="form-group col-lg-6 col-md-6 ">
				<label>Fecha Notificado</label>
				<div class="input-group">
					<div class="input-group-prepend datepicker-trigger">
						<div class="input-group-text">
							<i class="fa fa-calendar-alt"></i>
						</div>
					</div>

					<input type="text" class="form-control" readonly id="fecha_notificado" name="fecha_notificado" value="{{ $reparacionOnsite->fecha_notificado }}">
				</div>

			</div>

			<div class="form-group col-lg-6 col-md-6 ">
				<label>Fecha Cerrado</label>

				<div class="input-group date" id="fecha_cerrado" data-target-input="nearest">

					<div class="input-group-append" data-target="#fecha_cerrado" data-toggle="datetimepicker">
						<div class="input-group-text"><i class="fa fa-calendar"></i></div>
					</div>
					<input type="text" class="form-control datetimepicker-input" data-target="#fecha_cerrado" placeholder="Ingrese fecha y hora de cierre" name="fecha_cerrado" id="fecha_cerrado_input" value="{{ $reparacionOnsite->fecha_cerrado }}">
				</div>
			</div>

			<div class="form-group col-lg-6 col-md-6 "></div>


			@if(Request::segment(2)!='create' )

			<div class="form-group col-lg-6 col-md-6 @if( $reparacionOnsite->sla_status =='IN' ) bg-success text-white @else bg-danger text-white @endif ">
				<label>Sla Status</label>
				<label class="radio-inline">
					@if( Request::segment(2)=='create' )
					<input type="radio" id="sla_status_in" name="sla_status" checked value="IN"> IN
					@else
					<input type="radio" id="sla_status_in" name="sla_status" value="IN" {{ $reparacionOnsite->sla_status == "IN" ? "checked" : "" }}> IN
					@endif
				</label>
				<label class="radio-inline">
					<input id="sla_status_out" name="sla_status" type="radio" value="OUT" {{ $reparacionOnsite->sla_status == "OUT" ? "checked" : "" }}> OUT
				</label>
			</div>

			<div class="form-group col-lg-6 col-md-6 @if( $reparacionOnsite->sla_status =='IN' ) bg-success text-white @else bg-danger text-white @endif ">
				<label for="sla_justificado">Sla Justificado</label>

				<input type="checkbox" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="primary" data-offstyle="secondary" id="sla_justificado" name="sla_justificado" {{ ((isset($reparacionOnsite) && $reparacionOnsite->sla_justificado) ? "checked" : "")}}>
			</div>

			@endif

		</div>

	</div>
</div>
@endif

@if( $companyId == 1 && (Request::segment(3) == 'edit' || Session::get('perfilAdmin')) )
<div class="main-card mb-3 card ">
	<div class="card-header bg-secondary">
	</div>
	<div class="card-body">

		<div class="form-row mt-3">

			<div class="form-group col-lg-6 col-md-6 ">
				<label>Monto</label>
				<input type="text" class="form-control input-mask-trigger" value="{{ $reparacionOnsite->monto }}" data-inputmask="'alias': 'numeric', 'radixPoint': '.', 'groupSeparator': ' ', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '$ ', 'placeholder': '0', 'rightAlign': true, 'removeMaskOnSubmit': true" im-insert="true" id="monto" name="monto" style="text-align: right;">
			</div>

			<div class="form-group col-lg-6 col-md-6 ">
				<label>Monto Extra</label>
				<input type="text" class="form-control input-mask-trigger" value="{{ $reparacionOnsite->monto_extra }}" data-inputmask="'alias': 'numeric', 'radixPoint': '.', 'groupSeparator': ' ', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '$ ', 'placeholder': '0', 'rightAlign': true, 'removeMaskOnSubmit': true" im-insert="true" id="monto_extra" name="monto_extra" style="text-align: right;">
			</div>

			<div class="form-group col-lg-6 col-md-6 ">
				<label>Nro Factura de Proveedor</label>
				<input type="text" class="form-control" placeholder="Ingrese nro factura proveedor" name="nro_factura_proveedor" value="{{ $reparacionOnsite->nro_factura_proveedor }}">
			</div>

			<div class="form-group col-lg-6 col-md-6 pt-4">
				<label for="liquidado_proveedor">Liquidado por el Proveedor</label>
				<input type="checkbox" id="liquidado_proveedor" name="liquidado_proveedor" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="primary" data-offstyle="secondary" {{ (isset($reparacionOnsite) && $reparacionOnsite->liquidado_proveedor) ? 'checked' : '' }}>
			</div>
		</div>

	</div>
</div>
@endif

@if( Request::segment(3) == 'edit' || Session::get('perfilAdmin') )
<div class="main-card mb-3 card ">
	<div class="card-header bg-primary">
	</div>
	<div class="card-body">
		<div class="form-row mt-3">
			<div class="form-group col-lg-12 col-md-12">
				<label>Informe Técnico</label>
				<textarea rows="5" class="form-control" name="informe_tecnico">{{ (isset($reparacionOnsite) && $reparacionOnsite->informe_tecnico != 'null') ? $reparacionOnsite->informe_tecnico : '' }}</textarea>
			</div>
		</div>
		@if( isset($reparacionOnsite) && in_array($reparacionOnsite->id_tipo_servicio, [\App\Models\Onsite\TipoServicioOnsite::SEGUIMIENTO_OBRA,\App\Models\Onsite\TipoServicioOnsite::PUESTA_MARCHA]))
		&nbsp;
		@else
		<div class="form-group col-lg-6 col-md-6 pt-4">
			<label for="problema_resuelto">Problema resuelto</label>
			<input type="checkbox" id="problema_resuelto" name="problema_resuelto" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="primary" data-offstyle="secondary" {{ (isset($reparacionOnsite) && $reparacionOnsite->problema_resuelto) ? 'checked' : '' }}>
		</div>
		@endif
		@if( Session::get('perfilAdmin') || Session::get('perfilAdminOnsite') )

		<div class="form-row mt-3">
			<div class="form-group col-lg-12 col-md-12">
				<label>Observaciones Internas</label>
				<textarea rows="5" class="form-control" name="observaciones_internas">{{ (isset($reparacionOnsite) && $reparacionOnsite->observaciones_internas != 'null') ? $reparacionOnsite->observaciones_internas : '' }}</textarea>
			</div>
		</div>

		@endif
	</div>
</div>

<div class="main-card mb-3 card ">
	<div class="card-header bg-primary">
	</div>
	<div class="card-body">
		<div class="form-row mt-3">
			<div class="form-group col-lg-6 col-md-6 pt-4">
				<label for="visible_cliente">Visible para Empresa/Cliente</label>
				<input type="checkbox" id="visible_cliente" name="visible_cliente" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="primary" data-offstyle="secondary" {{ (isset($reparacionOnsite) && $reparacionOnsite->visible_cliente) ? 'checked' : '' }}>
			</div>
			<div class="form-group col-lg-6 col-md-6 pt-4">
				<label for="chequeado_cliente">Chequeado por Empresa/Cliente</label>
				<input type="checkbox" id="chequeado_cliente" name="chequeado_cliente" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="primary" data-offstyle="secondary" {{ (isset($reparacionOnsite) && $reparacionOnsite->chequeado_cliente) ? 'checked' : '' }}>
			</div>
			<div class="form-group col-lg-12 col-md-12 pt-4">
				<label for="nota_cliente">Nota Cliente</label>
				<input type="text" class="form-control" id="nota_cliente" name="nota_cliente" value="{{ (isset($reparacionOnsite)) ? $reparacionOnsite->nota_cliente : '' }}">
			</div>

		</div>

	</div>
</div>
@endif

@if( in_array($reparacionOnsite->id_tipo_servicio, [\App\Models\Onsite\TipoServicioOnsite::SEGUIMIENTO_OBRA,\App\Models\Onsite\TipoServicioOnsite::PUESTA_MARCHA]))
<div id="puestaMarcha">
	@include('_onsite.visita.camposPuestaMarcha')
</div>

@endif

@include('_onsite.visita.imagenesOnsite')
@include('_onsite.visita.info')


<div class="main-card mb-3 card ">
	<div class="card-header bg-alternate">
		<span class="text-light">Nuevo Historial Estado</span>
	</div>
	<div class="card-body">
		<div class="form-row">
			<div class="form-group col-lg-12 col-md-12">
				<label>Observación (solo si cambia ESTADO) </label>
				<textarea rows="5" class="form-control" name="observacion"></textarea>
			</div>
		</div>
	</div>
</div>