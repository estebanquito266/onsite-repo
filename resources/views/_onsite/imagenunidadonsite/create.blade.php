@extends('layouts.baseprocrud')

@section('content')
	@include('_onsite.unidadexterioronsite.top')

		<div class="main-card mb-3 card">
			<div class="card-header">
				<h3 class="mr-3">Imagen Unidad Onsite</h3>
			</div>
		</div>

            <form method="POST"  action="{{ url('imagenUnidadOnsite') }}" id="imagenUnidadOnsiteForm" novalidate="novalidate" enctype="multipart/form-data">
			{{ csrf_field() }}
			@include('_onsite.imagenunidadonsite.campos')

			<div class="main-card mb-3 card">
				<div class="card-body">
					<button type="submit" class="btn btn-primary btn-pill mt-2" name="botonGuardarReturnSO" value="1">Guardar</button>
					<button type="reset" class="btn btn-secondary btn-pill mt-2">Resetear</button>
				</div>
			</div>

		</form>

@endsection

@section('scripts')
	
@endsection
