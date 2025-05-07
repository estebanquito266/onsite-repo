@extends('layouts.baseprolist')

@section('content')


@include('_onsite.unidadinterioronsite.top')
<div class="main-card mb-3 card">
	<div class="card-header">
		<span class="col-lg-9">
			<h3 class="mr-3">Listado de Unidades Interiores Onsite</h3>
		</span>
		<span class="col-lg-3 float-right">
			<span class="text-center col-md-6 small p-1 text-capitalize">
				<button type="button" data-toggle="collapse" href="#filtro" class="btn-shadow btn btn-secondary btn-sm">
					<i class="fa fa-filter"></i>
				</button>
				Filtrar
			</span>
			

		</span>
	</div>

	<div class="card-body">
		<div class="collapse border mb-5 pl-3 pr-3 pb-3" id="filtro">
			<form action="{{ url('filtrarUnidadInterior') }}" method="POST">
				{{ csrf_field() }}
				<div class="form-row mt-3">
					<div class="col-lg-6">
						<div class="form-group">
							<label>Ingrese texto </label>
							<input type="text" name="texto" class="form-control" placeholder="Ingrese el texto de su búsqueda" id="texto" value="{{ isset($texto) ? $texto : '' }}">
						</div>
					</div>
					<div class="col-lg-6">
						<div class="form-group">
							<label>Obras y Sistemas </label>
							<select name="sistema_onsite_id" id="sistema_onsite_id" class="form-control multiselect-dropdown">
								<option value="0"> -- Seleccione uno --</option>
								@if(isset($obrasOnsite))
								@foreach ($obrasOnsite as $obra)
								<optgroup label="Obra: {{$obra->nombre}}">
									@foreach ($obra->sistema_onsite as $sistema)
									<option value="{{ $sistema->id }}" data-idobra="{{ $sistema->obra_onsite_id }}" data-nombre_sistema="{{$sistema->nombre}}" {{ (isset($sistema_onsite_id) && $sistema_onsite_id == $sistema->id) ? 'selected' : '' }}>
										[{{$sistema->id}}] {{$sistema->nombre}} (Obra: {{$obra->nombre}})
									</option>
									@endforeach
								</optgroup>
								@endforeach
								@endif
							</select>
						</div>
					</div>
				</div>
				<div class="form-row mt-3">
					<div class=' col-lg-6'>
						<div class='form-group'>
							<button type="submit" class="btn btn-primary btn-block btn-pill pull-right" name="boton_filtrar" value="filtrar">Filtrar</button>
						</div>
					</div>
					
				</div>
			</form>
		</div>

		<table style="width: 100%;" class="table table-hover table-striped table-bordered ">
			<thead>
				<tr>
					<th>#</th>
					<th>Obra</th>
					<th>Sistema Onsite</th>
					<th>Modelo</th>
					<th>Serie</th>
					<th>Dirección</th>
					<th>Actions</th>
				</tr>
			</thead>
			<tbody class="small">
				@foreach($unidadesInteriores as $unidadInterior)
				<tr>
					<td>
						<a href="{{ url('unidadInterior/' . $unidadInterior->id . '/edit'  ) }}">{{$unidadInterior->id}}</a>
					</td>
					<td>
						@if($unidadInterior->sistema_onsite->obra_onsite)
						[{{$unidadInterior->sistema_onsite->obra_onsite->id}}] {{$unidadInterior->sistema_onsite->obra_onsite->nombre}}
						@else
						---
						@endif
					</td>
					<td>
						[{{$unidadInterior->sistema_onsite->id}}] {{ ($unidadInterior->sistema_onsite?$unidadInterior->sistema_onsite->nombre:' --')}}
					</td>
					<td>
						{{$unidadInterior->modelo}}
					</td>
					<td>
						{{$unidadInterior->serie}}
					</td>
					<td>
						{{$unidadInterior->direccion}}
					</td>
					<td>
						<div class="btn-actions-pane-right actions-icon-btn">
							<div class="grupo_actions btn-group dropdown"><button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-icon btn-icon-only btn btn-link"><i class="pe-7s-menu btn-icon-wrapper"></i></button>
								<div tabindex="-1" role="menu" aria-hidden="true" class="grupo_actions_inner dropdown-menu-right rm-pointers dropdown-menu-shadow dropdown-menu-hover-link dropdown-menu" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(28px, 36px, 0px);" x-placement="bottom-end">
									<a type="button" tabindex="0" class="dropdown-item etiqueta" data-idunidadinterior="{{$unidadInterior->id}}"><i class="dropdown-icon pe-7s-note"> </i><span>Etiqueta</span></a>
								</div>
							</div>
						</div>
					</td>
				</tr>
				@endforeach
			</tbody>

			<tfoot>
				<tr>
					<th>#</th>
					<th>Obra</th>
					<th>Sistema Onsite</th>
					<th>Modelo</th>
					<th>Serie</th>
					<th>Dirección</th>
					<th>Actions</th>
				</tr>
			</tfoot>
		</table>

		<!---- PAGINATE -->
		@if( Request::path() == 'unidadInterior' )
		@include('pagination.default-limit-links', ['paginator' => $unidadesInteriores, 'filters' => ''])
		@else
		@include('pagination.default-limit-links', ['paginator' => $unidadesInteriores, 'filters' => '&texto='. $texto .'&sistema_onsite_id='. $sistema_onsite_id ])
		@endif
		<!----  -->

	</div>
</div>

@section('modals')

@include('_onsite.unidadinterioronsite.createEtiquetaModal')

@endsection

@section('scripts')
<script type="text/javascript" src="{!! asset('/assets/js/_onsite/UnidadesOnsite.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/assets/js/librerias/loader.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/assets/js/_onsite/form-wizard-obra.js') !!}"></script>
<script src="/assets/js/vendors/form-components/form-wizard.js"></script>
@endsection

@endsection