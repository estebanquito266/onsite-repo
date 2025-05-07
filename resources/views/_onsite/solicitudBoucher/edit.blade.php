@extends('layouts.baseprocrud')

@section('css')
	<link rel="stylesheet" href="vendor/bootstrap-select/css/bootstrap-select.css" />
@endsection

@section('content')
	@include('_onsite.sucursalonsite.top')

	<div class="main-card mb-3 card">
		<div class="card-header">
			<h3 class="mr-3">Modificar Sucursal Onsite</h3>
		</div>
	</div>

	<form method="POST" action="{{ url('sucursalesOnsite/' . $sucursalOnsite->id) }}" id="sucursalOnsiteForm" enctype="multipart/form-data">
		{{ csrf_field() }}
		<input name="_method" type="hidden" value="PUT">

		@include('_onsite.sucursalonsite.campos')
		@include('_onsite.sucursalonsite.camposEdit')
		


		<div class="main-card mb-3 card">
			<div class="card-body">
				<button type="submit" class="btn btn-primary btn-pill mt-2" name="botonGuardar" value="1">Guardar</button>
				<button type="submit" class="btn btn-primary btn-pill mt-2" name="botonGuardarCerrar" value="1">Guardar y Cerrar</button>

	</form>

			<form method="POST" action="{{ url('sucursalesOnsite/' . $sucursalOnsite->id) }}" style="display:inline;">
				{{ csrf_field() }}
				<input name="_method" type="hidden" value="DELETE">
				<button type="submit" class="btn btn-danger btn-pill mt-2" name="botonEliminar" value="1">Eliminar</button>
			</form>

		</div>
	</div>

@endsection

@section('scripts')
	<script type="text/javascript" src="{!! asset('/assets/js/_onsite/sucursalesonsite.js') !!}"></script>
	<script type="text/javascript" src="{!! asset('vendor/bootstrap-select/js/bootstrap-select.js') !!}"></script>
@endsection