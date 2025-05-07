@extends('layouts.baseprolist')

@section('content')


@include('_onsite.solicitudBoucher.top')

<div class="main-card mb-3 card">

	<div class="card-header">
		<h3 class="mr-3">Listado de Bouchers</h3>
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
					<th>Obra</th>					
					<th>Tipo</th>
					<th>Codigo</th>
					<th>Consumido</th>
					<th>Expira</th>
					<th>Observaciones</th>
				</tr>
			</thead>
			<tbody class="small">
				@foreach($solicitudes_bouchers as $boucher)
				<tr>
					<td>
						<a href="{{ url('solicitudBoucher/' . $boucher->id . '/edit') }}">{{ $boucher->id }}</a>
					</td>
					<td>{{$boucher->obra_onsite->nombre}}</td>
					<td>{{$boucher->tipo_boucher->nombre}}</td>
					<td>{{$boucher->codigo}}</td>
					<td>{{$boucher->consumido == 0? 'NO': 'SI'}}</td>
					<td>{{$boucher->fecha_expira}}</td>
					<td>{{$boucher->observaciones}}</td>					
					<td>
						<a href="{{ url('solicitudBoucher.show',$boucher->id) }}"><i class="fa fa-eye fa-2x"></i></a>
						<a href="{{ url('solicitudBoucher/' . $boucher->id . '/edit') }}"><i class="fa fa-edit fa-2x"></i></a>
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