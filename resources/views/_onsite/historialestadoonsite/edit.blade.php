@extends('layouts.baseprocrud')

@section('content')
@include('_onsite.historialestadoonsite.top')


<div class="main-card mb-3 card">
	<div class="card-header">
		<h3 class="mr-3">Modificar notificación</h3>
	</div>
</div>



<form action="{{ route('historialEstadoOnsite.update', ['historialEstadoOnsite' => $historialEstadoOnsite ]) }}" method='POST' id='historialEstadoOnsiteForm'>

	<input name="_method" type="hidden" value="PUT">

	{{ csrf_field() }}

	@include('_onsite.historialestadoonsite.campos')

	<div class="main-card mb-3 card">
		<div class="card-body">
			<button type="submit" class="btn btn-primary btn-pill mt-2" name="botonGuardar" value="1">Guardar</button>
			<button type="submit" class="btn btn-primary btn-pill mt-2" name="botonGuardarCerrar" value="1">Guardar y Cerrar</button>

</form>



<form action="{{ route('historialEstadoOnsite.destroy', ['historialEstadoOnsite' => $historialEstadoOnsite ]) }}" method='POST' style='display:inline;'>
	<input type="hidden" name="_method" value="DELETE" />
	{{ csrf_field() }}
	<button type="submit" class='btn btn-danger btn-pill mt-2'>Eliminar</button>
</form>
</div>
</div>

@endsection

@section('scripts')
  <script src="/assets/js/_onsite/historial-estados-onsite-form.js"></script>
@endsection