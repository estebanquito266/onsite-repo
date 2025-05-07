
@extends('layouts.baseprocrud')

@section('content')
	@include('_onsite.localidadonsite.top') 
	
		<div class="main-card mb-3 card">
			<div class="card-header">
				<h3 class="mr-3">Agregar Localidad Onsite</h3>
			</div>
		</div>
		
		<form method="POST" action="{{ route('localidadOnsite.store') }}" id="localidadOnsiteForm" enctype="multipart/form-data">
			@csrf
			
			@include('_onsite.localidadonsite.campos')	
			
			<div class="main-card mb-3 card">
				<div class="card-body">
					<button type="submit" class="btn btn-primary btn-pill mt-2" name="botonGuardar" value="1">Guardar</button>
					<button type="submit" class="btn btn-primary btn-pill mt-2" name="botonGuardarCerrar" value="1">Guardar y Cerrar</button>					
					<button type="reset" class="btn btn-secondary btn-pill mt-2">Resetear</button>									
				</div>
			</div>
							
		</form>
	
@endsection