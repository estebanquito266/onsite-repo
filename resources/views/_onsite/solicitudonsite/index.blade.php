@extends('layouts.baseprolist')

@section('content')
@include('_onsite.solicitudonsite.top')

<div class="main-card mb-3 card">
	<div class="card-header">
		<span class="col-lg-9">
			<h3 class="mr-3">Listado de Solicitudes Onsite</h3>
		</span>
		<span class="col-lg-3 float-right">
			<span class="text-center col-md-6 small p-1 text-capitalize">
				<button type="button" data-toggle="collapse" href="#filtro" class="btn-shadow btn btn-secondary btn-sm">
					<i class="fa fa-filter"></i>
				</button>
				Filtrar
			</span>

			<span class="text-center col-md-6 small p-1 text-capitalize">
				<a type="button" href="/exports/listado_solicitudonsite{{ Session::get('idUser') }}.xlsx" class="btn-shadow btn btn-secondary btn-sm">
					<i class="fa fa-download"></i>
				</a>
				Descargar
			</span>
		</span>
	</div>

	<div class="card-body">
		<div class="collapse border mb-5 pl-3 pr-3 pb-3" id="filtro">
			<form action="{{ url('filtrarSolicitudesOnsite') }}" method="POST">
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
							<label id="pendientes">Todas / Pendientes </label>
							<select name="pendientes" id="pendientes" class="form-control">
								@foreach($todas as $key => $item)
								<option value="{{ $key }}" {{ (isset($pendientes) && $pendientes == $key) ? 'selected' : '' }}>{{ $item }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-lg-4">
						<div class="form-group">
							<label id="estado_solicitud_onsite_id">Estado de solicitud </label>
							<select name="estado_solicitud_onsite_id" id="estado_solicitud_onsite_id" class="form-control">
								<option value="0">Todas</option>
								@foreach($estadosSolicitudOnsite as $idEstado => $estado)
								<option value="{{ $estado->id }}" {{ (isset($estadoSolicitudOnsiteId) && $estadoSolicitudOnsiteId == $estado->id) ? 'selected' : '' }}>{{ $estado->nombre }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class="col-lg-4">
						<div class="form-group">
							<label id="solicitud_tipo_id">Tipo de solicitud </label>
							<select name="solicitud_tipo_id" id="solicitud_tipo_id" class="form-control">
								<option value="0">Todas</option>
								@foreach($tipoSolicitud as $idTipo => $tipo)
								<option value="{{ $tipo->id }}" {{ (isset($tipoSolicitudId) && $tipoSolicitudId == $tipo->id ) ? 'selected' : '' }}>{{ $tipo->nombre }}</option>
								@endforeach
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
					<th> Tipo</th>
					<th> Obra</th>
					<th>Sistema</th>
					<th> Empresa</th>
					<th> Responsable</th>
					<th>Estado Solicitud</th>
					<th>Fecha y hora de creación</th>
					<th>Acciones</th>
				</tr>
			</thead>
			@if($perfilUser == 'admin')
			<tbody class="small">
				@foreach($solicitudesOnsite as $solicitudOnsite)
				<tr>
					<td>
						<a href="{{ route('solicitudesOnsite.edit',$solicitudOnsite->id) }}"> {{ $solicitudOnsite->id }}</a>
					</td>
					<td>
						{{$solicitudOnsite->nombre_tipo}}
					</td>
					<td>
						{{$solicitudOnsite->nombreobraonsiteid}}
					</td>
					<td>
						[{{$solicitudOnsite->id_sistema}}] {{$solicitudOnsite->nombre_sistema}}
					</td>
					<td>
						{{$solicitudOnsite->empresa}}
					</td>
					<td>
						{{$solicitudOnsite->responsable}}
					</td>
					<td>
						{{$solicitudOnsite->nombreestadosolicitudonsiteid}}
					</td>
					<td>
						{{date( 'd-m-Y - H:i ', strtotime($solicitudOnsite->created_at)) . 'Hs.'}}
					</td>
					<td>
						<div class="btn-actions-pane-right actions-icon-btn">
							<div class="grupo_actions btn-group dropdown"><button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-icon btn-icon-only btn btn-link"><i class="pe-7s-menu btn-icon-wrapper"></i></button>
								<div tabindex="-1" role="menu" aria-hidden="true" class="grupo_actions_inner dropdown-menu-right rm-pointers dropdown-menu-shadow dropdown-menu-hover-link dropdown-menu" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(28px, 36px, 0px);" x-placement="bottom-end">
									<a href="{{ route('solicitudesOnsite.show',$solicitudOnsite->id) }}" type="button" tabindex="0" class="dropdown-item aprobar_index_btn"><i class="dropdown-icon pe-7s-expand1"> </i><span>Ver</span></a>
									<a href="{{ route('solicitudesOnsite.edit',$solicitudOnsite->id) }}" target="_self" type="button" tabindex="0" class="dropdown-item aprobar_index_btn"><i class="dropdown-icon pe-7s-note"> </i><span>Editar</span></a>
								</div>
							</div>
						</div>
					</td>
				</tr>
				@endforeach
			</tbody>
			@endif

			@if($perfilUser == 'puestaEnMarcha')
			<tbody class="small">
				@foreach($solicitudesOnsite as $solicitudOnsite)
				<tr>
					<td>
						<a href="{{ route('solicitudesOnsite.edit',$solicitudOnsite->id) }}"> {{ $solicitudOnsite->id }}</a>
					</td>
					<td>
						{{$solicitudOnsite->obra_onsite->nombre}}
					</td>
					<td>
						{{$solicitudOnsite->obra_onsite->empresa_instaladora_nombre}}
					</td>
					<td>
						{{$solicitudOnsite->obra_onsite->empresa_instaladora_responsable}}
					</td>
					<td>
						{{$solicitudOnsite->estado_solicitud_onsite->nombre}}
					</td>
					<td>
						{{date( 'd-m-Y - H:i ', strtotime($solicitudOnsite->created_at)) . 'Hs.'}}
					</td>
					<td>
						<div class="btn-actions-pane-right actions-icon-btn">
							<div class="grupo_actions btn-group dropdown"><button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-icon btn-icon-only btn btn-link"><i class="pe-7s-menu btn-icon-wrapper"></i></button>
								<div tabindex="-1" role="menu" aria-hidden="true" class="grupo_actions_inner dropdown-menu-right rm-pointers dropdown-menu-shadow dropdown-menu-hover-link dropdown-menu" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(28px, 36px, 0px);" x-placement="bottom-end">
									<a href="{{ route('solicitudesOnsite.show',$solicitudOnsite->id) }}" type="button" tabindex="0" class="dropdown-item aprobar_index_btn"><i class="dropdown-icon pe-7s-expand1"> </i><span>Ver</span></a>
									<a href="{{ route('solicitudesOnsite.edit',$solicitudOnsite->id) }}" target="_self" type="button" tabindex="0" class="dropdown-item aprobar_index_btn"><i class="dropdown-icon pe-7s-note"> </i><span>Editar</span></a>
								</div>
							</div>
						</div>
					</td>
				</tr>
				@endforeach
			</tbody>
			@endif

			<tfoot>
				<tr>
					<th>#</th>
					<th> Tipo</th>
					<th> Obra</th>
					<th>Sistema</th>
					<th> Empresa</th>
					<th> Responsable</th>
					<th>Estado Solicitud</th>
					<th>Fecha y hora de creación</th>
					<th>Acciones</th>
				</tr>
			</tfoot>
		</table>

		<!---- PAGINATE -->
		@if( Request::path() =='solicitudesOnsite' )
		@include('pagination.default-limit-links', ['paginator' => $solicitudesOnsite, 'filters' => ''])
		@else
		@include('pagination.default-limit-links', ['paginator' => $solicitudesOnsite, 'filters' => '&texto='. $texto.'&pendientes='. $pendientes.'&estado_solicitud_onsite_id='. $estadoSolicitudOnsiteId.'&solicitud_tipo_id='. $tipoSolicitudId ])
		@endif
		<!----  -->

	</div>
</div>
@endsection