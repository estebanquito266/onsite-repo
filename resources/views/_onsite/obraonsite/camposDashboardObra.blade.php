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
						<em>2</em><span>Unidades</span>
					</a>
				</li>



				<li class="nav-item">
					<a href="#step-3" class="nav-link">
						<em>3</em><span>Visitas</span>
					</a>
				</li>

				<li class="nav-item">
					<a href="#step-4" class="nav-link">
						<em>4</em><span>Bouchers</span>
					</a>
				</li>
			</ul>
			<div class="form-wizard-content sw-container tab-content" style="min-height: 478.734px;">

				<!-- empresa @@@@################ -->
				<div id="step-1" class="tab-pane step-content" style="display: none;">

					<div class="form-row mt-3">

						<div class="form-group col-lg-4 col-12">

							<input type="hidden" name='empresa_instaladora_id' value="{{ !is_null($user)?
																							(count($user->empresa_instaladora)>0?$user->empresa_instaladora[0]->id:null)
																							:null }}">
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

								<div class="input-group-append col-2">
									<button id="refreshsistemasbutton" class="btn btn-secondary"><i class="pe-7s-refresh"> </i></button>
								</div>

							</div>
						</div>

						<div class="card-body">
							<div class="table-responsive">
								<table id="tableSistemas" class="table table-striped table-bordered tableSistemas" cellspacing="0" width="100%">
								</table>
							</div>
						</div>

						<div class="col-12">
							<input type="hidden" name="obra_onsite_id" id="obra_onsite_id" value="{{old ('obra_onsite_id', isset($garantiaOnsite)?$garantiaOnsite->obra_onsite_id:null) }}">

						</div>

					</div>
				</div>

				<!-- sistema ############### -->
				<div id="step-2" class="tab-pane step-content" style="display: block;">
				<div class="card">
					
					<div class="card-body">
						<h4 class="card-title">UNIDADES</h4>
						<p class="card-text">Unidades interiores y exteriores</p>
					</div>
				</div>

					<div class="card-body">
						<div class="table-responsive">
							<table id="unidades_interiores" class="table table-striped table-bordered unidades_interiores" cellspacing="0" width="100%">
							</table>
						</div>
					</div>

					<div class="card-body">
						<div class="table-responsive">
							<table id="unidades_exteriores" class="table table-striped table-bordered unidades_exteriores" cellspacing="0" width="100%">
							</table>
						</div>
					</div>

				</div>

				<!-- sistema ############### -->
				<div id="step-3" class="tab-pane step-content" style="display: block;">
					<div class="table-responsive">
						<table id="reparaciones" class="table table-striped table-bordered reparaciones" cellspacing="0" width="100%">
						</table>
					</div>
				</div>

				<!-- bouchers -->				
				<div id="step-4" class="tab-pane step-content" style="display: block;">
					<div class="table-responsive">
						<table id="table_bouchers" class="table table-striped table-bordered table_bouchers" cellspacing="0" width="100%">
						</table>
					</div>


				</div>



			</div>
		</div>
		<div class="divider"></div>
		<div class="clearfix">
			<button type="button" id="reset-btn" class="btn-shadow float-left btn btn-link">Reset</button>
			<button type="button" id="next-btn" class="btn-shadow btn-wide float-right btn-pill btn-hover-shine btn btn-primary">Siguiente</button>
			<button type="button" id="prev-btn" class="btn-shadow float-right btn-wide btn-pill mr-3 btn btn-outline-secondary">Anterior</button>
		</div>
	</div>
</div>