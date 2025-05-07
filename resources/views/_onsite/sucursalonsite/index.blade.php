@extends('layouts.baseprolist')

@section('content')


@include('_onsite.sucursalonsite.top')

<div class="main-card mb-3 card">

	<div class="card-header">
		<h3 class="mr-3">Listado de Sucursales Onsite</h3>
		<button type="button" data-toggle="collapse" href="#filtro" class="btn-shadow mr-3 btn btn-secondary">
			<i class="fa fa-filter"></i>
		</button>
		<button type="button" data-toggle="collapse" href="#exportador" class="btn-shadow mr-3 btn btn-secondary">
			<i class="fa fa-download"></i>
		</button>
	</div>

	<div class="card-body">

		<div class="collapse border mb-5 pl-3 pr-3 pb-3" id="filtro">
			<form action="{{ url('filtrarSucursalesOnsite') }}" method="POST">
				{{ csrf_field() }}
			<div class="form-row mt-3">
				<div class="col-lg-4">
					<div class="form-group">
						<label>Ingrese texto </label>
						<input type="text" name="texto" class="form-control" placeholder="Ingrese el texto de su búsqueda" id="texto" value="{{ (isset($texto)) ? $texto : '' }}">
					</div>
				</div>


				<div class="col-lg-4">
					<div class="form-group">
						<label>Empresa Onsite Id</label>
						<select name="empresa_onsite_id" id="empresa_onsite_id" class="form-control" placeholder='Seleccione empresa onsite id'>
							<option value=""> -- Seleccione uno --</option>
							@foreach ($empresasOnsite as $empresaOnsite)
								<option value="{{ $empresaOnsite->id }}" {{ ((isset($empresa_onsite_id) && $empresa_onsite_id == $empresaOnsite->id) ? 'selected' : '' ) }}>{{ $empresaOnsite->nombre }}</option>
							@endforeach
						</select>
					</div>
				</div>

				<div class="col-lg-4">
					<div class="form-group">
						<label>Localidad Onsite Id</label>

						<select name="localidad_onsite_id" id="localidad_onsite_id" class=" multiselect-dropdown form-control" placeholder='Seleccione localidad onsite id'>
							<option value=""> -- Seleccione uno --</option>
							@foreach ($localidadesOnsite as $id => $localidadOnsite)
								<option value="{{ $id }}" {{ ((isset($localidad_onsite_id) && $localidad_onsite_id == $id) ? 'selected' : '') }}>{{ $localidadOnsite }}</option>
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

			</form>
		</div>

		<div class="collapse border mb-5" id="exportador">
			<div class="form-group text-center">
				<a href="{{ url('descargarSucursalesOnsite') . $request }}" class="btn btn-success  btn-pill mt-3" target="_blank">Descargar </a>
			</div>
		</div>

		<table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered ">
			<thead>
				<tr>
					<th>#</th>


					<th>Código Sucursal</th>
					
					<th>Razón Social</th>
					<th>Empresa Onsite Id</th>
					<th>Localidad Onsite Id</th>

					<th>Teléfono Contacto</th>
					<th>Nombre Contacto</th>

					<th>Técnico</th>

					<th>Acción</th>
				</tr>
			</thead>
			<tbody class="small">
				@foreach($sucursalesOnsite as $sucursalOnsite)
				<tr>
					<td>
						<a href="{{ url('sucursalesOnsite/' . $sucursalOnsite->id . '/edit') }}">{{ $sucursalOnsite->id }}</a>
					</td>

					<td>{{$sucursalOnsite->codigo_sucursal}}</td>
					
					<td>{{$sucursalOnsite->razon_social}}</td>
					<td>{{$sucursalOnsite->empresa_onsite->nombre}}</td>
					<td>{{$sucursalOnsite->localidad_onsite->localidad}}</td>

					<td>{{$sucursalOnsite->telefono_contacto}}</td>
					<td>{{$sucursalOnsite->nombre_contacto}}</td>

					<td>{{ ($sucursalOnsite->tecnicosOnsite()->exists()) ? $sucursalOnsite->tecnicosOnsite()->first()->name : ''}}</td>

					<td>
						<a href="{{ route('sucursalesOnsite.show',$sucursalOnsite->id) }}"><i class="fa fa-eye fa-2x"></i></a>
						<a href="{{ url('sucursalesOnsite/' . $sucursalOnsite->id . '/edit') }}"><i class="fa fa-edit fa-2x"></i></a>
					</td>
				</tr>
				@endforeach
			</tbody>
			<tfoot>
				<tr>
					<th>#</th>


					<th>Código Sucursal</th>
					
					<th>Razón Social</th>
					<th>Empresa Onsite Id</th>
					<th>Localidad Onsite Id</th>

					<th>Teléfono Contacto</th>
					<th>Nombre Contacto</th>

					<th>Técnico</th>

					<th>Acción</th>
				</tr>
			</tfoot>
		</table>

		<!---- PAGINATE -->

		@if( Request::path() =='sucursalesOnsite' )
			@include('pagination.default-limit-links', ['paginator' => $sucursalesOnsite, 'filters' => ''])
		@else
			@include('pagination.default-limit-links', ['paginator' => $sucursalesOnsite, 'filters' => '&texto='. $texto.'&empresa_onsite_id='. $empresa_onsite_id.'&localidad_onsite_id='. $localidad_onsite_id ])
		@endif

		<!----  -->

	</div>
</div>


@endsection