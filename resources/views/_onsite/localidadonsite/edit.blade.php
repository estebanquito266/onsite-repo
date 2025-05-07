
@extends('layouts.baseprocrud')

@section('content')
	@include('_onsite.localidadonsite.top') 
	
		<div class="main-card mb-3 card">
			<div class="card-header">
				<h3 class="mr-3">Modificar Localidad Onsite</h3>
			</div>
		</div>

		<form method="POST" action="{{ route('localidadOnsite.update', ['localidadOnsite' => $localidadOnsite]) }}" id="localidadOnsiteForm" enctype="multipart/form-data">
			@method('PUT')
			@csrf
					
			@include('_onsite.localidadonsite.campos')
					
			<div class="main-card mb-3 card">			
				<div class="card-body">
					<button type="submit" class="btn btn-primary btn-pill mt-2" name="botonGuardar" value="1">Guardar</button>
					<button type="submit" class="btn btn-primary btn-pill mt-2" name="botonGuardarCerrar" value="1">Guardar y Cerrar</button>	
						
				</form>
				<form action="{{ route('localidadOnsite.destroy', ['localidadOnsite' => $localidadOnsite->id])}}" method="POST" style="display:inline;">
					@method('DELETE')
					@csrf
					<button type="submit" class="btn btn-danger btn-pill mt-2" >Eliminar</button>
				<form>

			</div>
		</div>	
	
@endsection