@extends('layouts.baseprocrud')

@section('content')
	@include('_onsite.sistemaonsite.top') 
	
		<div class="main-card mb-3 card">
			<div class="card-header">
				<h3 class="mr-3">Agregar Sistema Onsite</h3>
			</div>
		</div>

		<form method="POST" action="{{ url('sistemaOnsite') }}" id="sistemaOnsiteForm" novalidate="novalidate" enctype="multipart/form-data">
			{{ csrf_field() }}	
			

			@include('_onsite.sistemaonsite.campos')	
			
			<div class="main-card mb-3 card">			
				<div class="card-body">
					<button type="submit" class="btn btn-primary btn-pill mt-2" name="botonGuardar" value="1">Guardar</button>
					<button type="submit" class="btn btn-primary btn-pill mt-2" name="botonGuardarCerrar" value="1">Guardar y Cerrar</button>					
					<button type="reset" class="btn btn-secondary btn-pill mt-2">Resetear</button>									
				</div>
			</div>

		</form>

@endsection

@section('scripts')
	<script type="text/javascript" src="{!! asset('/assets/js/_onsite/sistemas-onsite.js') !!}"></script>
	<script type="text/javascript" src="{!! asset('/assets/js/_onsite/terminales-onsite.js') !!}"></script>
	<script type="text/javascript" src="{!! asset('/assets/js/_onsite/visitas-onsite-sucursales.js') !!}"></script>
	<script type="text/javascript" src="{!! asset('/assets/js/_onsite/validar-generar-identificador.js') !!}"></script>
@endsection
