@extends('layouts.baseprocrud')

@section('content')
@include('_onsite.solicitudonsite.top')

<div class="main-card mb-3 card">
	<div class="card-header">
		<h3 class="mr-3">Agregar Solicitud</h3>
	</div>
</div>

<form method="POST" action="{{ url('solicitudesOnsite') }}" id="solicitudOnsiteForm" enctype="multipart/form-data" novalidate="novalidate">
	{{ csrf_field() }}

	@include('_onsite.solicitudonsite.campos')

	<div class="main-card mb-3 card">
		<div class="card-body">
			<button type="submit" class="btn btn-primary btn-pill mt-2" name="botonGuardar" value="1">Guardar</button>
			
			<div class="dropdown d-inline-block">
				<button type="button" aria-haspopup="true" aria-expanded="false" data-toggle="dropdown" class="dropdown-toggle btn btn-primary btn-pill mt-2" name="botonGuardarEstado" value="1">Guardar y Cambiar Estado</button>
				<div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-rounded dropdown-menu">
					@foreach($estadosSolicitudOnsite as $idEstado => $estado)
					<button type="submit" tabindex="0" class="dropdown-item" name="estado_solicitud_onsite_id" value="{{ $estado->id }}">Pasar a: {{ $estado->nombre }}</button>
					@endforeach
				</div>
			</div>
			
			<button type="reset" class="btn btn-secondary btn-pill mt-2">Resetear</button>
		</div>
	</div>

</form>

@endsection

@section('scripts')
<script type="text/javascript" src="{!! asset('/assets/js/_onsite/solicitudesonsite.js') !!}"></script>
@endsection