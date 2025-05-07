@extends('layouts.baseprocrud')

@section('content')
	@include('_onsite.obraonsite.top')

		<div class="main-card mb-3 card">
			<div class="card-header">
				<h3 class="mr-3">Imagen Obra Onsite</h3>
			</div>
		</div>

            <form method="POST"  action="{{ url('imagenobraonsite') }}" id="imagenobraonsite" enctype="multipart/form-data">
			{{ csrf_field() }}
			@include('_onsite.obraonsite.imagenobraonsite.campos')

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
