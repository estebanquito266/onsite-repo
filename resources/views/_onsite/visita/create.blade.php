@extends('layouts.baseprocrud')

@section('content')
	@include('_onsite.visita.top')

	<div class="main-card mb-3 card">
		<div class="card-header">
			<h3 class="mr-3">Agregar Visita Onsite</h3>
		</div>
	</div>

	<form method="POST" action="{{ url('reparacionOnsite') }}" id="reparacionesOnsiteForm" novalidate="novalidate">
		{{ csrf_field() }}

		@include('_onsite.visita.campos')
		
		<div class="main-card mb-3 card">
			<div class="card-body">
				<button type="submit" class="btn btn-primary btn-pill mt-2" name="botonGuardar" value="1">Guardar</button>
				<button type="submit" class="btn btn-primary btn-pill mt-2" name="botonGuardarNotificar" value="1">Guardar y Notificar</button>
				<button type="reset" class="btn btn-secondary btn-pill mt-2">Resetear</button>
			</div>
		</div>

	</form>

@endsection

@section('scripts')
	<script type="text/javascript" src="{!! asset('/assets/js/_onsite/visitas-onsite-form.js') !!}"></script>
	<script type="text/javascript" src="{!! asset('/assets/js/_onsite/visitas-onsite.js') !!}"></script>
	<script type="text/javascript" src="{!! asset('/assets/js/_onsite/visitas-onsite-sucursales.js') !!}"></script>
	
	<script type="text/javascript" src="{!! asset('/assets/js/_onsite/visitas-onsite-sistemas.js') !!}"></script>
	<script type="text/javascript" src="{!! asset('/assets/js/_onsite/validar-generar-identificador.js') !!}"></script>
@endsection

@section('modals')
	@include('_onsite.sistemaonsite.modalpro')
	
	@include('_onsite.sucursalonsite.modalpro')
@endsection