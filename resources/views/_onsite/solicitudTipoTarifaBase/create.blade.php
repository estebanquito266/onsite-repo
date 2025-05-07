@extends('layouts.baseprocrud')

@section('content')
	@include('_onsite.solicitudTipoTarifa.top')

	<div class="main-card mb-3 card">
		<div class="card-header">
			<h3 class="mr-3">Agregar Tarifas Base </h3>
		</div>
	</div>

	<form method="POST" action="{{ url('solicitudesTiposTarifasBases') }}" id="solicitudesTiposTarifasBasesForm" >
		{{ csrf_field() }}

		@include('_onsite.solicitudTipoTarifaBase.campos')

		<div class="main-card mb-3 card">
			<div class="card-body col-12">
				<button type="submit" class="btn btn-primary col-12 mt-2" name="botonGuardar" value="1">Guardar</button>
				
			</div>
		</div>

	</form>

@endsection

@section('scripts')
<script type="text/javascript" src="{!! asset('/assets/js/_onsite/createSistema.js') !!}"></script>	
<script type="text/javascript" src="{!! asset('/assets/js/_onsite/sucursalesonsite.js') !!}"></script>
@endsection