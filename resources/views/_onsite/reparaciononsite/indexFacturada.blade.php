@extends('layouts.baseprolist')

@section('content')


@include('_onsite.reparaciononsite.top')

<div class="main-card mb-3 card">
	<div class="card-header">
		<h3 class="mr-3">Listado de Reparaciones Onsite FACTURADAS</h3>
		<button type="button" data-toggle="collapse" href="#filtro" class="btn-shadow mr-3 btn btn-secondary">
			<i class="fa fa-filter"></i>
		</button>
		<button type="button" data-toggle="collapse" href="#exportador" class="btn-shadow mr-3 btn btn-secondary">
			<i class="fa fa-download"></i>
		</button>
	</div>
	<div class="card-body">

		<div class="collapse border mb-5 pl-3 pr-3 pb-3" id="filtro">
			<form action="{{ url('filtrarReparacionOnsite') }}" method="POST">
				{{ csrf_field() }}
				<div class="form-row mt-3">
					<div class="col-lg-6">
						<div class="form-group">
							<label>Ingrese texto </label>
							<input type="text" name="texto" class="form-control" placeholder="Ingrese el texto de su búsqueda" id="texto" value="{{ (isset($texto)) ? $texto : '' }}">
						</div>
					</div>
					<div class=' col-lg-6'>
						<div class='form-group'>
							<label>Empresa Onsite</label>
							<select name="id_empresa" id="id_empresa" class="form-control multiselect-dropdown">
								<option selected="selected" value="">Seleccione empresa onsite</option>
								@foreach ($empresasOnsite as $id => $empresaOnsite)
								<option value="{{ $id }}" {{ (isset($id_empresa) && $id_empresa == $id) ? 'selected' : '' }}>{{ $empresaOnsite }}</option>
								@endforeach
							</select>
						</div>
					</div>
					<div class=' col-lg-6'>
						<div class='form-group'>
							<label>TipoServicio Onsite</label>
							<select name="id_tipo_servicio" id="id_tipo_servicio" class="form-control multiselect-dropdown">
								<option selected="selected" value="">Seleccione tiposervicio onsite</option>
								@foreach ($tiposServicios as $id => $nombre)
								<option value="{{ $id }}" {{ (isset($id_tipo_servicio) && $id_tipo_servicio == $id) ? 'selected' : '' }}>{{ $nombre }}</option>
								@endforeach
							</select>
						</div>
					</div>

					<input type="hidden" value="9" name="id_estado">

					<div class=' col-lg-6'>
						<div class='form-group'>
							<label>Técnicos Onsite</label>
							<select name="id_tecnico" id="id_tecnico" class="form-control multiselect-dropdown">
								<option selected="selected" value="">Seleccione técnico onsite</option>
								@foreach ($tecnicosOnsite as $id => $nombre)
								<option value="{{ $id }}" {{ (isset($id_tecnico) && $id_tecnico == $id) ? 'selected' : '' }}>{{ $nombre }}</option>
								@endforeach
							</select>
						</div>
					</div>

					<div class=' col-lg-6'>
						<div class='form-group'>
							<input type="hidden" value="1" name="liquidado_proveedor">
							<input type="hidden" value="reparacionOnsiteFacturada" name="ruta">
							<button type="submit" class="btn btn-primary btn-block btn-pill pull-right" name="boton" value="filtrar">Filtrar</button>
						</div>
					</div>
					<div class=' col-lg-6'>
						<div class='form-group'>
							<button type="submit" class="btn btn-success btn-block btn-pill pull-right" name="boton" value="csv">Filtrar y Generar CSV</button>
						</div>
					</div>
				</div>

			</form>
		</div>

		<div class="collapse border mb-5" id="exportador">
			<div class="form-group text-center">
				<a href="exports/listado_reparaciononsite_{{ $user_id }}.xlsx" class="btn btn-success  btn-pill mt-3">Descargar </a>
			</div>
		</div>

		<div class="collapse border mb-5" id="importador">
			<div class="mt-3 mb-3">
				<form action="{{ url('importarReparacionesOnsite') }}" method="POST" class="form-inline" enctype="multipart/form-data">
					{{ csrf_field() }}
					<div class="form-group mr-2">
						<a href="imports/template_reparaciones_onsite.csv" class="btn btn-light btn-pill" style="display:inline;"><i class="fa fa-arrow-circle-down"></i> Template Importación </a>
					</div>

					<div class="form-group">
						<input type="file" name="archivo" class="form-control mr-2">
						<input type="submit" class="btn btn-warning  btn-pill" value="Importar">
					</div>
				</form>
			</div>
		</div>

		<table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered ">
			<thead>
				<tr>
					<th>Clave</th>
					<th>Empresa</th>
					<th>Sucursal</th>
					<th>Estado</th>

					<th>Terminal</th>
					<th>Tipo de Servicio</th>
					<th>Técnico Asignado</th>

					<th>Monto </th>
					<th>Monto Extra </th>
					<th>Monto Total </th>

					<th>Eliminar</th>
				</tr>
			</thead>
			<tbody class="small">
				@foreach($reparacionesOnsite as $reparacionOnsite)
				<tr>
					<td>
						<a href="{{ url('reparacionOnsite/' . $reparacionOnsite->id . '/edit') }}">{{ $reparacionOnsite->clave }}</a>
					</td>
					<td>{{($reparacionOnsite->empresa_onsite) ? $reparacionOnsite->empresa_onsite->nombre : ''}}</td>
					<td>{{ ($reparacionOnsite->sucursal_onsite) ? $reparacionOnsite->sucursal_onsite->razon_social : '' }}</td>
					<td class="text-center">
						<button class="btn btn-link" name="consultarHistorialEstadoOnsite" data-toggle="modal" data-target="#modalHistorialEstadosOnsite" value="{{ $reparacionOnsite->id }}" type="button">{{ ($reparacionOnsite->estado_onsite) ? $reparacionOnsite->estado_onsite->nombre : '' }}</button>
					</td>

					<td>{{ ($reparacionOnsite->terminal_onsite) ? $reparacionOnsite->terminal_onsite->nro . ' - ' . $reparacionOnsite->terminal_onsite->marca . ' - ' . $reparacionOnsite->terminal_onsite->modelo . ' - ' . $reparacionOnsite->terminal_onsite->serie . ' - ' . $reparacionOnsite->terminal_onsite->rotulo : ''}}</td>
					<td>{{ ($reparacionOnsite->tipo_servicio_onsite) ? $reparacionOnsite->tipo_servicio_onsite->nombre : '' }}</td>
					<td>{{ ($reparacionOnsite->tecnicoAsignado) ? $reparacionOnsite->tecnicoAsignado->name : '' }}</td>

					<td>
						<input class="form-control form-control-sm  monto" step=".01" id="{{ $reparacionOnsite->id }}" name="monto" type="number" value="{{ $reparacionOnsite->monto }}">
					</td>
					<td>
						<input class="form-control form-control-sm  montoextra" step=".01" id="{{ $reparacionOnsite->id }}" name="monto_extra" type="number" value="{{ $reparacionOnsite->monto_extra }}">
					</td>

					<td>
						${{ number_format( ($reparacionOnsite->monto + $reparacionOnsite->monto_extra) , 2, ',', '.') }}
					</td>


					<td>
						<form action="{{ url('reparacionOnsite/' . $reparacionOnsite->id) }}" method="POST" style="display:inline;">
							<input name="_method" type="hidden" value="DELETE">
							{{ csrf_field() }}
							<button type="submit" class="mb-2 mr-2  btn btn-link text-danger "><i class="fa fa-times-circle fa-2x"></i></button>
						</form>
					</td>
				</tr>
				@endforeach
			</tbody>
			<tfoot>
				<tr>
					<th>Clave</th>
					<th>Empresa</th>
					<th>Sucursal</th>
					<th>Estado</th>

					<th>Terminal</th>
					<th>Tipo de Servicio</th>
					<th>Técnico Asignado</th>

					<th>Monto </th>
					<th>Monto Extra </th>
					<th>Monto Total </th>

					<th>Eliminar</th>
				</tr>
			</tfoot>
		</table>

		<!---- PAGINATE -->

		@if( Request::path() =='reparacionOnsiteFacturada' )
		@include('pagination.default-limit-links', ['paginator' => $reparacionesOnsite, 'filters' => ''])
		@else
		@include('pagination.default-limit-links', ['paginator' => $reparacionesOnsite, 'filters' => '&texto='. $texto .'&id_empresa='. $id_empresa .'&id_tipo_servicio='. $id_tipo_servicio .'&id_estado='. $id_estado .'&id_tecnico='. $id_tecnico .'&fecha_vencimiento='. $fecha_vencimiento .'&estados_activo='. $estados_activo .'&ruta='. $ruta .'&liquidado_proveedor='. $liquidado_proveedor ])
		@endif

		<!----  -->

	</div>
</div>


@endsection

@section('scripts')
<script type="text/javascript" src="{!! asset('/assets/js/_onsite/reparaciones-onsite-historial-estados.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/assets/js/_onsite/reparaciones-onsite.js') !!}"></script>
@endsection

@section('modals')
@include('_onsite.historialestadoonsite.modalpro')
@endsection