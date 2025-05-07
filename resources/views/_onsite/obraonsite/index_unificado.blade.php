@extends('layouts.baseprolist')

@section('content')
@include('_onsite.obraonsite.top')

<div class="main-card mb-3 card">
	<div class="card-header">
		<span class="col-lg-9">
			<h3 class="mr-3">Listado de Obras</h3>
		</span>
		<span class="col-lg-3 float-right">
			<span class="text-center col-md-6 small p-1 text-capitalize">
				<button type="button" data-toggle="collapse" href="#filtro" class="btn-shadow btn btn-secondary btn-sm">
					<i class="fa fa-filter"></i>
				</button>
                Filtrar
            </span>

			<span class="text-center col-md-6 small p-1 text-capitalize">
				<button type="button" data-toggle="collapse" href="#exportador" class="btn-shadow btn btn-secondary btn-sm">
					<i class="fa fa-download"></i>
				</button>
                Descargar
            </span>			
		</span>
	</div>

	<div class="card-body">

		<table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered ">
			<thead>
				<tr>
					<th>#</th>
					<th>Nombre</th>
					<th>Unidades Exteriores</th>
					<th>Unidades Interiores</th>
					<th>Empresa Nombre</th>
					<th>Empresa Responsable</th>
					<th>Email de Responsable</th>
					<th>Teléfono de Responsable</th>
					<th>Estado</th>
					<th>Acción</th>
				</tr>
			</thead>
			<tbody class="small">
				@foreach($obrasOnsite as $obraOnsite)
				@if($obraOnsite->id == $obraOnsite->id_unificado)
				<tr>
					<td>
						<a href="{{ route('obrasOnsite.edit',$obraOnsite->id) }}">{{ $obraOnsite->id }}</a>
					</td>
					<td>{{$obraOnsite->nombre}}</td>

					<td>{{$obraOnsite->cantidad_unidades_exteriores}}</td>
					<td>{{$obraOnsite->cantidad_unidades_interiores}}</td>
					<td>{{$obraOnsite->empresa_instaladora_nombre}}</td>
					<td>{{$obraOnsite->empresa_instaladora_responsable}}</td>
					<td>{{$obraOnsite->responsable_email}}</td>
					<td>{{$obraOnsite->responsable_telefono}}</td>
					<td>{{$obraOnsite->estado}}</td>
					<td>
						<span class="mr-2"><a href="{{ route('obrasOnsite.show',$obraOnsite->id) }}"><i class="fa fa-eye fa-2x"></i></a></span>
						<span>             <a href="{{ route('obrasOnsite.edit',$obraOnsite->id) }}"><i class="fa fa-edit fa-2x"></i></a></span>
					</td>
				</tr>
				<tr>
					@foreach ($obraOnsite->sistema_onsite_unificado as $sistema)

					<tr>
						<td></td>
						<td></td>
						<td>======> SISTEMAS =======></td>
						<td></td>
						<td>id/idUnif: {{$sistema->obra_onsite_id .'/'.$sistema->obra_onsite_id_unificado}}</td>
						<td>Sis: {{$sistema->nombre}}</td>

					</tr>

					
					@endforeach
				</tr>
				@endif
				@endforeach
				
			</tbody>
			<tfoot>
				<tr>
					<th>#</th>
					<th>Nombre</th>
					<th>Unidades Exteriores</th>
					<th>Unidades Interiores</th>
					<th>Empresa Nombre</th>
					<th>Empresa Responsable</th>
					<th>Email de Responsable</th>
					<th>Teléfono de Responsable</th>
					<th>Estado</th>
					<th>Acción</th>
				</tr>
			</tfoot>
		</table>

		<!---- PAGINATE -->

	

		<!----  -->

	</div>
</div>

@endsection