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
						<em>2</em><span>Sistema</span>
					</a>
				</li>



				<li class="nav-item">
					<a href="#step-3" class="nav-link">
						<em>3</em><span>Finalizar</span>
					</a>
				</li>
			</ul>
			<div class="form-wizard-content sw-container tab-content" style="min-height: 478.734px;">

				<!-- empresa @@@@################ -->
				<div id="step-1" class="tab-pane step-content" style="display: none;">

					<div class="form-row mt-3">

						<div class="form-group col-lg-4 col-12">

							<input type="hidden" name='empresa_instaladora_id' value="{{ (count($user->empresa_instaladora)>0?$user->empresa_instaladora[0]->id:null) }}">
							<input type="hidden" name='user_id' value="{{ (isset($user)?$user->id:null) }}">
						</div>

					</div>

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
								<!--
								<div class="input-group-append col-2">
									<button id="refreshsistemasbutton" class="btn btn-secondary"><i class="pe-7s-refresh"> </i></button>
								</div>
							-->

							</div>
						</div>
<!--
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
-->
						<div class="col-12">
							<input type="hidden" name="obra_onsite_id" id="obra_onsite_id" value="{{old ('obra_onsite_id', isset($garantiaOnsite)?$garantiaOnsite->obra_onsite_id:null) }}">

						</div>

					</div>
				</div>

				<!-- sistema ############### -->
				<div id="step-2" class="tab-pane step-content" style="display: block;">
					<div class="form-row mt-3">

						<div class="form-group col-12 col-md-6 logica-sistema	-sucursal-modal">
							<label>Sucursal</label>
							<select name="sucursal_onsite_id" id="sucursal_onsite_id" class="form-control multiselect-dropdown" placeholder='Seleccione Sucursal onsite id' {{ ((isset($sistemaEditar))?'disabled':'') }}>


							</select>
						</div>
						<div class=" form-group col-12 col-md-6">
							<label>Empresa Cliente</label>

							<select class="form-control multiselect-dropdown" name="empresa_onsite_id" id="empresa_onsite_id">

								<!-- se completa dinámicamente -->

							</select>

						</div>

						<div class=" form-group col-12 col-md-8">
							<label>Nombre Sistema</label>
							<input type="text" class="form-control" name="sistema_nombre" id="sistema_nombre" required autocomplete="off">


						</div>

						<div class=" form-group col-12 col-md-4">
							<label>Fecha Compra</label>
							<small>opcional</small>
							<input type="date" class="form-control" name="fecha_compra" id="fecha_compra" value="{{date('Y-m-d')}}" required>
						</div>

						<div class=" form-group col-12">
							<label>Nº Factura</label>
							<small>opcional</small>
							<input type="text" class="form-control" name="numero_factura" id="numero_factura" required autocomplete="off">
						</div>


						<div class="form-group col-md-12 logica-sistema-sucursal-modal">
							<label>Comentarios</label>
							<input type="text" class="form-control" name="comentarios" id="comentarios" autocomplete="off">
							<small class="form-text text-muted">Complete los datos del sistema y de click en Guardar para cada sistema que desee agregar</small>

						</div>

						<div class="form-group">
							<button type="submit" class="btn btn-warning  mt-2" id="botonGuardar" value="1">Guardar</button>

							<button type="reset" class="btn btn-secondary  mt-2">Resetear</button>
						</div>



					</div>

					<div class="row" id="sistemas_unidades_creados" hidden>
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
				</div>

				<!-- Confirma Formulario -->
				<div id="step-3" class="tab-pane step-content" style="display: none;">
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
		<div class="divider"></div>

	
        <div class="form-row mt-3">
            <button type="button" id="reset-btn" class="btn-shadow float-left btn btn-link">Reset</button>
	        <button type="button" id="next-btn" class="btn btn-primary col-12">Guardar Sistema y Continuar</button>
        </div>		
<!--
		<div class="clearfix">
			<button type="button" id="reset-btn" class="btn-shadow float-left btn btn-link">Reset</button>
			<button type="button" id="next-btn" class="btn-shadow btn-wide float-right btn-pill btn-hover-shine btn btn-primary">Siguiente</button>
			<button type="button" id="prev-btn" class="btn-shadow float-right btn-wide btn-pill mr-3 btn btn-outline-secondary">Anterior</button>
		</div>
-->
	</div>
</div>