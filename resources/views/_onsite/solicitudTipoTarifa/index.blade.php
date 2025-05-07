@extends('layouts.baseprolist')

@section('content')


@include('_onsite.solicitudTipoTarifa.top')

<div class="main-card mb-3 card">

	<div class="card-header">
		<h3 class="mr-3">Listado de Tarifas por Tipo de Solicitud</h3>
		<!-- <button type="button" data-toggle="collapse" href="#filtro" class="btn-shadow mr-3 btn btn-secondary">
			<i class="fa fa-filter"></i>
		</button>
		<button type="button" data-toggle="collapse" href="#exportador" class="btn-shadow mr-3 btn btn-secondary">
			<i class="fa fa-download"></i>
		</button> -->
	</div>

	<div class="card-body">

		<!--  -->

		<table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered ">
			<thead>
				<tr>
					<th>#</th>
					<th>Tipo Solicitud</th>					
					<th>Precio</th>
					<th>Versión</th>
					<th>Observaciones</th>
				</tr>
			</thead>
			<tbody class="small">
				@foreach($solicitudes_tipos_tarifas as $tarifa)
				<tr>
					<td>
						<a href="{{ url('solicitudTipoTarifa/' . $tarifa->id . '/edit') }}">{{ $tarifa->id }}</a>
					</td>
					<td>{{$tarifa->solicitud_tipo->nombre}}</td>
					<td>${{number_format($tarifa->precio, 2, ',', '.')}}</td>
					
					<td>{{$tarifa->version}}</td>
					<td>{{$tarifa->observaciones}}</td>
					<td>
						<a href="{{ url('solicitudTipoTarifa.show',$tarifa->id) }}"><i class="fa fa-eye fa-2x"></i></a>
						<a href="{{ url('solicitudTipoTarifa/' . $tarifa->id . '/edit') }}"><i class="fa fa-edit fa-2x"></i></a>
					</td>
				</tr>
				@endforeach
			</tbody>
			
		</table>

		<!---- PAGINATE -->

		
			
		

		<!----  -->

	</div>
</div>


@endsection