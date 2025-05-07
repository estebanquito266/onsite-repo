<!-- CARD SISTEMAS Y UNIDADES -->
<div class="col-md-12 main-card mb-3 px-2 card ">
	<div class="mb-3 card mt-3">
		<div class="tabs-lg-alternate card-header">
			<ul class="nav nav-justified">
				<li class="nav-item">
					<a data-toggle="tab" href="#tab-eg9-0" class="nav-link">
						<div class="widget-number">Sistema</div>
						<div class="tab-subheading">
							<span class="pr-2 opactiy-6">
								<i class="fa fa-server"></i>
							</span>
							{{isset($sistemaOnsiteReparacion)?$sistemaOnsiteReparacion->nombre:null}}
						</div>
					</a>
				</li>
				<li class="nav-item">
					<a data-toggle="tab" href="#tab-eg9-1" class="nav-link">
						<div class="widget-number text-danger">Unidades Interiores</div>
						<div class="tab-subheading">
							<span class="pr-2 opactiy-6">
								<i class="fa fa-sitemap"></i>
							</span>
							{{isset($sistemaOnsiteReparacion)?count($sistemaOnsiteReparacion->unidades_interiores):null}}
						</div>
					</a>
				</li>
				<li class="nav-item">
					<a data-toggle="tab" href="#tab-eg9-2" class="nav-link">
						<div class="widget-number text-danger">Unidades Exteriores</div>
						<div class="tab-subheading">
							<span class="pr-2 opactiy-6">
								<i class="fa fa-sitemap"></i>
							</span>
							{{isset($sistemaOnsiteReparacion)?count($sistemaOnsiteReparacion->unidades_exteriores):null}}
						</div>
					</a>
				</li>
			</ul>
		</div>
		<div class="tab-content">
			<div class="tab-pane active" id="tab-eg9-0" role="tabpanel">
				<div class="card-body">
					
					<div>
					<h4 class="m-0 p-0"><span class="badge badge-primary">{{isset($sistemaOnsiteReparacion)?$sistemaOnsiteReparacion->nombre:null}}</span></h4>
					<h5 class="m-0 p-0">Obra: <span class="badge badge-secondary">{{ (isset($sistemaOnsiteReparacion) && isset($sistemaOnsiteReparacion->obra_onsite)) ? $sistemaOnsiteReparacion->obra_onsite->nombre : null}}</span></h5>
					<h5 class="m-0 p-0">Fecha de Alta: <span class="badge badge-info">{{isset($sistemaOnsiteReparacion)?date( 'd-m-Y', strtotime($sistemaOnsiteReparacion->created_at)):null}}</span></h5>
					
					<h5 class="m-0 p-0">{{isset($sistemaOnsiteReparacion)?$sistemaOnsiteReparacion->comentarios:null}}</h5>
					</div>
					
				</div>
			</div>
			<div class="tab-pane" id="tab-eg9-1" role="tabpanel">
				<div class="card-body">
				<div class="table-responsive">
				<table id="table_unidades_interiores" class="table table-striped table-bordered" cellspacing="0" width="100%">
				@if(isset($sistemaOnsiteReparacion))	
				@foreach ($sistemaOnsiteReparacion->unidades_interiores as $unidadInterior)

					<tr>
						<td>{{$unidadInterior->id}}</td>
						<td>{{$unidadInterior->clave}}</td>
						<td>
							@foreach ($unidadInterior->imagenes as $imagen)
							<a href="{{ asset('/imagenes/unidades_interiores/'.$imagen->archivo) }}" target="_blank">
								<img src="{{ asset('/imagenes/unidades_interiores/'.$imagen->archivo) }}" width="40px" height="40px" style="border-radius: 100%">
							</a>
							@endforeach
						</td>
					</tr>

					@endforeach
				@endif	
				</table>
			</div>
				</div>
			</div>
			<div class="tab-pane" id="tab-eg9-2" role="tabpanel">
				<div class="card-body">
				<div class="table-responsive">
				<table id="table_unidades_exteriores" class="table table-striped table-bordered" cellspacing="0" width="100%">
				@if(isset($sistemaOnsiteReparacion))	
					@foreach ($sistemaOnsiteReparacion->unidades_exteriores as $unidadExterior)

					<tr>
						<td>{{$unidadExterior->id}}</td>
						<td>{{$unidadExterior->clave}}</td>
						<td>
							@foreach ($unidadExterior->imagenes as $imagen)
							<a href="{{ asset('/imagenes/unidades_exteriores/'.$imagen->archivo) }}" target="_blank">
								<img src="{{ asset('/imagenes/unidades_exteriores/'.$imagen->archivo) }}" width="40px" height="40px" style="border-radius: 100%">
							</a>
							@endforeach
						</td>
					</tr>

					@endforeach
				@endif
				</table>
			</div>
				</div>
			</div>
		</div>
	</div>
</div>