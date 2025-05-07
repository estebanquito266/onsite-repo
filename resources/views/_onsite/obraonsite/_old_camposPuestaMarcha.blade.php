<div class="main-card mb-3 card formulario_obra" hidden>
	<div class="card-body">
		<div id="smartwizard" class="sw-main sw-theme-default">
			<ul class="forms-wizard nav nav-tabs step-anchor">

				<li class="nav-item active">
					<a href="#step-1" class="nav-link">
						<em>1</em><span>Empresa y Obra</span>
					</a>
				</li>

				<li class="nav-item active">
					<a href="#step-2" class="nav-link">
						<em>2</em><span>Sistema</span>
					</a>
				</li>

				<li class="nav-item active">
					<a href="#step-3" class="nav-link">
						<em>3</em><span>Estado</span>
					</a>
				</li>

				<li class="nav-item done">
					<a href="#step-4" class="nav-link">
						<em>4</em><span>Req. acceso</span>
					</a>
				</li>

				<li class="nav-item done">
					<a href="#step-5" class="nav-link">
						<em>5</em><span>Finalizar</span>
					</a>
				</li>
			</ul>
			<div class="form-wizard-content sw-container tab-content" style="min-height: 478.734px;">


				<!-- empresa y obra ############### -->
				<div id="step-1" class="tab-pane step-content" style="display: block;">

					<div class="form-row mt-3">
						<div class="form-group col-12 col-md-4">
							<label>Nombre de la Empresa</label>
							<input readonly type="text" name='empresa_instaladora_nombre' id="empresa_instaladora_nombre" class='form-control' placeholder='Ingrese empresa_instaladora_nombre' value="{{ (isset($user)?$user->empresa_instaladora[0]->nombre:null) }}">
							<input type="hidden" name="empresa_instaladora_id" id="empresa_instaladora_id" value="{{ (isset($user)?$user->empresa_instaladora[0]->id:null) }}">
						</div>

						<div class="form-group col-12 col-md-4">
							<label>Email </label>
							<input readonly type="email" name='responsable_email' id="responsable_email" class='form-control' placeholder='Ingrese responsable_email' value="{{ (isset($user)?$user->empresa_instaladora[0]->email:null) }}">
						</div>

						<div class="form-group col-12 col-md-4">
							<label>Teléfono </label>
							<input readonly type="number" name='responsable_telefono' class='form-control' placeholder='Ingrese responsable_telefono' value="{{ (isset($user)?$user->empresa_instaladora[0]->celular:null) }}">
						</div>

					</div>



					<div class="row pt-3">
						<div class="col-12">
							<div class="form-group">
								<label for="solicitud_tipo_id">Tipo de Solicitud</label>
								<select class="form-control multiselect-dropdown" name="solicitud_tipo_id" id="solicitud_tipo_id">
									@foreach ($solicitudesTipos as $tipo)
									<option value=" {{$tipo->id}} ">{{$tipo->nombre}}</option>
									@endforeach
								</select>
							</div>
						</div>
					</div>

					<label>Obra y Sistemas</label>
					<div class="row">
						<div class="col-8">
							<select name="sistema_onsite_id" id="sistema_onsite_id" class="form-control multiselect-dropdown">
								<option value="0"> -- Seleccione uno --</option>
								@if(isset($obrasOnsite))
								@foreach ($obrasOnsite as $obra)
								<optgroup label="Obra: {{$obra->nombre}}">
									@foreach ($obra->sistema_onsite as $sistema)
									<option value="{{ $sistema->id }}" data-idobra="{{ $sistema->obra_onsite_id }}" data-nombre_sistema="{{$sistema->nombre}}" @if( Request::segment(3)=='edit' ) {{(isset($garantiaOnsite) && $garantiaOnsite->sistema_onsite_id == $sistema->id) ? 'selected': null}} @else {{old('sistema_onsite_id') == $sistema->id ? 'selected': null}} @endif>{{$sistema->nombre}} (Obra: {{$obra->nombre}})
									</option>
									@endforeach
								</optgroup>
								@endforeach
								@endif
							</select>
						</div>

						<div class="input-group-append col-2">
							<button id="refreshsistemasbutton" class="btn btn-secondary"><i class="pe-7s-refresh"> </i></button>
						</div>

						<div class="input-group-append col-2">
							<button id="addobrabutton" class="btn btn-success"><i class="pe-7s-refresh"> </i></button>
						</div>

					</div>




					<hr>

					<div class="card-body row form-group px-2" id="datos_obra_div" hidden>

						<div class="form-group col-12">
							<h3 class="card-title">Datos de la Obra</h3>
							<hr>
						</div>

						<div class="form-group col-12  col-md-6">
							<label>Nombre</label>
							<input type="text" name='nombre' id="nombre" class='form-control' placeholder='Ingrese nombre' value="{{ old('nombre') }}">
						</div>

						<div class="form-group col-12  col-md-6">
							<label>Nro Cliente BGH Ecosmart</label>
							<input type="text" name='nro_cliente_bgh_ecosmart' id="nro_cliente_bgh_ecosmart" class='form-control' placeholder='Ingrese nro_cliente_bgh_ecosmart' value="{{ old('nro_cliente_bgh_ecosmart') }}">
						</div>

						<div class="form-group col-12 col-md-6">
							<label>País</label>
							<select name="pais" id="select_pais" class="form-control multiselect-dropdown  mb-3">
								<option value="DEFAULT">Seleccione Pais...</option>
								<option value="Argentina" selected>Argentina</option>
								<option value="Brasil">Brasil</option>
								<option value="Bolivia">Bolivia</option>
								<option value="Chile">Chile</option>
								<option value="Paraguay">Paraguay</option>
								<option value="Perú">Perú</option>
								<option value="Uruguay">Uruguay</option>
							</select>
						</div>

						<div class="form-group  col-md-6" id="provincia_div" hidden>
							<label>Provincia</label>
							<select name="provincia_onsite_id" id="provincia" class="form-control multiselect-dropdown  mb-3">
								<option value="DEFAULT" selected="">Seleccione Provincia...</option>
								@foreach ($provincias as $provincia)
								<option value="{{ $provincia->id }}" {{old('provincia')==$provincia->id ? 'selected':null}}>{{$provincia->nombre}}</option>
								@endforeach
							</select>
						</div>

						<div class="form-group  col-12 col-md-6" id="localidad_div">
							<label>Localidad</label>
							<select name="localidad" id="localidad" class="form-control multiselect-dropdown  mb-3">
								<option value="DEFAULT" selected="">Seleccione Localidad...</option>
								@foreach ($localidades as $localidad)
								<option value="{{ $localidad->id }}" {{(isset($garantiaOnsite) && $garantiaOnsite->comprador_onsite->localidad_onsite_id == $localidad->id) ? 'selected': null}}>{{$localidad->localidad}}</option>
								@endforeach
							</select>
						</div>


						<div class="form-group col-lg-12 col-md-6">
							<label>Domicilio</label>
							<input type="text" name='domicilio' id="domicilio" class='form-control' placeholder='Ingrese domicilio' value="{{ old('domicilio') }}">
						</div>


						<input type="hidden" name='cantidad_unidades_exteriores' id="cantidad_unidades_exteriores" class='form-control' placeholder='Ingrese cantidad_unidades_exteriores' value="{{ old('cantidad_unidades_exteriores', 1)}}">


						<input type="hidden" name='cantidad_unidades_interiores' id="cantidad_unidades_interiores" class='form-control' placeholder='Ingrese cantidad_unidades_interiores' value="{{ old('cantidad_unidades_interiores', 1) }}">

					</div>



				</div>

				<!-- sistema ############### -->
				<div id="step-2" class="tab-pane step-content" style="display: block;">
					<div class="form-row mt-3">

						<div class="form-group col-4">
							<label>Nombre de la Empresa</label>
							<input readonly type="text" name='empresa_instaladora_nombre' id="empresa_instaladora_nombre" class='form-control' placeholder='Ingrese empresa_instaladora_nombre' value="{{ (isset($user)?$user->empresa_instaladora[0]->nombre:null) }}">
							<input type="hidden" name="empresa_instaladora_id" id="empresa_instaladora_id" value="{{ (isset($user)?$user->empresa_instaladora[0]->id:null) }}">
						</div>


						<div class=" form-group col-1">
							<label>id</label>
							<input readonly type="text" class="form-control" name="obra_onsite_id" id="obra_onsite_id" value="{{old ('obra_onsite_id') }}">
						</div>
						<div class=" form-group col-4">
							<label>Obra</label>

							<input readonly type="text" class="form-control" name="obra_nombre" id="obra_nombre">
						</div>
						<div class=" form-group col-1">
							<label>Clave</label>
							<input readonly type="text" class="form-control" name="empresa_onsite_id" id="empresa_onsite_id">
						</div>

						<div class="form-group col-12 logica-sistema	-sucursal-modal">
							<label>Sucursal</label>
							<select name="sucursal_onsite_id" id="sucursal_onsite_id" class="form-control multiselect-dropdown" placeholder='Seleccione Sucursal onsite id' {{ ((isset($sistemaEditar))?'disabled':'') }}>
								<option value="1">Suc 1</option>
								<option value="2">Suc 2</option>

							</select>
						</div>

						<div class=" form-group col-12">
							<label>Nombre Sistema</label>
							<input type="text" class="form-control" name="sistema_nombre" id="sistema_nombre" required>
						</div>

						<div class="form-group col-md-12 logica-sistema-sucursal-modal">
							<label>Comentarios</label>
							<input type="text" class="form-control" name="comentarios" id="comentarios">
							<small class="form-text text-muted">Complete los datos del sistema y de click en Guardar para cada sistema que desee agregar</small>

						</div>

						<div class="form-group">
							<button type="submit" class="btn btn-warning  mt-2" id="botonGuardar" value="1">Guardar</button>

							<button type="reset" class="btn btn-secondary  mt-2">Resetear</button>
						</div>

					</div>
				</div>

				<!-- estado obra ############### -->
				<div id="step-3" class="tab-pane step-content" style="display: block;">
					<div class="form-row mt-3">


						<div class="form-group col-lg-6 col-md-6">
							<label>Avance de obra (%)</label>
							<input type="number" name='estado' class='form-control' id="estado" placeholder='Ingrese avance de obra (%)' value="{{ old('estado') }}">
						</div>

						<div class="form-group col-lg-12 col-md-12">
							<label>Detalle del estado</label>
							<input type="text" name='estado_detalle' id="estado_detalle" class='form-control' placeholder='Ingrese estado_detalle' value="{{ old ('detalle_estado')}}">
						</div>

						<div class="form-group col-lg-6 col-md-6">
							<label>Esquema</label>
							<input type="file" name='esquema_archivo' id="esquema_archivo" class="d-block" placeholder="Seleccione el archivo" value="{{ (isset($obraOnsite)?$obraOnsite->esquema:null) }}">
						</div>

						<div class="form-group col-lg-6 col-md-6">

							<label>Esquema</label>
							<br>
							<div id="esquema_obra">

							</div>

						</div>

					</div>
				</div>

				<!-- Requisitos Acceso -->
				<div id="step-4" class="tab-pane step-content" style="display: none;">
					<div class="form-row mt-3">
						<div class="row form-group col-lg-12 col-md-12 pt-1 mt-1 border-top">
							<div class="form-check col-lg-6 col-md-6 mt-2">

								<label class="form-check-label" for="requiere_zapatos_seguridad">
									Requiere zapatos de seguridad
								</label>
								<input type="checkbox" id="requiere_zapatos_seguridad" name="requiere_zapatos_seguridad" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="primary" data-offstyle="secondary" {{ old('requiere_zapatos_seguridad')==true ? 'checked' : '' }}>
							</div>

							<div class="form-check col-lg-6 col-md-6  mt-2 zapatos">

								<label class="form-check-label" for="requiere_casco_seguridad">
									Requiere cascos de seguridad
								</label>
								<input type="checkbox" id="requiere_casco_seguridad" name="requiere_casco_seguridad" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="primary" data-offstyle="secondary" {{ (isset($obraChecklistOnsite) && $obraChecklistOnsite->requiere_casco_seguridad) ? 'checked' : '' }}>
							</div>

							<div class="form-check col-lg-6 col-md-6 mt-2">

								<label class="form-check-label" for="requiere_proteccion_visual">
									Requiere protección visual
								</label>
								<input type="checkbox" id="requiere_proteccion_visual" name="requiere_proteccion_visual" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="primary" data-offstyle="secondary" {{ (isset($obraChecklistOnsite) && $obraChecklistOnsite->requiere_proteccion_visual) ? 'checked' : '' }}>
							</div>
							<div class="form-check col-lg-6 col-md-6 mt-2">

								<label class="form-check-label" for="requiere_proteccion_auditiva">
									Requiere protección auditiva
								</label>
								<input type="checkbox" id="requiere_proteccion_auditiva" name="requiere_proteccion_auditiva" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="primary" data-offstyle="secondary" {{ (isset($obraChecklistOnsite) && $obraChecklistOnsite->requiere_proteccion_auditiva) ? 'checked' : '' }}>
							</div>
							<div class="form-check col-lg-6 col-md-6 mt-2">

								<label class="form-check-label" for="requiere_art">
									Requiere Art.
								</label>
								<input type="checkbox" id="requiere_art" name="requiere_art" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="primary" data-offstyle="secondary" {{ (isset($obraChecklistOnsite) && $obraChecklistOnsite->requiere_art) ? 'checked' : '' }}>
							</div>
							<div class="form-check col-lg-6 col-md-6 mt-2">

								<label class="form-check-label" for="clausula_no_arrepentimiento">
									Cláusula de No Repetición
								</label>
								<input type="checkbox" id="clausula_no_arrepentimiento" name="clausula_no_arrepentimiento" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="primary" data-offstyle="secondary" {{ (isset($obraChecklistOnsite) && $obraChecklistOnsite->clausula_no_arrepentimiento) ? 'checked' : '' }}>
							</div>
						</div>
						<div class="form-group col-lg-6 col-md-6">
							<label>CUIT</label>
							<input type="number" name='cuit' id="cuit" class='form-control' placeholder='Ingrese cuit' value="{{ (isset($obraChecklistOnsite)?$obraChecklistOnsite->cuit:null) }}">
						</div>
						<div class="form-group col-lg-6 col-md-6">
							<label>Razón Social</label>
							<input type="text" name='razon_social' id="razon_social" class='form-control' placeholder='Ingrese razón social' value="{{ (isset($obraChecklistOnsite)?$obraChecklistOnsite->razon_social:null) }}">
						</div>
						<div class="form-group col-lg-12 col-md-12">
							<label>CNR Detalle</label>
							<input type="text" name='cnr_detalle' id="cnr_detalle" class='form-control' placeholder='Ingrese detalle' value="{{ (isset($obraChecklistOnsite)?$obraChecklistOnsite->cnr_detalle:null) }}">
						</div>

					</div>
				</div>

				<!-- Confirma Formulario -->
				<div id="step-5" class="tab-pane step-content" style="display: none;">
					<div class="no-results">
						<div class="swal2-icon swal2-success swal2-animate-success-icon">
							<div class="swal2-success-circular-line-left" style="background-color: rgb(255, 255, 255);"></div>
							<span class="swal2-success-line-tip"></span>
							<span class="swal2-success-line-long"></span>
							<div class="swal2-success-ring"></div>
							<div class="swal2-success-fix" style="background-color: rgb(255, 255, 255);"></div>
							<div class="swal2-success-circular-line-right" style="background-color: rgb(255, 255, 255);"></div>
						</div>

						<div class="swal2-icon swal2-error swal2-animate-error-icon" style="display: flex;" hidden>
							<span class="swal2-x-mark">
								<span class="swal2-x-mark-line-left"></span>
								<span class="swal2-x-mark-line-right"></span>
							</span>
						</div>

						<!-- <div class="results-subtitle mt-4">Completo!</div> -->
						<div class="results-subtitle mt-4 resumen_form"></div>

						<!-- <div class="results-title">Click para enviar</div> -->
						<div class="mt-3 mb-3"></div>
						<div class="text-center">
							<button class="btn-shadow btn-wide btn btn-success btn-lg" id="boton_enviar">Enviar</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="divider"></div>
		<div class="card-body clearfix">
			<button type="button" id="reset-btn" class="btn-shadow float-left btn btn-link">Reset</button>
			<button type="button" id="next-btn" class="btn-shadow btn-wide float-right btn-pill btn-hover-shine btn btn-primary">Siguiente</button>
			<button type="button" id="prev-btn" class="btn-shadow float-right btn-wide btn-pill mr-3 btn btn-outline-secondary">Anterior</button>
		</div>
	</div>
</div>