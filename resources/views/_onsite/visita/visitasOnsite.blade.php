@extends('layouts.baseprolist')

@section('content')


@include('_onsite.visita.top')

<div class="main-card mb-3 card">
	<div class="card-header">
		<span class="col-lg-9">
			<h3 class="mr-3">Visitas</h3>
		</span>
		<span class="col-lg-3 float-right">
			<span class="text-center col-md-6 small p-1 text-capitalize">
				<button type="button" data-toggle="collapse" href="#filtro" class="btn-shadow btn btn-secondary btn-sm">
					<i class="fa fa-filter"></i>
				</button>
				Filtrar
			</span>

			<span class="text-center col-md-6 small p-1 text-capitalize">
				<a type="button" href="/exports/listado_visitas_{{ $user_id }}.xlsx" class="btn-shadow btn btn-secondary btn-sm">
					<i class="fa fa-download"></i>
				</a>
				Descargar
			</span>
		</span>
	</div>
	<div class="card-body">

		@include('_onsite.visita.filtroVisitasOnsite')

		<table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered ">
			<thead>
				<tr>
					<th>Clave</th>
					<th>Obra</th>
					<th>Sistema</th>
					<th>Tipo de Servicio</th>
					<th>Estado</th>
					<th>Solicitud</th>
					<th>Técnico Asignado</th>
					<th>Fecha de Ingreso</th>
					<th>Fecha Vencimiento</th>
					<th>Actions</th>
					<!-- <th>Nota</th> -->
				</tr>
			</thead>
			<tbody class="small">
				@foreach($reparacionesOnsite as $reparacionOnsite)
				<tr>
					<td>
						<a href="{{ url('visitasOnsite/' . $reparacionOnsite->id . '/edit') }}">{{ $reparacionOnsite->clave }}</a>
					</td>
					<td>
						{{($reparacionOnsite->sistema_onsite && $reparacionOnsite->sistema_onsite->obra_onsite) ? ('['.$reparacionOnsite->sistema_onsite->obra_onsite->id . '] ' . $reparacionOnsite->sistema_onsite->obra_onsite->nombre) : ''}}
					</td>
					<td>
						{{ ($reparacionOnsite->sistema_onsite) ? ('['.$reparacionOnsite->sistema_onsite->id . '] ' . $reparacionOnsite->sistema_onsite->nombre)  : ''}}
					</td>
					<!-- <td>{{ ($reparacionOnsite->tipo_servicio_onsite) ? $reparacionOnsite->tipo_servicio_onsite->nombre : '' }}</td> -->
					<td>
						{{ ($reparacionOnsite->solicitud_tipo_id) ? ('['.$reparacionOnsite->solicitud_tipo->id . '] ' .$reparacionOnsite->solicitud_tipo->nombre ): '' }}
					</td>
					<td class="text-center">
						<button class="btn btn-link" name="consultarHistorialEstadoOnsite" data-toggle="modal" data-target="#modalHistorialEstadosOnsite" value="{{ $reparacionOnsite->id }}" type="button">{{ ($reparacionOnsite->estado_onsite) ? $reparacionOnsite->estado_onsite->nombre : '' }}</button>
					</td>
					<td>
						@if(isset($reparacionOnsite) && isset($reparacionOnsite->solicitud) && isset($reparacionOnsite->solicitud[0]))
						{{ '['.$reparacionOnsite->solicitud[0]->id .'] '.$reparacionOnsite->solicitud[0]->tipo->nombre }}
						@else
						-
						@endif
					</td>
					<td>
						{{ ($reparacionOnsite->tecnicoAsignado) ? ('['.$reparacionOnsite->tecnicoAsignado->id . '] ' .$reparacionOnsite->tecnicoAsignado->name) : '' }}
					</td>
					<td>
						{{$reparacionOnsite->fecha_ingreso}}
					</td>
					<td>
						{{$reparacionOnsite->fecha_vencimiento}}
					</td>
					<td>
						<!-- actions -->
						<div class="btn-actions-pane-right actions-icon-btn">
							<div class="grupo_actions btn-group dropdown"><button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-icon btn-icon-only btn btn-link"><i class="pe-7s-menu btn-icon-wrapper"></i></button>
								<div tabindex="-1" role="menu" aria-hidden="true" class="grupo_actions_inner dropdown-menu-right rm-pointers dropdown-menu-shadow dropdown-menu-hover-link dropdown-menu" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(28px, 36px, 0px);" x-placement="bottom-end">
									<a href="{{url('crearGarantia/'.$reparacionOnsite->id)}}" target="_self" type="button" tabindex="0" class="dropdown-item aprobar_index_btn"><i class="dropdown-icon pe-7s-note2"> </i><span>Crear Garantía</span></a>
									<a name='agregarNota' data-toggle='modal' data-target='#modalAgregarNota' value='{{$reparacionOnsite->id}}' target="_self" type="button" tabindex="0" class="dropdown-item aprobar_index_btn"><i class="dropdown-icon pe-7s-micro"> </i><span>Agregar Nota</span></a>
								</div>
							</div>
						</div>
					</td>
					<!-- <td>
						<button class='btn btn-info btn-link my-2' name='agregarNota' data-toggle='modal' data-target='#modalAgregarNota' value='{{$reparacionOnsite->id}}'><i class="fa fa-sticky-note" aria-hidden="true" title="Agregar nota" data-toggle="tooltip"></i></button>
					</td> -->
				</tr>
				@endforeach
			</tbody>
			<tfoot>
				<tr>
					<th>Clave</th>
					<th>Obra</th>
					<th>Sistema</th>
					<th>Tipo de Servicio</th>
					<th>Estado</th>
					<th>Solicitud</th>
					<th>Técnico Asignado</th>
					<th>Fecha de Ingreso</th>
					<th>Fecha Vencimiento</th>
					<th>Actions</th>
					<!-- <th>Prioridad</th>
					<th>Nota</th> -->
				</tr>
			</tfoot>
		</table>

		<!---- PAGINATE -->
		@if( Request::path() =='visitasOnsite')
		@include('pagination.default-limit-links', ['paginator' => $reparacionesOnsite, 'filters' => ''])
		@else
		@include('pagination.default-limit-links', ['paginator' => $reparacionesOnsite, 'filters' => '&texto='. $texto .'&id_tipo_servicio='. $id_tipo_servicio .'&id_estado='. $id_estado .'&id_tecnico='. $id_tecnico .'&fecha_vencimiento_desde='. $fecha_vencimiento_desde.'&fecha_vencimiento_hasta='. $fecha_vencimiento_hasta .'&ruta='. $ruta ])
		@endif
		<!----  -->

	</div>
</div>


@endsection

@section('scripts')
<script type="text/javascript" src="{!! asset('/assets/js/_onsite/visitas-onsite-historial-estados.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/assets/js/_onsite/visitas-onsite.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/assets/js/_onsite/agregar-nota.js') !!}"></script>

@endsection

@section('modals')
@include('_onsite.historialestadoonsite.modalpro')
@include('_onsite.visita.nota.modal-agregar')
@endsection