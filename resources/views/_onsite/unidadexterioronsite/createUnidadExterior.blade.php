@extends('layouts.baseprocrud')

@section('content')

		<div class="main-card mb-3 card">
			<div class="card-header">
				<h3 class="mr-3">Agregar Unidad Interior Onsite</h3>
			</div>
		</div>

            <form method="POST"  action="{{ url('unidadExterior') }}" id="unidadExteriorOnsiteForm" novalidate="novalidate" enctype="multipart/form-data">
			{{ csrf_field() }}
			<input type="hidden" name="empresa_onsite_id" id="empresa_onsite_id" value="{{$sistemaOnsite->empresa_onsite_id}}">
			<input type="hidden" name="sucursal_onsite_id" id="sucursal_onsite_id" value="{{$sistemaOnsite->sucursal_onsite_id}}">
			<input type="hidden" name="sistema_onsite_id" id="sistema_onsite_id" value="{{$sistemaOnsite->id}}">
			@include('_onsite.unidadexterioronsite.campos')

			<div class="main-card mb-3 card">
				<div class="card-body">
					<button type="submit" class="btn btn-primary btn-pill mt-2" name="botonGuardarReturnSO" value="1">Guardar</button>
					<button type="reset" class="btn btn-secondary btn-pill mt-2">Resetear</button>
				</div>
			</div>

		</form>

@endsection

@section('scripts')
	<script type="text/javascript" src="{!! asset('/assets/js/_onsite/UnidadesOnsite.js') !!}"></script>
@endsection
