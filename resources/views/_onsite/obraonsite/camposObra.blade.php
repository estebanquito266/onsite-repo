<link rel="stylesheet" href="/assets/css/dragdropfiles.css" type="text/css">


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
						<em>2</em><span>Requisitos</span>
					</a>
				</li>

				<li class="nav-item done">
					<a href="#step-3" class="nav-link">
						<em>3</em><span>Sistemas</span>
					</a>
				</li>

				<li class="nav-item done">
					<a href="#step-4" class="nav-link">
						<em>4</em><span>Finalizar</span>
					</a>
				</li>
			</ul>
			<div class="form-wizard-content sw-container tab-content" style="min-height: 478.734px;">


				<!-- empresa y obra ############### -->
				<div id="step-1" class="tab-pane step-content " style="display: block;">

					<div class="form-row mt-3 empresa_obra1">
						@if( Session::get('perfilAdmin') || Session::get('perfilAdminOnsite'))
						<div class="form-group col-12 col-md-12">
							<label>Seleccione Empresa Instaladora</label>
							<select name="empresa_instaladora_admins" id="empresa_instaladora_admins" class="multiselect-dropdown form-control">
								<option value="0">Seleccione</option>
								@foreach ($empresas_instaladoras_admins as $empresa)
								<option value="{{ $empresa->id }}" data-numero="{{$empresa->numero_cuenta_bgh}}" data-email="{{ $empresa->email }}" data-nombre="{{ $empresa->nombre }}" data-telefono="{{ $empresa->celular }}" {{old('empresa')==$empresa->id ? 'selected':null}}>{{$empresa->nombre}}</option>
								@endforeach
							</select>
						</div>

						<div class="form-group col-3">
							<label>Empresa Instaladora</label>
							<input readonly name='empresa_instaladora_nombre' id="empresa_instaladora_nombre" class='form-control'>
							<input readonly type="hidden" name="empresa_instaladora_id" id="empresa_instaladora_id">
						</div>
						<div class="form-group col-3">
							<label>Email </label>
							<input readonly name='responsable_email' id="responsable_email" class='form-control'>
						</div>
						<div class="form-group col-2">
							<label>Nombre </label>
							<input readonly name='responsable_nombre' id="responsable_nombre" class='form-control'>
						</div>
						<div class="form-group col-2">
							<label>Teléfono </label>
							<input readonly name='responsable_telefono' id='responsable_telefono' class='form-control'>
						</div>

						<div class="form-group col-2">
							<label>Nro BGH</label>
							<input readonly type="text" name='nro_cliente_bgh_ecosmart' id="nro_cliente_bgh_ecosmart" class='form-control'>
						</div>

						@else
						<div class="form-group  col-3">
							<label>Empresa Instaladora</label>
							<input readonly name='empresa_instaladora_nombre' id="empresa_instaladora_nombre" class='form-control' value="{{ (isset($user)?$user->empresa_instaladora[0]->nombre:null) }}">
							<input type="hidden" name="empresa_instaladora_id" id="empresa_instaladora_id" value="{{ (isset($user)?$user->empresa_instaladora[0]->id:null) }}">
						</div>
						<div class="form-group  col-3">
							<label>Email </label>
							<input readonly name='responsable_email' id="responsable_email" class='form-control' placeholder='Ingrese responsable_email' value="{{ (isset($user)?$user->empresa_instaladora[0]->email:null) }}">
						</div>
						<div class="form-group  col-2">
							<label>Responsable </label>
							<input readonly name='responsable_nombre' id="responsable_nombre" class='form-control' value="{{ (isset($user)?$user->name:null) }}">
						</div>
						<div class="form-group  col-2">
							<label>Teléfono </label>
							<input readonly name='responsable_telefono' id='responsable_telefono' class='form-control' placeholder='Ingrese responsable_telefono' value="{{ (isset($user)?$user->empresa_instaladora[0]->celular:null) }}">
						</div>

						<div class="form-group   col-2">
							<label>Nro Cliente BGH Ecosmart</label>
							<input readonly type="text" name='nro_cliente_bgh_ecosmart' id="nro_cliente_bgh_ecosmart" class='form-control' placeholder='Ingrese nro_cliente_bgh_ecosmart' value="{{Auth::user()->numero_cuenta_respuestos_onsite }}">
						</div>
						@endif

					</div>

					<div class="form-row mt-3 empresa_obra">

						<div class="card-body row form-group px-2" id="datos_obra_div">
							<div class="form-group col-12">
								<h3 class="card-title">Datos de la Obra</h3>
								<hr>
							</div>

							<div class=" form-group col-12 col-md-4" hidden>
								<select hidden class="form-control multiselect-dropdown" name="empresa_onsite_id" id="empresa_onsite_id">
									<!-- se completa dinámicamente -->
								</select>
							</div>

							<div class="form-group col-12  col-md-8">
								<label>Nombre de la OBRA</label>
								<input type="text" name='nombre' id="nombre" class='form-control' placeholder='Ingrese nombre' value="{{ old('nombre') }}" autocomplete="off">
							</div>



							<div class="form-group col-12 col-md-4">
								<label>País</label>
								<select name="pais" id="select_pais" class="form-control multiselect-dropdown  mb-3">
									<option value="DEFAULT">Seleccione Pais...</option>
									<option value="Argentina" selected>Argentina</option>									
								</select>
							</div>

							<div class="form-group col-12 col-md-4" id="provincia_div" hidden>
								<label>Provincia</label>
								<select name="provincia_onsite_id" id="provincia" class="form-control multiselect-dropdown  mb-3">
									<option value="DEFAULT" selected="">Seleccione Provincia...</option>
									@foreach ($provincias as $provincia)
									<option value="{{ $provincia->id }}" {{old('provincia')==$provincia->id ? 'selected':null}}>{{$provincia->nombre}}</option>
									@endforeach
								</select>
							</div>

							<div class="form-group  col-12 col-md-4" id="localidad_div">
								<label>Localidad</label>
								<select name="localidad" id="localidad" class="form-control multiselect-dropdown  mb-3">
									<option value="DEFAULT" selected="">Seleccione Localidad...</option>
									@foreach ($localidades as $localidad)
									<option value="{{ $localidad->id }}">{{$localidad->localidad}}</option>
									@endforeach
								</select>
							</div>


							<div class="form-group col-6">
								<label>Domicilio</label>
								<input type="text" name='domicilio' id="autocomplete" class='form-control autocomplete_domicilio' placeholder='Ingrese domicilio' value="{{ old('domicilio') }}">
							</div>
							<div class="form-group col-2" id="latitudeArea">
								<!-- <label>Latitud</label> -->
								<input hidden type="text" id="latitude" name="latitud" class="form-control">
							</div>
							<div class="form-group col-2" id="longtitudeArea">
								<!-- <label>Longitud</label> -->
								<input hidden type="text" name="longitud" id="longitude" class="form-control">
							</div>

							<div class="form-group col-2">
								<!-- <label>
									Obra VIP
								</label> -->
								<br>
								<div hidden>
									<input class="form-control" type="checkbox" id="vip" name="vip" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="primary" data-offstyle="secondary" {{ (isset($obraChecklistOnsite) && $obraChecklistOnsite->vip) ? 'checked' : '' }}>
								</div>

							</div>

							<div class="form-group col-12">
								<label>Esquema</label>
								<div class="file-drop-area card mt-2 ml-2 mr-2">

									<span class="choose-file-button">Seleccionar archivos</span>
									<span class="file-message mt-2">o arrastrar archivos aquí</span>
									<input multiple type="file" name='esquema_archivo' id="esquema_archivo" class="file-input" placeholder="Seleccione el archivo" value="{{ (isset($obraOnsite)?$obraOnsite->esquema:null) }}">
								</div>
							</div>


							<input type="hidden" name='cantidad_unidades_exteriores' id="cantidad_unidades_exteriores" class='form-control' placeholder='Ingrese cantidad_unidades_exteriores' value="{{ old('cantidad_unidades_exteriores', 1)}}">
							<input type="hidden" name='cantidad_unidades_interiores' id="cantidad_unidades_interiores" class='form-control' placeholder='Ingrese cantidad_unidades_interiores' value="{{ old('cantidad_unidades_interiores', 1) }}">
							<input type="hidden" name='estado' class='form-control' id="estado" value="{{ old('estado', 1) }}">
							<input type="hidden" name='estado_detalle' id="estado_detalle" class='form-control' value="{{ old ('detalle_estado', '-')}}">

						</div>
					</div>

					<div class="divider"></div>

					<div class="form-row mt-3">
						<button type="button" id="store_obra" class="btn btn-primary col-12">Guardar Obra y Continuar</button>
					</div>
				</div>
				<!-- Requisitos Acceso -->
				<div id="step-2" class="tab-pane step-content" style="display: none;">

					<div class="mt-3 ">
						<div class="row mt-3">
							<div class="form-group col-4">
								<label>id</label>
								<input readonly class="form-control" name="obra_onsite_id" id="obra_onsite_id" value="{{old ('obra_onsite_id') }}">
							</div>
							<div class="form-group col-8">
								<label>Obra</label>
								<input readonly class="form-control" name="obra_nombre" id="obra_nombre">
							</div>

						</div>

						<div class="row form-group col-lg-12 col-md-12 pt-1 mt-1 border-top checklist">
							<div class="form-check col-12 col-md-4 mt-2">

								<label class="form-check-label" for="requiere_zapatos_seguridad">
									Requiere zapatos de seguridad
								</label>
								<input type="checkbox" id="requiere_zapatos_seguridad" name="requiere_zapatos_seguridad" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="primary" data-offstyle="secondary" {{ old('requiere_zapatos_seguridad')==true ? 'checked' : '' }}>
							</div>

							<div class="form-check col-12 col-md-4  mt-2 zapatos">

								<label class="form-check-label" for="requiere_casco_seguridad">
									Requiere cascos de seguridad
								</label>
								<input type="checkbox" id="requiere_casco_seguridad" name="requiere_casco_seguridad" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="primary" data-offstyle="secondary" {{ (isset($obraChecklistOnsite) && $obraChecklistOnsite->requiere_casco_seguridad) ? 'checked' : '' }}>
							</div>

							<div class="form-check col-12 col-md-4 mt-2">

								<label class="form-check-label" for="requiere_proteccion_visual">
									Requiere protección visual
								</label>
								<input type="checkbox" id="requiere_proteccion_visual" name="requiere_proteccion_visual" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="primary" data-offstyle="secondary" {{ (isset($obraChecklistOnsite) && $obraChecklistOnsite->requiere_proteccion_visual) ? 'checked' : '' }}>
							</div>
							<div class="form-check col-12 col-md-4 mt-2">

								<label class="form-check-label" for="requiere_proteccion_auditiva">
									Requiere protección auditiva
								</label>
								<input type="checkbox" id="requiere_proteccion_auditiva" name="requiere_proteccion_auditiva" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="primary" data-offstyle="secondary" {{ (isset($obraChecklistOnsite) && $obraChecklistOnsite->requiere_proteccion_auditiva) ? 'checked' : '' }}>
							</div>


							<div class="form-check col-12 col-md-4 mt-2">
								<label class="form-check-label" for="requiere_art">
									Requiere Art.
								</label>
								<input type="checkbox" id="requiere_art" name="requiere_art" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="primary" data-offstyle="secondary" {{ (isset($obraChecklistOnsite) && $obraChecklistOnsite->requiere_art) ? 'checked' : '' }}>
							</div>

						</div>

						<div class="row div_art" id='div_art' hidden>
							<div class="form-group col-12 col-md-6 ">
								<label>CUIT</label>
								<input type="number" name='cuit' id="cuit" class='form-control' placeholder='Ingrese cuit' value="{{ (isset($obraChecklistOnsite)?$obraChecklistOnsite->cuit:null) }}">
								<small>(Si son más de una, separar por comas).</small>
							</div>
							<div class="form-group col-12 col-md-6">
								<label>Razón Social</label>
								<input type="text" name='razon_social' id="razon_social" class='form-control' placeholder='Ingrese razón social' value="{{ (isset($obraChecklistOnsite)?$obraChecklistOnsite->razon_social:null) }}" autocomplete="off">
								<small>(Si son más de una, separar por comas).</small>
							</div>
							<div class="form-check col-12 col-md-4 mt-2">
								<label class="form-check-label" for="clausula_no_arrepentimiento">
									Cláusula de No Repetición
								</label>
								<input type="checkbox" id="clausula_no_arrepentimiento" name="clausula_no_arrepentimiento" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="primary" data-offstyle="secondary" {{ (isset($obraChecklistOnsite) && $obraChecklistOnsite->clausula_no_arrepentimiento) ? 'checked' : '' }}>
							</div>

							<div class="form-group col-12 col-md-8 div_cnr" hidden>
								<label>CNR Detalle</label>
								<input type="text" name='cnr_detalle' id="cnr_detalle" class='form-control' placeholder='Ingrese detalle' value="{{ (isset($obraChecklistOnsite)?$obraChecklistOnsite->cnr_detalle:null) }}" autocomplete="off">
								<small>INDIQUE LA RAZÓN SOCIAL Y CUIT DE LOS BENEFICIARIOS DE LA CLÁUSULA DE NO REPETICIÓN (SEPARARLOS POR COMA)</small>
							</div>
						</div>

						<div class="divider"></div>

						<div class="form-row mt-3">
							<button type="button" id="store_checklist" class="btn btn-primary col-12">Guardar Requisitos y Continuar</button>
						</div>

					</div>
				</div>

				<!-- sistema ############### -->
				<div id="step-3" class="tab-pane step-content" style="display: block;">


					<div class="main-card mb-3 card sistemas">
						<div class="card-header card-header-tab  ">
							<div class="card-header-title font-size-lg text-capitalize font-weight-normal">
								<i class="header-icon pe-7s-cart mr-3 text-muted opacity-6"> </i>
								Creación de Sistemas

							</div>

							<div class="btn-actions-pane-right actions-icon-btn">
								<div class="btn-group dropdown">
									<button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-icon btn-icon-only btn btn-link">
										<i class="pe-7s-menu btn-icon-wrapper"></i>
									</button>
									<div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-right rm-pointers dropdown-menu-shadow dropdown-menu-hover-link dropdown-menu" >
										<button type="button" tabindex="0" class="dropdown-item" id="hide_detalle">
											<i class="dropdown-icon lnr-inbox"> </i><span>Ocultar</span>
										</button>
										<button type="button" tabindex="0" class="dropdown-item">
											<i class="dropdown-icon lnr-file-empty"> </i><span>Settings</span>
										</button>
									</div>
								</div>
							</div>

						</div>
						<div class="card-body">
							<div class="form-row mt-3">

								<div class="form-group col-12 logica-sistema	-sucursal-modal" hidden>
									<label>Sucursal</label>
									<select name="sucursal_onsite_id" id="sucursal_onsite_id" class="form-control multiselect-dropdown" placeholder='Seleccione Sucursal onsite id' {{ ((isset($sistemaEditar))?'disabled':'') }}>
									</select>
								</div>

								<div class=" form-group col-12 col-md-6">
									<label>Nombre Sistema</label>
									<input type="text" class="form-control" name="sistema_nombre" id="sistema_nombre" required autocomplete="off">
								</div>

								<div class=" form-group col-12 col-md-6">
									<label>Fecha Compra</label>
									<small>opcional</small>
									<input type="date" class="form-control" name="fecha_compra" id="fecha_compra" required>
								</div>

								<div class=" form-group col-12 col-md-6">
									<label>Nº Factura</label>
									<small>opcional</small>
									<input type="text" class="form-control" name="numero_factura" id="numero_factura" required autocomplete="off">
								</div>


								<div class="form-group col-12 col-md-6 logica-sistema-sucursal-modal">
									<label>Comentarios</label>
									<input type="text" class="form-control" name="comentarios" id="comentarios" autocomplete="off">
								</div>

								<div class="col-12 mb-2">
									<small class="form-text text-muted">Complete los datos del sistema y de click en Guardar para cada sistema que desee agregar</small>
									<small class="form-text text-muted">Para cada Sistema creado, podrá agregar Unidades Exteriores e Interiores y datos de Comprador </small>
								</div>

							</div>

							<div class="form-row mt-2">
								<button type="submit" class="btn btn-warning  mt-2 mr-2 col-9" id="botonGuardarSistema" value="1">Guardar</button>
								<button type="reset" class="btn btn-secondary  mt-2 col-sm">Resetear</button>
							</div>

						</div>

					</div>


					<div class="row sistemas_unidades_creados" id="sistemas_unidades_creados" hidden>
						<div class="col-12">
							<div class="card">
								<div class="card-body">
									<h3 class="card-title">Sistemas</h3>
									<p class="card-text">Sistemas creados</p>
									<div class="col-12 sistemas_creados" id="sistemas_creados">
										<!-- completo dinamicamente -->
									</div>
								</div>
							</div>
						</div>
						<div class="col-12">
							<div class="card">
								<div class="card-body">
									<h3 class="card-title">Unidades Exteriores</h3>
									<p class="card-text">Unidades creadas</p>
									<div class="col-12 unidades_exteriores_creadas" id="unidades_exteriores_creadas">
										<!-- completo dinamicamente -->
									</div>
								</div>
							</div>
						</div>

						<div class="col-12">
							<div class="card">
								<div class="card-body">
									<h3 class="card-title">Unidades Interiores</h3>
									<p class="card-text">Unidades creadas</p>
									<div class="col-12 unidades_interiores_creadas" id="unidades_interiores_creadas">
										<!-- completo dinamicamente -->
									</div>
								</div>
							</div>
						</div>
					</div>

					<div class="divider"></div>

					<div class="card-body">
						<div class="form-group row mt-2">
							<button type="button" class="btn btn-primary col-12" id="finalizar_carga">Finalizar carga de Obra</button>
						</div>
					</div>
				</div>

				<!-- Confirma Formulario -->
				<div id="step-4" class="tab-pane step-content" style="display: none;">
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
							<button class="btn-shadow btn-wide btn btn-success btn-lg" id="boton_enviar">Finalizar</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!-- <div class="divider"></div> -->
		<!-- <div class="card-body clearfix">
			<button type="button" id="reset-btn" class="btn-shadow float-left btn btn-link">Reset</button>
			<button type="button" id="next-btn" class="btn-shadow btn-wide float-right btn-pill btn-hover-shine btn btn-primary">Siguiente</button>
			<button type="button" id="prev-btn" class="btn-shadow float-right btn-wide btn-pill mr-3 btn btn-outline-secondary">Anterior</button>
		</div> -->
	</div>
</div>