@extends('layouts.baseprocrud')
@section('content')

<div class="main-card mb-3 card">
	<div class="card-header">
		<h3 class="mr-3">Modificar Usuario OnSite</h3>
	</div>
</div>


<div class="main-card mb-3 card">
	<div class="card-body">

		<form method="POST" action="{{ url('updateConfigUsuario', $usuario->id) }}" enctype="multipart/form-data">
			@method('PUT')
			@csrf
			@include('usuario.campos')			

			<!-- <input type="hidden" name="id_sucursal" value="$usuario->id_sucursal">
			<input type="hidden" name="id_tipo_visibilidad" value="$usuario->id_tipo_visibilidad"> -->

			<div class="form-group col-md-12">
				<button type="submit" class="btn btn-primary">Guardar</button>
				<button type="reset" class="btn btn-default">Resetear</button>
			</div>

		</form>
	</div>


</div>
@endsection

@section('scripts')


<script 
    
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCrK_SmK6NrXU72tphpRZKccnEIPnSieR8&libraries=places&v=weekly" defer>

    </script>

<script type="text/javascript" src="{!! asset('/assets/js/librerias/googleMaps.js') !!}"></script>

@endsection



