<div class="main-card mb-3 card logica-terminal-empresa-modal">
	<div class="card-header bg-secondary text-white">Empresa Onsite</div>
	<div class="card-body">
		<div class="form-row mt-12">
			<div class="form-group col-lg-12 col-md-12">
				<select name="empresa_onsite_id" id="terminal_empresa_onsite_id" class="form-control multiselect-dropdown " placeholder='Seleccione empresa onsite id' {!! (Request::segment(2)=='create' ) ? 'onchange="validarGenerarNumeroTerminal();"' : '' !!}">
					<option value=""> -- Seleccione uno --</option>
					@foreach ($empresasOnsite as $empresaOnsite)
						<option value="{{ $empresaOnsite->id }}" {{ ((isset($terminalOnsite) && isset($terminalOnsite->empresa_onsite_id) && $terminalOnsite->empresa_onsite_id == $empresaOnsite->id)?'selected':'') }}>{{ $empresaOnsite->nombre }}</option>
					@endforeach
				</select>
			</div>
		</div>
	</div>
</div>

<div class="main-card mb-3 card ">
	<div class="card-header bg-secondary text-light">Datos Terminal</div>
	<div class="card-body">
		<div class="form-row mt-3">
			<div class="form-group col-lg-6 col-md-6">
				
				<label>Nro</label>
				@if(Request::segment(2)=='create' || Request::segment(1)=='reparacionOnsite' )
					<input id="nro" name="nro" type="text" value="" class="form-control" placeholder="Ingrese nro">
				@else
					<input id="nro" name="nro" type="text" readonly class="form-control" placeholder="Ingrese nro" value="{{ $terminalOnsite->nro }}">
				@endif
			</div>

			<div class="form-group col-lg-6 col-md-6">
				<label for="all_terminales_sucursal">All Terminales - Sucursal</label>
				<input type="checkbox" id="all_terminales_sucursal" name="all_terminales_sucursal" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="primary" data-offstyle="secondary" {{ (( isset($terminalOnsite) && isset($terminalOnsite->all_terminales_sucursal) && $terminalOnsite->all_terminales_sucursal) ? 'checked' : '') }}>
			</div>

			<div class="form-group col-lg-12 col-md-12 logica-terminal-sucursal-modal">
				<label>Sucursal Onsite Id</label>

				<div class="form-group input-group ">
					<select name="sucursal_onsite_id" id="terminal_sucursal_onsite_id" class=" multiselect-dropdown form-control mr-1" placeholder='Seleccione sucursal onsite id'>
						<option value=""> -- Seleccione uno --</option>
						@foreach ($sucursalesOnsite ?? '' as $sucursalOnsite)
							<option value="{{ $sucursalOnsite->id }}" {{ ((isset($terminalOnsite) && isset($terminalOnsite->sucursal_onsite_id) && $terminalOnsite->sucursal_onsite_id == $sucursalOnsite->id)?'selected':'') }}>{{ $sucursalOnsite->codigo_sucursal .' - '.$sucursalOnsite->razon_social }}</option>
						@endforeach
					</select>

					<span class="input-group-btn">
						<button class="btn btn-warning " type="button" id="refreshSucursal"><i class="fa fa-reply-all"></i></button>
					</span>
				</div>
			</div>
                <div class="form-group col-lg-6 col-md-6">
                    <label>Marca</label>
                    <input type="text" name='marca' id="marca" class='form-control' placeholder='Ingrese marca' value="{{ ( ( isset($terminalOnsite) && !empty($terminalOnsite)) ? $terminalOnsite->marca : null) }}">
                </div>

			<div class="form-group col-lg-6 col-md-6">
				<label>Modelo</label>
				<input type="text" name='modelo' id="modelo" class='form-control' placeholder='Ingrese modelo' value="{{ ( ( isset($terminalOnsite) && !empty($terminalOnsite)) ? $terminalOnsite->modelo : null) }}">
			</div>
            <div class="form-group col-lg-6 col-md-6">
                <label>Serie</label>
                <input type="text" name='serie' id="serie" class='form-control' placeholder='Ingrese serie' value="{{ ( ( isset($terminalOnsite) && !empty($terminalOnsite)) ? $terminalOnsite->serie : null) }}">
            </div>

                <div class="form-group col-lg-6 col-md-6">
                    <label>Rótulo</label>
                    <input type="text" name='rotulo' id="rotulo" class='form-control' placeholder='Ingrese rotulo' value="{{ ( ( isset($terminalOnsite) && !empty($terminalOnsite)) ? $terminalOnsite->rotulo : null) }}">
                </div>



			<div class="form-group col-lg-12 col-md-12">
				<label>Observaciones</label>
				<input type="text" name='observaciones' id="terminal_observaciones" class='form-control' placeholder='Ingrese observaciones' value="{{ ( ( isset($terminalOnsite) && !empty($terminalOnsite)) ? $terminalOnsite->observaciones : null) }}">
			</div>

		</div>

	</div>
</div>
