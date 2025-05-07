@if( $companyId == \App\Models\Company::DEFAULT && Request::segment(2) != 'create' )
@if($reparacionOnsite->reparacion_detalle)

<div class="main-card mb-3 card">
	<div class="card-header bg-info text-white">Companía</div>

	<div class="card-body">

		<div class="form-row mt-3 ">
			@if( in_array( $reparacionOnsite->id_empresa_onsite, Array(\App\Models\Onsite\EmpresaOnsite::RAPIPAGO, \App\Models\Onsite\EmpresaOnsite::PAGOFACIL) ) )
			<div class="form-group col-md-6 ">
				<label>TIPO DE CONEXIÓN DEL LOCAL</label>
				<div class="radio">
					<label>
						<input id="tipo_conexion_modem" name="tipo_conexion_local" type="radio" value="Modem 3G/4G" {{ $reparacionOnsite->reparacion_detalle->tipo_conexion_local == "Modem 3G/4G" ? "checked" : "" }}> Modem 3G/4G
					</label>
					<label>
						<input id="tipo_conexion_enlace" name="tipo_conexion_local" type="radio" value="Enlace" {{ $reparacionOnsite->reparacion_detalle->tipo_conexion_local == "Enlace" ? "checked" : "" }}> Enlace
					</label>
					<label>
						<input id="tipo_conexion_internet" name="tipo_conexion_local" type="radio" value="Internet" {{ $reparacionOnsite->reparacion_detalle->tipo_conexion_local == "Internet" ? "checked" : "" }}> Internet
					</label>
				</div>
			</div>
			<div class="form-group col-md-6 ">
				<label for="tipo_conexion_proveedor">Proveedor</label>
				<input type="text" class="form-control" placeholder="Ingrese proveedor" name="tipo_conexion_proveedor" value="{{ $reparacionOnsite->reparacion_detalle->tipo_conexion_proveedor }}">
			</div>
			@endif

			@if( in_array( $reparacionOnsite->id_empresa_onsite, Array(\App\Models\Onsite\EmpresaOnsite::RAPIPAGO, \App\Models\Onsite\EmpresaOnsite::PAGOFACIL) ) )
			<div class="form-group col-md-4 ">
				<label for="cableado">CABLEADO</label>
				<input type="checkbox" id="cableado" name="cableado" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="primary" data-offstyle="secondary" {{ (isset($reparacionOnsite) && $reparacionOnsite->reparacion_detalle->cableado) ? 'checked' : '' }}>
			</div>
			<div class="form-group col-md-4 ">
				<label for="cableado_cantidad_metros">Cantidad de Metros;</label>
				<input type="number" class="form-control" placeholder="Ingrese cantidad de metros" id="cableado_cantidad_metros" name="cableado_cantidad_metros" value="{{ $reparacionOnsite->reparacion_detalle->cableado_cantidad_metros }}">
			</div>
			<div class="form-group col-md-4 ">
				<label for="cableado_cantidad_fichas">Cantidad de Fichas RJ45:</label>
				<input type="number" class="form-control" placeholder="Ingrese cantidad de fichas rj45" id="cableado_cantidad_fichas" name="cableado_cantidad_fichas" value="{{ $reparacionOnsite->reparacion_detalle->cableado_cantidad_fichas }}">
			</div>
			@endif

			@if( in_array( $reparacionOnsite->id_empresa_onsite, Array(\App\Models\Onsite\EmpresaOnsite::PAGOFACIL) ) )
			<div class="form-group col-md-6">
				<label for="instalacion_cartel">¿SE INSTALÓ / DESINSTALÓ CARTEL?</label>
				<input type="checkbox" id="instalacion_cartel" name="instalacion_cartel" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="primary" data-offstyle="secondary" {{ (isset($reparacionOnsite) && $reparacionOnsite->reparacion_detalle->instalacion_cartel) ? 'checked' : '' }}>
			</div>
			<div class="form-group col-md-6">
				<label for="instalacion_cartel_luz">Con Luz</label>
				<input type="checkbox" id="instalacion_cartel_luz" name="instalacion_cartel_luz" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="primary" data-offstyle="secondary" {{ (isset($reparacionOnsite) && $reparacionOnsite->reparacion_detalle->instalacion_cartel_luz) ? 'checked' : '' }}>
			</div>
			@endif

			@if( in_array( $reparacionOnsite->id_empresa_onsite, Array(\App\Models\Onsite\EmpresaOnsite::PAGOFACIL) ) )
			<div class="form-group col-md-6">
				<label for="instalacion_buzon">¿SE AMURÓ / DESAMURÓ BUZÓN?</label>
				<input type="checkbox" id="instalacion_buzon" name="instalacion_buzon" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="primary" data-offstyle="secondary" {{ (isset($reparacionOnsite) && $reparacionOnsite->instalacion_buzon) ? 'checked' : '' }}>
			</div>
			@endif

			@if( in_array( $reparacionOnsite->id_empresa_onsite, Array(\App\Models\Onsite\EmpresaOnsite::TELECOM, \App\Models\Onsite\EmpresaOnsite::NIKE,\App\Models\Onsite\EmpresaOnsite::QUILMES,\App\Models\Onsite\EmpresaOnsite::YPF) ) )
			<div class="form-group col-md-6">
				<label>Horas de trabajo (sin incluir tiempo de viaje) formato HH:MM</label>
				<input type="text" class="form-control" placeholder="Ingrese cantidad de horas de trabajo" name="cantidad_horas_trabajo" value="{{ $reparacionOnsite->cantidad_horas_trabajo }}">
			</div>
			@endif

			<div class="form-group col-md-6">
				<label for="requiere_nueva_visita">¿REQUIERE SEGUNDA VISITA?</label>
				<input type="checkbox" id="requiere_nueva_visita" name="requiere_nueva_visita" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="primary" data-offstyle="secondary" {{ (isset($reparacionOnsite) && $reparacionOnsite->requiere_nueva_visita) ? 'checked' : '' }}>
			</div>
		</div>
	</div>
</div>
@endif
@endif