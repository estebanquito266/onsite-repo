<div class="main-card mb-3 card formulario_obra" hidden>
	<div class="card-body">
		<div id="smartwizard" class="sw-main sw-theme-default">
			<ul class="forms-wizard nav nav-tabs step-anchor">
				<li class="nav-item active">
					<a href="#step-1" class="nav-link">
						<em>1</em><span>Empresa y Obras</span>
					</a>
				</li>


				<li class="nav-item">
					<a href="#step-2" class="nav-link">
						<em>2</em><span>Comprador</span>
					</a>
				</li>

				<li class="nav-item">
					<a href="#step-3" class="nav-link">
						<em>3</em><span>Factura</span>
					</a>
				</li>

				<li class="nav-item">
					<a href="#step-4" class="nav-link">
						<em>4</em><span>Finalizar</span>
					</a>
				</li>
			</ul>
			<div class="form-wizard-content sw-container tab-content" style="min-height: 478.734px;">

				<!-- empresa @@@@################ -->
				<div id="step-1" class="tab-pane step-content" style="display: none;">

					<div class="form-row mt-3">

						<div class="form-group col-lg-4 col-12">

							<input type="hidden" name='empresa_instaladora_id' value="{{ (count($user->empresa_instaladora)>0?$user->empresa_instaladora[0]->id:null) }}">
							<input type="hidden" name='user_id' id='user_id' value="{{ (isset($user)?$user->id:null) }}">
						</div>

					</div>

					<div class="row mt-3">
						<div class="form-group col-12">
							<label>Empresa Instaladora</label>
							<select name="empresa_instaladora_id" id="empresa_instaladora_id" class="form-control multiselect-dropdown">
								<option value="0"> -- Seleccione uno --</option>
								@foreach ($empresasInstaladoras as $empresa)
								<option value="{{ $empresa->id }}" @if( Request::segment(3)=='edit' ) {{(isset($garantiaOnsite) && $garantiaOnsite->empresa_instaladora_id == $empresa->id) ? 'selected': null }} @else {{old('empresa_instaladora_id') == $empresa->id ? 'selected': null}} @endif>{{$empresa->nombre}}</option>
								@endforeach
							</select>
						</div>

						<div class="col-12 form-group">
							<label>Sistemas</label>
							<div class="row">
								<div class="col-10">
									<select name="sistema_onsite_id" id="sistema_onsite_id" class="form-control multiselect-dropdown">
										<option value="0"> -- Seleccione uno --</option>
										@if(isset($obrasOnsite))
										@foreach ($obrasOnsite as $obra)
										<optgroup label="Obra: {{$obra->nombre}}">
											@foreach ($obra->sistema_onsite as $sistema)
											<option value="{{ $sistema->id }}" data-idobra="{{ $sistema->obra_onsite_id }}" data-idreparacion="{{count($sistema->reparacion_onsite)>0? $sistema->reparacion_onsite[0]->id:0}}" data-nombre_sistema="{{$sistema->nombre}}" @if( Request::segment(3)=='edit' ) {{(isset($garantiaOnsite) && $garantiaOnsite->sistema_onsite_id == $sistema->id) ? 'selected': null}} @else {{old('sistema_onsite_id') == $sistema->id ? 'selected': null}} @endif>{{$sistema->nombre}} (Obra: {{$obra->nombre}})
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

						<div class="col-12">
							<input type="hidden" name="obra_onsite_id" id="obra_onsite_id" value="{{old ('obra_onsite_id', isset($garantiaOnsite)?$garantiaOnsite->obra_onsite_id:null) }}">
							<input type="hidden" name="sistema_nombre" id="sistema_nombre">
						</div>

					</div>


					<div class="divider"></div>

					<div class="form-row mt-3">
						<button type="button" id="save1" class="btn btn-primary col-12">Guardar y Continuar</button>
					</div>


				</div>

				<!-- comprador ############### -->
				<div id="step-2" class="tab-pane step-content" style="display: none;">
					<div class="form-row mt-3">

						<div class="form-group  col-md-4 col-12">
							<label>CUIT</label>
							<input type="number" step="1" name='dni' id="dni" class='form-control' placeholder='Ingrese CUIT' value="{{ old('dni' ,(isset($garantiaOnsite)?$garantiaOnsite->sistema_onsite->comprador_onsite->dni:null)) }}">
							<input type="hidden" id="id_comprador">
						</div>

						<div class="form-group  col-md-4 col-12">
							<label>Nombre</label>
							<input type="text" name='primer_nombre' id="primer_nombre" class='form-control' placeholder='Ingrese primer_nombre' value="{{ old('primer_nombre', isset($garantiaOnsite)?$garantiaOnsite->sistema_onsite->comprador_onsite->primer_nombre:null) }}">
						</div>

						<div class="form-group  col-md-4 col-12">
							<label>Apellido</label>
							<input type="text" name='apellido' id="apellido" class='form-control' placeholder='Ingrese apellido' value="{{old('apellido', isset($garantiaOnsite)?$garantiaOnsite->sistema_onsite->comprador_onsite->apellido:null) }}">
							<input type="hidden" name='nombre' id="nombre" class='form-control' value="{{ (isset($obraOnsite)?$obraOnsite->nombre:null) }}">
						</div>

						<div class="form-group  col-12 col-md-6">
							<label>País</label>
							<select name="pais" id="select_pais" class="form-control multiselect-dropdown  mb-3">
								<option value="DEFAULT">Seleccione Pais...</option>
								@if( Request::segment(3)=='edit' )
								<option value="{{(isset($garantiaOnsite)?$garantiaOnsite->sistema_onsite->comprador_onsite->pais:null)}}" selected>{{(isset($garantiaOnsite)?$garantiaOnsite->sistema_onsite->comprador_onsite->pais:null)}}</option>
								@else
								<option value="{{old('pais', 'Argentina')}}" selected>{{old('pais', 'Argentina')}}</option>
								@endif

								<option value="Argentina">Argentina</option>
								<option value="Brasil">Brasil</option>
								<option value="Bolivia">Bolivia</option>
								<option value="Chile">Chile</option>
								<option value="Paraguay">Paraguay</option>
								<option value="Perú">Perú</option>
								<option value="Uruguay">Uruguay</option>
							</select>
						</div>

						<div class="form-group  col-12 col-md-6" id="provincia_div">
							<label>Provincia</label>
							<select name="provincia_onsite_id" id="provincia" class="form-control multiselect-dropdown  mb-3">
								<option value="DEFAULT" selected="">Seleccione Provincia...</option>
								@foreach ($provincias as $provincia)
								<option value="{{ $provincia->id }}" @if( Request::segment(3)=='edit' ) {{isset($garantiaOnsite) && $garantiaOnsite->sistema_onsite->comprador_onsite->provincia_onsite_id == $provincia->id ? 'selected': null}} @else {{old('provincia_onsite_id') == $provincia->id ? 'selected': null}} @endif>{{$provincia->nombre}}</option>
								@endforeach
							</select>
						</div>

						<div class="form-group  col-12 col-md-6" id="localidad_div">
							<label>Localidad</label>
							<select name="localidad" id="localidad" class="form-control multiselect-dropdown  mb-3">
								<option value="DEFAULT" selected="">Seleccione Localidad...</option>
								@foreach ($localidades as $localidad)
								<option value="{{ $localidad->id }}" {{(isset($garantiaOnsite) && $garantiaOnsite->sistema_onsite->comprador_onsite->localidad_onsite_id == $localidad->id) ? 'selected': null}}>{{$localidad->localidad}}</option>
								@endforeach
							</select>
						</div>


						<div class="form-group col-12 col-md-6">
							<label>Domicilio</label>
							<input type="text" name='domicilio' id="domicilio" class='form-control' placeholder='Ingrese domicilio' value="{{old( 'domicilio', isset($garantiaOnsite) ? $garantiaOnsite->sistema_onsite->comprador_onsite->domicilio: null) }}">
						</div>

						<div class="form-group col-12 col-md-6">
							<label>email</label>
							<input type="text" name='email' id="email" class='form-control' placeholder='Ingrese email' value="{{old('email', isset($garantiaOnsite) ? $garantiaOnsite->sistema_onsite->comprador_onsite->email: null) }}">
						</div>

						<div class="form-group col-12 col-md-6">
							<label>celular</label>
							<input type="text" name='celular' id="celular" class='form-control' placeholder='Ingrese celular' value="{{old('celular', isset($garantiaOnsite) ? $garantiaOnsite->sistema_onsite->comprador_onsite->celular: null)}}">
						</div>


					</div>

					<div class="divider"></div>

					<div class="form-row mt-3">
						<button type="button" id="save2" class="btn btn-primary col-12">Guardar y Continuar</button>
					</div>


				</div>

				<!-- fatura ############### -->
				<div id="step-3" class="tab-pane step-content" style="display: none;">
					<div class="form-row mt-3">						

						<div class="form-group col-12 col-md-6">
							<label>Fecha de Garantía</label>
							<input type="date" name='fecha' class='form-control' id="fecha_compra" placeholder='Ingrese avance de obra (%)' value="{{old ('fecha', isset($garantiaOnsite)?$garantiaOnsite->fecha:date('Y-m-d')) }}">
						</div>


						<div class="form-group col-12 col-md-6">
							<label for="tipo">Tipo de Garantía</label>
							<select class="form-control" name="garantia_tipo_onsite_id" id="tipo">
								<option value=6>SIN NOTIFICAR</option>
								@foreach ($garantiasTipos as $tipo)
								<option value="{{$tipo->id}}">{{$tipo->nombre}}</option>
								@endforeach
							</select>
						</div>						

						<div class="form-group col-lg-12 col-md-12">
							<label>Observaciones</label>
							<textarea class="form-control" name="observaciones" id="observaciones" cols="30" rows="5">{{old('observaciones', isset($garantiaOnsite)?$garantiaOnsite->observaciones:null ) }}</textarea>
						</div>						

						<div class="form-group col-12 col-md-6">
							<label>Referencia Informe Observaciones</label>
							<input type="text" name='informe_observaciones' class='form-control' id="informe_observaciones" value="{{old('informe_observaciones',  isset($garantiaOnsite)?$garantiaOnsite->informe_observaciones:null ) }}">
							<small>Ingrese número de referencia del Informe. Por defecto se toma el número de la visita</small>
						</div>

						<div class="form-group col-12 col-md-6">
							<label>Destinatario Informe Observaciones</label>
							<input type="text" name='destinatario_informe' class='form-control' id="destinatario_informe" value="{{old('destinatario_informe',  isset($garantiaOnsite)?$garantiaOnsite->destinatario_informe:null ) }}">
							<small>Ingrese Nombre y Apellido de la persona destinataria del Informe.</small>
						</div>

					</div>

					<div class="divider"></div>

					<div class="form-row mt-3">
						<button type="button" id="save3" class="btn btn-primary col-12">Guardar y Continuar</button>
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
							<button type="button" class="btn-shadow btn-wide btn btn-success btn-lg" id="boton_enviar">Enviar</button>
						</div>
					</div>
				</div>
			</div>
		</div>
		<!--
		<div class="divider"></div>
		<div class="clearfix">
			<button type="button" id="reset-btn" class="btn-shadow float-left btn btn-link">Reset</button>
			<button type="button" id="next-btn" class="btn-shadow btn-wide float-right btn-pill btn-hover-shine btn btn-primary">Siguiente</button>
			<button type="button" id="prev-btn" class="btn-shadow float-right btn-wide btn-pill mr-3 btn btn-outline-secondary">Anterior</button>
		</div>
		-->
	</div>
</div>