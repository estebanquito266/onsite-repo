@extends('layouts.baseprolist')

@section('content')


@include('_onsite.terminalonsite.top')

<div class="main-card mb-3 card">

	<div class="card-header">
		<h3 class="mr-3">Listado de Terminales Onsite</h3>
		<button type="button" data-toggle="collapse" href="#filtro" class="btn-shadow mr-3 btn btn-secondary">
			<i class="fa fa-filter"></i>
		</button>
		<button type="button" data-toggle="collapse" href="#exportador" class="btn-shadow mr-3 btn btn-secondary">
			<i class="fa fa-download"></i>
		</button>
	</div>

	<div class="card-body">

		<div class="collapse border mb-5 pl-3 pr-3 pb-3" id="filtro">
			<form action="{{ url('filtrarTerminalOnsite') }}" method="POST">
				{{ csrf_field() }}
				<div class="form-row mt-3">
					<div class="col-lg-6">
						<div class="form-group">
							<label>Ingrese texto </label>
							<input type="text" name="texto" class="form-control" placeholder="Ingrese el texto de su búsqueda" id="texto" value="{{ (isset($texto)) ? $texto : '' }}">
						</div>
					</div>

					<div class="col-lg-6">
						<div class="form-group">
							<label>Ingrese sucursal onsite clave/razón social </label>
							<input type="text" name="sucursal_onsite_clave" class="form-control" placeholder="Ingrese la clave/razón social de la sucursal onsite" id="sucursal_onsite_clave"  value="{{ (isset($sucursal_onsite_clave)) ? $sucursal_onsite_clave : '' }}">
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
				<a href="exports/listado_terminalonsite_{{ $user_id }}.csv" class="btn btn-success  btn-pill mt-3">Descargar </a>
			</div>
		</div>

		<table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered ">
			<thead>
				<tr>
					<th>#</th>
					<th>Empresa Onsite</th>
					<th>Sucursal Onsite</th>
					<th>ALL</th>
					<th>Marca</th>
					<th>Modelo</th>
					<th>Serie</th>
					<th>Rótulo</th>

					<th>Acción</th>
				</tr>
			</thead>
			<tbody class="small">
				@foreach($terminalesOnsite as $terminalOnsite)
				<tr>
					<td>
						<a href="{{ url('terminalOnsite/' . $terminalOnsite->nro . '/edit') }}">{{ $terminalOnsite->nro }}</a>
					</td>

					<td>
						@if($terminalOnsite->empresa_onsite)
						{{$terminalOnsite->empresa_onsite->nombre}}
						@endif
					</td>

					<td>
						@if($terminalOnsite->sucursal_onsite)
						{{$terminalOnsite->sucursal_onsite->razon_social}}
						@endif
					</td>
					<td>
						@if($terminalOnsite->all_terminales_sucursal)
						SI
						@else
						NO
						@endif
					</td>
					<td>{{$terminalOnsite->marca}}</td>
					<td>{{$terminalOnsite->modelo}}</td>
					<td>{{$terminalOnsite->serie}}</td>
					<td>{{$terminalOnsite->rotulo}}</td>


					<td>
					
						<span><a href="{{ route('terminalOnsite.show',$terminalOnsite) }}"><i class="fa fa-eye fa-2x"></i></a></span>
						<span class="mr-2"><a href="{{ url('terminalOnsite/' . $terminalOnsite->nro . '/edit') }}"><i class="fa fa-edit fa-2x"></i></a></span>
					</td>
				</tr>
				@endforeach
			</tbody>
			<tfoot>
				<tr>
					<th>#</th>
					<th>Empresa Onsite</th>
					<th>Sucursal Onsite</th>
					<th>ALL</th>
					<th>Marca</th>
					<th>Modelo</th>
					<th>Serie</th>
					<th>Rótulo</th>

					<th>Acción</th>
				</tr>
			</tfoot>
		</table>


		<!---- PAGINATE -->

		@if( Request::path() =='terminalOnsite' )
		@include('pagination.default-limit-links', ['paginator' => $terminalesOnsite, 'filters' => ''])
		@else
		@include('pagination.default-limit-links', ['paginator' => $terminalesOnsite, 'filters' => '&texto='. $texto .'&sucursal_onsite_clave='. $sucursal_onsite_clave ])
		@endif

		<!----  -->

	</div>
</div>


@endsection
