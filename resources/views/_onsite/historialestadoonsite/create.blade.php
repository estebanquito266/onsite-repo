@extends('layouts.baseprocrud')

@section('content')
@include('_onsite.historialestadoonsite.top')

<div class="main-card mb-3 card">
	<div class="card-header">
		<h3 class="mr-3">Agregar notificación</h3>
	</div>
</div>



<form action="{{ route('historialEstadoOnsite.store') }}" method='POST' id='historialEstadoOnsiteForm'>

	{{ csrf_field() }}

	@include('_onsite.historialestadoonsite.campos')

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
  <script src="/assets/js/_onsite/historial-estados-onsite-form.js"></script>
@endsection