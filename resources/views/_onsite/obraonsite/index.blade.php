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
				<a type="button" href="exports/listado_obraonsite_{{ Session::get('idUser') }}.xlsx" class="btn-shadow btn btn-secondary btn-sm">
					<i class="fa fa-download"></i>
				</a>
				Descargar
			</span>	
		</span>
	</div>

	<div class="card-body">
		<div class="collapse border mb-5 pl-3 pr-3 pb-3" id="filtro">
			<form action="{{ url('filtrarObraOnsite') }}" method="POST">
				{{ csrf_field() }}
				<div class="form-row mt-3">
					<div class="col-lg-4">
						<div class="form-group">
							<label>Ingrese texto </label>
							<input type="text" name="texto" class="form-control" placeholder="Ingrese el texto de su búsqueda" id="texto" value="{{ (isset($texto)) ? $texto : '' }}">
						</div>
					</div>
				</div>
				<div class="form-row mt-3">
					<div class=' col-lg-6'>
						<div class='form-group'>
							<button type="submit" class="btn btn-primary btn-block btn-pill pull-right" name="boton_filtrar" value="filtrar">Filtrar</button>
						</div>
					</div>
					<div class=' col-lg-6'>
						<div class='form-group'>
							<button type="submit" class="btn btn-success btn-block btn-pill pull-right" name="boton_filtrar" value="csv">Filtrar y Generar CSV</button>
						</div>
					</div>
				</div>

			</form>
		</div>		

		<table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered ">
			<thead>
				<tr>
					<th>#</th>
					<th>Nombre</th>
					<th>Empresa Instaladora</th>
					<th>Responsable</th>
					<th>Email</th>
					<th>Teléfono</th>
					<th>Acción</th>
				</tr>
			</thead>
			<tbody class="small">
				@foreach($obrasOnsite as $obraOnsite)
				<tr>
					<td>
						<a href="{{ route('obrasOnsite.edit',$obraOnsite->id) }}">{{ $obraOnsite->id }}</a>
					</td>
					<td>
						{{$obraOnsite->nombre}}
					</td>
					<td>
						@if($obraOnsite->empresa_instaladora)
						[{{ $obraOnsite->empresa_instaladora->id}}] {{ $obraOnsite->empresa_instaladora->nombre}}
						@else
						---
						@endif
					</td>
					<td>
						{{$obraOnsite->empresa_instaladora_responsable}}
					</td>
					<td>
						{{$obraOnsite->responsable_email}}
					</td>
					<td>
						{{$obraOnsite->responsable_telefono}}
					</td>
					<td>
						<div class="btn-actions-pane-right actions-icon-btn">
							<div class="grupo_actions btn-group dropdown"><button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-icon btn-icon-only btn btn-link"><i class="pe-7s-menu btn-icon-wrapper"></i></button>
								<div tabindex="-1" role="menu" aria-hidden="true" class="grupo_actions_inner dropdown-menu-right rm-pointers dropdown-menu-shadow dropdown-menu-hover-link dropdown-menu" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(28px, 36px, 0px);" x-placement="bottom-end">
									<a href="{{ route('obrasOnsite.show',$obraOnsite->id) }}" type="button" tabindex="0" class="dropdown-item aprobar_index_btn"><i class="dropdown-icon pe-7s-expand1"> </i><span>Ver</span></a>
									<a href="{{ route('obrasOnsite.edit',$obraOnsite->id) }}" target="_self" type="button" tabindex="0" class="dropdown-item aprobar_index_btn"><i class="dropdown-icon pe-7s-note"> </i><span>Editar</span></a>
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
					<th>Nombre</th>
					<th>Empresa Instaladora</th>
					<th>Responsable</th>
					<th>Email</th>
					<th>Teléfono</th>
					<th>Acción</th>
				</tr>
			</tfoot>
		</table>

		<!---- PAGINATE -->
		@if( Request::path() =='obrasOnsite' )
		@include('pagination.default-limit-links', ['paginator' => $obrasOnsite, 'filters' => ''])
		@else
		@include('pagination.default-limit-links', ['paginator' => $obrasOnsite, 'filters' => '&texto='. $texto ])
		@endif
		<!----  -->

	</div>
</div>

@endsection