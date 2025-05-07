@extends('layouts.baseprolist')

@section('content')


@include('_onsite.sistemaonsite.top')
<div class="main-card mb-3 card">
	<div class="card-header">
		<span class="col-lg-9">
			<h3 class="mr-3">Listado de Sistemas Onsite</h3>
		</span>
		<span class="col-lg-3 float-right">
			<span class="text-center col-md-6 small p-1 text-capitalize">
				<button type="button" data-toggle="collapse" href="#filtro" class="btn-shadow btn btn-secondary btn-sm">
					<i class="fa fa-filter"></i>
				</button>
				Filtrar
			</span>
			<span class="text-center col-md-6 small p-1 text-capitalize">
				<a type="button" href="/exports/listado_sistemasonsite_{{ $user_id }}.xlsx" class="btn-shadow btn btn-secondary btn-sm">
					<i class="fa fa-download"></i>
				</a>
				Descargar
			</span>
		</span>
	</div>

	<div class="card-body">
		<div class="collapse border mb-5 pl-3 pr-3 pb-3" id="filtro">
			<form action="{{ url('filtrarSistemasOnsite') }}" method="POST">
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
							<label>Obras</label>
							<select name="id_obra" id="id_obra" class="form-control multiselect-dropdown" placeholder='Seleccione una obra'>
								<option value=""> -- Seleccione una --</option>
								@foreach ($obras as $obra)
								<option value="{{ $obra->id }}" {{ (isset($id_obra) && $id_obra == $obra->id ) ? 'selected' : '' }}>[{{ $obra->id }}] {{ $obra->nombre }}</option>
								@endforeach
							</select>
						</div>
						<!--
						<div class="form-group">
							<label>Ingrese sucursal onsite clave/razón social </label>
							<input type="text" name="sucursal_onsite_clave" class="form-control" placeholder="Ingrese la clave/razón social de la sucursal onsite" id="sucursal_onsite_clave"  value="{{ (isset($sucursal_onsite_clave)) ? $sucursal_onsite_clave : '' }}">
						</div>
						-->
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
					<th>Obra Onsite</th>
					<th>Nombre Sistema</th>
					<th>Comentarios</th>
					<th>Action</th>

				</tr>
			</thead>
			<tbody class="small">
				@foreach($sistemasOnsite as $sistemaOnsite)
				<tr>
					<td>
						<a href="{{ url('sistemaOnsite/' . $sistemaOnsite->id . '/edit') }}">{{ $sistemaOnsite->id }}</a>
					</td>
					<td>
						{{($sistemaOnsite->obra_onsite)?('['.$sistemaOnsite->obra_onsite->id .'] '.$sistemaOnsite->obra_onsite->nombre):null}}
					</td>
					<td>
						{{$sistemaOnsite->nombre}}
					</td>
					<td>
						{{$sistemaOnsite->comentarios}}
					</td>
					<td>
						<!-- actions -->
						<div class="btn-actions-pane-right actions-icon-btn">
							<div class="grupo_actions btn-group dropdown"><button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-icon btn-icon-only btn btn-link"><i class="pe-7s-menu btn-icon-wrapper"></i></button>
								<div tabindex="-1" role="menu" aria-hidden="true" class="grupo_actions_inner dropdown-menu-right rm-pointers dropdown-menu-shadow dropdown-menu-hover-link dropdown-menu" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(28px, 36px, 0px);" x-placement="bottom-end">
									<a href="{{url('createGarantiaFromSistema/'.$sistemaOnsite->id)}}" target="_self" type="button" tabindex="0" class="dropdown-item aprobar_index_btn"><i class="dropdown-icon pe-7s-note2"> </i><span>Crear Garantía</span></a>
									<a href="{{url('sistemaOnsite/'.$sistemaOnsite->id.'/edit')}}" name='editarSistema' target="_self" type="button" tabindex="0" class="dropdown-item aprobar_index_btn"><i class="dropdown-icon pe-7s-micro"> </i><span>Editar</span></a>
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
					<th>Obra Onsite</th>
					<th>Nombre Sistema</th>
					<th>Comentarios</th>
				</tr>
			</tfoot>
		</table>

		<!---- PAGINATE -->
		@if( Request::path() =='sistemaOnsite' )
		@include('pagination.default-limit-links', ['paginator' => $sistemasOnsite, 'filters' => ''])
		@else
		@include('pagination.default-limit-links', ['paginator' => $sistemasOnsite, 'filters' => '&texto='. $texto .'&id_obra='. $id_obra ])
		@endif
		<!----  -->

	</div>
</div>


@endsection