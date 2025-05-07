@if( $companyId == \App\Models\Company::DEFAULT && Request::segment(2) != 'create' )
@if( !in_array( $reparacionOnsite->id_empresa_onsite, Array(\App\Models\Onsite\EmpresaOnsite::QUILMES, \App\Models\Onsite\EmpresaOnsite::NIKE, \App\Models\Onsite\EmpresaOnsite::YPF) ) )
@if($reparacionOnsite->reparacion_detalle)
<div class="main-card mb-3 card">
	<div class="card-header bg-alternate text-white">Activo
	</div>
	<div class="card-body">
		<div id="activo_1" class=" form-row mt-3 " >
			<div class="form-group col-lg-4 col-md-4 ">
				<label>Código Activo (nuevo) 1</label>
				<input type="text" class="form-control" placeholder="Ingrese código activo (nuevo) 1" name="codigo_activo_nuevo1" value="{{ $reparacionOnsite->reparacion_detalle->codigo_activo_nuevo1 }}">
			</div>
			<div class="form-group col-lg-4 col-md-4 ">
				<label>Código Activo (retirado) 1</label>
				<input type="text" class="form-control" placeholder="Ingrese código activo (retirado) 1" name="codigo_activo_retirado1" value="{{ $reparacionOnsite->reparacion_detalle->codigo_activo_retirado1 }}">
			</div>
			<div class="form-group col-lg-4 col-md-4 ">
				<label>Descripción 1</label>
				<input type="text" class="form-control" placeholder="Ingrese descripción 1" name="codigo_activo_descripcion1" value="{{ $reparacionOnsite->reparacion_detalle->codigo_activo_descripcion1 }}">
			</div>
		</div>
		<div id="activo_2" class=" form-row mt-3 {{ (($reparacionOnsite->reparacion_detalle->codigo_activo_nuevo2 || $reparacionOnsite->reparacion_detalle->codigo_activo_retirado2 ||  $reparacionOnsite->reparacion_detalle->codigo_activo_descripcion2)?'':'d-none') }}">
			<div class="form-group col-lg-4 col-md-4 ">
				<label>Código Activo (nuevo) 2</label>
				<input type="text" class="form-control" placeholder="Ingrese código activo (nuevo) 2" name="codigo_activo_nuevo2" value="{{ $reparacionOnsite->reparacion_detalle->codigo_activo_nuevo2 }}">
			</div>
			<div class="form-group col-lg-4 col-md-4 ">
				<label>Código Activo (retirado) 2</label>
				<input type="text" class="form-control" placeholder="Ingrese código activo (retirado) 2" name="codigo_activo_retirado2" value="{{ $reparacionOnsite->reparacion_detalle->codigo_activo_retirado2 }}">
			</div>
			<div class="form-group col-lg-4 col-md-4 ">
				<label>Descripción 2</label>
				<input type="text" class="form-control" placeholder="Ingrese descripción 2" name="codigo_activo_descripcion2" value="{{ $reparacionOnsite->reparacion_detalle->codigo_activo_descripcion2 }}">
			</div>
		</div>
		<div id="activo_3" class=" form-row mt-3 {{ (($reparacionOnsite->reparacion_detalle->codigo_activo_nuevo3 || $reparacionOnsite->reparacion_detalle->codigo_activo_retirado3 ||  $reparacionOnsite->reparacion_detalle->codigo_activo_descripcion3)?'':'d-none') }}">
			<div class="form-group col-lg-4 col-md-4 ">
				<label>Código Activo (nuevo) 3</label>
				<input type="text" class="form-control" placeholder="Ingrese código activo (nuevo) 3" name="codigo_activo_nuevo3" value="{{ $reparacionOnsite->reparacion_detalle->codigo_activo_nuevo3 }}">
			</div>
			<div class="form-group col-lg-4 col-md-4 ">
				<label>Código Activo (retirado) 3</label>
				<input type="text" class="form-control" placeholder="Ingrese código activo (retirado) 3" name="codigo_activo_retirado3" value="{{ $reparacionOnsite->reparacion_detalle->codigo_activo_retirado3 }}">
			</div>
			<div class="form-group col-lg-4 col-md-4 ">
				<label>Descripción 3</label>
				<input type="text" class="form-control" placeholder="Ingrese descripción 3" name="codigo_activo_descripcion3" value="{{ $reparacionOnsite->reparacion_detalle->codigo_activo_descripcion3 }}">
			</div>
		</div>
		<div id="activo_4" class=" form-row mt-3 {{ (($reparacionOnsite->reparacion_detalle->codigo_activo_nuevo4 || $reparacionOnsite->reparacion_detalle->codigo_activo_retirado4 ||  $reparacionOnsite->reparacion_detalle->codigo_activo_descripcion4)?'':'d-none') }}">
			<div class="form-group col-lg-4 col-md-4 ">
				<label>Código Activo (nuevo) 4</label>
				<input type="text" class="form-control" placeholder="Ingrese código activo (nuevo) 4" name="codigo_activo_nuevo4" value="{{ $reparacionOnsite->reparacion_detalle->codigo_activo_nuevo4 }}">
			</div>
			<div class="form-group col-lg-4 col-md-4 ">
				<label>Código Activo (retirado) 4</label>
				<input type="text" class="form-control" placeholder="Ingrese código activo (retirado) 4" name="codigo_activo_retirado4" value="{{ $reparacionOnsite->reparacion_detalle->codigo_activo_retirado4 }}">
			</div>
			<div class="form-group col-lg-4 col-md-4 ">
				<label>Descripción 4</label>
				<input type="text" class="form-control" placeholder="Ingrese descripción 4" name="codigo_activo_descripcion4" value="{{ $reparacionOnsite->reparacion_detalle->codigo_activo_descripcion4 }}">
			</div>
		</div>
		<div id="activo_5" class=" form-row mt-3 {{ (($reparacionOnsite->reparacion_detalle->codigo_activo_nuevo5 || $reparacionOnsite->reparacion_detalle->codigo_activo_retirado5 ||  $reparacionOnsite->reparacion_detalle->codigo_activo_descripcion5)?'':'d-none') }}">
			<div class="form-group col-lg-4 col-md-4 ">
				<label>Código Activo (nuevo) 5</label>
				<input type="text" class="form-control" placeholder="Ingrese código activo (nuevo) 5" name="codigo_activo_nuevo5" value="{{ $reparacionOnsite->reparacion_detalle->codigo_activo_nuevo5 }}">
			</div>
			<div class="form-group col-lg-4 col-md-4 ">
				<label>Código Activo (retirado) 5</label>
				<input type="text" class="form-control" placeholder="Ingrese código activo (retirado) 5" name="codigo_activo_retirado5" value="{{ $reparacionOnsite->reparacion_detalle->codigo_activo_retirado5 }}">
			</div>
			<div class="form-group col-lg-4 col-md-4 ">
				<label>Descripción 5</label>
				<input type="text" class="form-control" placeholder="Ingrese descripción 5" name="codigo_activo_descripcion5" value="{{ $reparacionOnsite->reparacion_detalle->codigo_activo_descripcion5 }}">
			</div>
		</div>
		<div id="activo_6" class=" form-row mt-3 {{ (($reparacionOnsite->reparacion_detalle->codigo_activo_nuevo6 || $reparacionOnsite->reparacion_detalle->codigo_activo_retirado6 ||  $reparacionOnsite->reparacion_detalle->codigo_activo_descripcion6)?'':'d-none') }}">
			<div class="form-group col-lg-4 col-md-4 ">
				<label>Código Activo (nuevo) 6</label>
				<input type="text" class="form-control" placeholder="Ingrese código activo (nuevo) 6" name="codigo_activo_nuevo6" value="{{ $reparacionOnsite->reparacion_detalle->codigo_activo_nuevo6 }}">
			</div>
			<div class="form-group col-lg-4 col-md-4 ">
				<label>Código Activo (retirado) 6</label>
				<input type="text" class="form-control" placeholder="Ingrese código activo (retirado) 6" name="codigo_activo_retirado6" value="{{ $reparacionOnsite->reparacion_detalle->codigo_activo_retirado6 }}">
			</div>
			<div class="form-group col-lg-4 col-md-4 ">
				<label>Descripción 6</label>
				<input type="text" class="form-control" placeholder="Ingrese descripción 6" name="codigo_activo_descripcion6" value="{{ $reparacionOnsite->reparacion_detalle->codigo_activo_descripcion6 }}">
			</div>
		</div>
		<div id="activo_7" class=" form-row mt-3 {{ (($reparacionOnsite->reparacion_detalle->codigo_activo_nuevo7 || $reparacionOnsite->reparacion_detalle->codigo_activo_retirado7 ||  $reparacionOnsite->reparacion_detalle->codigo_activo_descripcion7)?'':'d-none') }}">
			<div class="form-group col-lg-4 col-md-4 ">
				<label>Código Activo (nuevo) 7</label>
				<input type="text" class="form-control" placeholder="Ingrese código activo (nuevo) 7" name="codigo_activo_nuevo7" value="{{ $reparacionOnsite->reparacion_detalle->codigo_activo_nuevo7 }}">
			</div>
			<div class="form-group col-lg-4 col-md-4 ">
				<label>Código Activo (retirado) 7</label>
				<input type="text" class="form-control" placeholder="Ingrese código activo (retirado) 7" name="codigo_activo_retirado7" value="{{ $reparacionOnsite->reparacion_detalle->codigo_activo_retirado7 }}">
			</div>
			<div class="form-group col-lg-4 col-md-4 ">
				<label>Descripción 7</label>
				<input type="text" class="form-control" placeholder="Ingrese descripción 7" name="codigo_activo_descripcion7" value="{{ $reparacionOnsite->reparacion_detalle->codigo_activo_descripcion7 }}">
			</div>
		</div>
		<div id="activo_8" class=" form-row mt-3 {{ (($reparacionOnsite->reparacion_detalle->codigo_activo_nuevo8 || $reparacionOnsite->reparacion_detalle->codigo_activo_retirado8 ||  $reparacionOnsite->reparacion_detalle->codigo_activo_descripcion8)?'':'d-none') }}">
			<div class="form-group col-lg-4 col-md-4 ">
				<label>Código Activo (nuevo) 8</label>
				<input type="text" class="form-control" placeholder="Ingrese código activo (nuevo) 8" name="codigo_activo_nuevo8" value="{{ $reparacionOnsite->reparacion_detalle->codigo_activo_nuevo8 }}">
			</div>
			<div class="form-group col-lg-4 col-md-4 ">
				<label>Código Activo (retirado) 8</label>
				<input type="text" class="form-control" placeholder="Ingrese código activo (retirado) 8" name="codigo_activo_retirado8" value="{{ $reparacionOnsite->reparacion_detalle->codigo_activo_retirado8 }}">
			</div>
			<div class="form-group col-lg-4 col-md-4 ">
				<label>Descripción 8</label>
				<input type="text" class="form-control" placeholder="Ingrese descripción 8" name="codigo_activo_descripcion8" value="{{ $reparacionOnsite->reparacion_detalle->codigo_activo_descripcion8 }}">
			</div>
		</div>
		<div id="activo_9" class=" form-row mt-3 {{ (($reparacionOnsite->reparacion_detalle->codigo_activo_nuevo9 || $reparacionOnsite->reparacion_detalle->codigo_activo_retirado9 ||  $reparacionOnsite->reparacion_detalle->codigo_activo_descripcion9)?'':'d-none') }}">
			<div class="form-group col-lg-4 col-md-4 ">
				<label>Código Activo (nuevo) 9</label>
				<input type="text" class="form-control" placeholder="Ingrese código activo (nuevo) 9" name="codigo_activo_nuevo9" value="{{ $reparacionOnsite->reparacion_detalle->codigo_activo_nuevo9 }}">
			</div>
			<div class="form-group col-lg-4 col-md-4 ">
				<label>Código Activo (retirado) 9</label>
				<input type="text" class="form-control" placeholder="Ingrese código activo (retirado) 9" name="codigo_activo_retirado9" value="{{ $reparacionOnsite->reparacion_detalle->codigo_activo_retirado9 }}">
			</div>
			<div class="form-group col-lg-4 col-md-4 ">
				<label>Descripción 9</label>
				<input type="text" class="form-control" placeholder="Ingrese descripción 9" name="codigo_activo_descripcion9" value="{{ $reparacionOnsite->reparacion_detalle->codigo_activo_descripcion9 }}">
			</div>
		</div>
		<div id="activo_10" class=" form-row mt-3 {{ (($reparacionOnsite->reparacion_detalle->codigo_activo_nuevo10 || $reparacionOnsite->reparacion_detalle->codigo_activo_retirado10 ||  $reparacionOnsite->reparacion_detalle->codigo_activo_descripcion10)?'':'d-none') }}">
			<div class="form-group col-lg-4 col-md-4 ">
				<label>Código Activo (nuevo) 10</label>
				<input type="text" class="form-control" placeholder="Ingrese código activo (nuevo) 10" name="codigo_activo_nuevo10" value="{{ $reparacionOnsite->reparacion_detalle->codigo_activo_nuevo10 }}">
			</div>
			<div class="form-group col-lg-4 col-md-4 ">
				<label>Código Activo (retirado) 10</label>
				<input type="text" class="form-control" placeholder="Ingrese código activo (retirado) 10" name="codigo_activo_retirado10" value="{{ $reparacionOnsite->reparacion_detalle->codigo_activo_retirado10 }}">
			</div>
			<div class="form-group col-lg-4 col-md-4 ">
				<label>Descripción 10</label>
				<input type="text" class="form-control" placeholder="Ingrese descripción 10" name="codigo_activo_descripcion10" value="{{ $reparacionOnsite->reparacion_detalle->codigo_activo_descripcion10 }}">
			</div>
		</div>
		<div class=" form-row mt-3 ">
		<div class="form-group col-md-12 text-center">
				<button type="button" id="btn_nuevo_activo" class="btn btn-secondary" value="2">Mostrar Nuevo Activo</button>
			</div>
		</div>
		<div class=" form-row mt-3 ">
			<div class="form-group col-lg-6 col-md-6 ">
				<label>Modem 3G/4G SIM nuevo</label>
				<input type="text" class="form-control" placeholder="Ingrese  modem 3g 4g sim nuevo" name="modem_3g_4g_sim_nuevo" value="{{ $reparacionOnsite->reparacion_detalle->modem_3g_4g_sim_nuevo }}">
			</div>
			<div class="form-group col-lg-6 col-md-6 ">
				<label>Modem 3G/4G SIM retirado</label>
				<input type="text" class="form-control" placeholder="Ingrese modem 3g 4g sim retirado" name="modem_3g_4g_sim_retirado" value="{{ $reparacionOnsite->reparacion_detalle->modem_3g_4g_sim_retirado }}">
			</div>
		</div>
	</div>
</div>
@endif
@endif
@endif