@extends('layouts.baseprolist')

@section('content')

@include('_onsite.localidadonsite.top')

<div class="main-card mb-3 card">
	<div class="card-header">
		<h3 class="mr-3">Listado de Localidades Onsite</h3>
		<button type="button" data-toggle="collapse" href="#filtro" class="btn-shadow mr-3 btn btn-secondary">
			<i class="fa fa-filter"></i>
		</button>
		<button type="button" data-toggle="collapse" href="#exportador" class="btn-shadow mr-3 btn btn-secondary">
			<i class="fa fa-download"></i>
		</button>

		<button type="button" data-toggle="collapse" href="#importador" class="btn-shadow mr-3 btn btn-secondary">
			<i class="fa fa-upload"></i>
		</button>
	</div>

	<div class="card-body">

		<div class="collapse border mb-5 pl-3 pr-3 pb-3" id="filtro">
			<form method="POST" action="filtrarLocalidadOnsite" id="localidadOnsiteForm">
				@csrf
				<div class="form-row mt-3">
					<div class="col-lg-4">
						<div class="form-group">
							<label>Ingrese texto </label>
							<input type="text" class="form-control" placeholder="Ingrese el texto de su búsqueda " name="texto" id="texto" value="{{ (isset($texto)) ? $texto : '' }}">
						</div>
					</div>

					<div class="col-lg-4">
						<div class="form-group">
							<label>Provincia</label>
							<select name="id_provincia" id="id_niid_provinciavel" class="form-control multiselect-dropdown" placeholder='Seleccione una provincia'>
								<option value=""> -- Seleccione una --</option>
								@foreach ($provincias as $provincia)
								<option value="{{ $provincia->id }}" {{ (isset($id_provincia) && $id_provincia == $provincia->id ) ? 'selected' : '' }}>{{ $provincia->nombre }}</option>
								@endforeach
							</select>
						</div>
					</div>

					<div class="col-lg-4">
						<div class="form-group">
							<label>Niveles Onsite</label>
							<select name="id_nivel" id="id_nivel" class="form-control multiselect-dropdown" placeholder='Seleccione un nivel onsite'>
								<option value=""> -- Seleccione uno --</option>
								@foreach ($nivelesOnsite as $id => $nivelOnsite)
								<option value="{{ $id }}" {{ (isset($id_nivel) && $id_nivel == $id ) ? 'selected' : '' }}>{{ $nivelOnsite }}</option>
								@endforeach
							</select>
						</div>
					</div>

					<div class="col-lg-4">
						<div class="form-group">
							<label>Técnico Onsite</label>
							<select name="id_tecnico" id="id_tecnico" class="form-control" placeholder='Seleccione un técnico onsite'>
								<option value=""> -- Seleccione uno --</option>
								@foreach ($tecnicosOnsite as $id => $tecnicoOnsite)
								<option value="{{ $id }}" {{ (isset($id_tecnico) && $id_tecnico == $id ) ? 'selected' : '' }}>{{ $tecnicoOnsite }}</option>
								@endforeach
							</select>
						</div>
					</div>

					<div class=' col-lg-12'>
						<div class='form-group'>
							<button type="submit" class="btn btn-primary btn-block btn-pill pull-right">Filtrar</button>
						</div>
					</div>
				</div>
				<form>
		</div>

		<div class="collapse border mb-5" id="exportador">
			<div class="form-group text-center">
				<a href="exports/listado_localidades_onsite_{{ $userId }}.xlsx" class="btn btn-success  btn-pill mt-3">Descargar </a>
			</div>
		</div>

		<div class="collapse border  pl-3 pr-3 mb-5" id="importador">
			<div class="mt-3 mb-3">
				<form method="POST" action="importarLocalidadesOnsite" id="importarLocalidadesOnsiteForm" enctype="multipart/form-data">
					@csrf
					<div class="form-group mr-2">
						<a href="imports/template_localidades_onsite.csv" class="btn btn-light btn-pill" style="display:inline;"><i class="fa fa-arrow-circle-down"></i> Template Importación </a>
					</div>

					<div class="form-group">
						<input type="file" name="archivo" placeholder="Seleccione el archivo" id="archivo" class="form-control mr-2">
						<input type="submit" class="btn btn-warning  btn-pill" id="importar">Importar</button>
					</div>
				</form>
			</div>
		</div>

		<table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered ">
			<thead>
				<tr>
					<th>#</th>
					<th>Provincia</th>
					<th>Localidad</th>
					<th>Localidad Estandard</th>
					<th>Código</th>
					<th>Km</th>
					<th>Nivel</th>
					<th>Atiende Desde</th>
					<th>Técnico</th>
					<th>Acciones</th>
				</tr>
			</thead>
			<tbody class="small">
				@foreach($localidadesOnsite as $localidadOnsite)
				<tr>
					<td>
						<a href="{{ route('localidadOnsite.edit', ['localidadOnsite' => $localidadOnsite->id]) }}">{{ $localidadOnsite->id }}</a>
					</td>
					<td>{{ ($localidadOnsite->provincia ?$localidadOnsite->provincia->nombre:'-')}}</td>
					<td>{{$localidadOnsite->localidad}}</td>
					<td>{{$localidadOnsite->localidad_estandard}}</td>
					<td>{{$localidadOnsite->codigo}}</td>
					<td>{{$localidadOnsite->km}}</td>
					<td>{{($localidadOnsite->nivelOnsite?$localidadOnsite->nivelOnsite->nombre:'-')}}</td>
					<td>{{$localidadOnsite->atiende_desde}}</td>
					<td>{{($localidadOnsite->usuarioTecnico?$localidadOnsite->usuarioTecnico->name:'-')}}</td>
					<td>
					<span class="mr-2"><a href="{{ url('localidadOnsite/' . $localidadOnsite->id . '/edit') }}"><i class="fa fa-edit fa-2x"></i></a></span>
					</td>
				</tr>
				@endforeach
			</tbody>
			<tfoot>
				<tr>
					<th>#</th>

					<th>Provincia</th>
					<th>Localidad</th>
					<th>Localidad Estandard</th>
					<th>Código</th>
					<th>Km</th>
					<th>Nivel</th>
					<th>Atiende Desde</th>
					<th>Técnico</th>
					<th>Acciones</th>
				</tr>
			</tfoot>
		</table>

		<!---- PAGINATE -->

		@if( Request::path() =='localidadOnsite' )
		@include('pagination.default-limit-links', ['paginator' => $localidadesOnsite, 'filters' => ''])
		@else
		@include('pagination.default-limit-links', ['paginator' => $localidadesOnsite, 'filters' => '&texto='. $texto .'&id_provincia='. $id_provincia .'&id_nivel='. $id_nivel .'&id_tecnico='. $id_tecnico ])
		@endif

		<!----  -->

	</div>
</div>


@endsection