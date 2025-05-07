@extends('layouts.baseprocrud')

@section('content')
@include('_onsite.respuestosonsite.top')

<meta name="csrf-token" content="{{ csrf_token() }}" />
<div class="main-card mb-3 card">
	<div class="card-header card_inicio_repuestos">
		<h3 class="mr-3">GENERAR SOLICITUD DE REPUESTOS</h3>
	</div>
</div>

<form id="respuestosOnsiteForm">
	{{ csrf_field() }}

	@include('_onsite.respuestosonsite.campos')

	<div class="main-card mb-3 card">
		<div class="card-body">
			<button type="submit" class="col-12 btn btn-primary btn-pill mt-2" name="botonGuardar" id="botonGuardar" value="1">Enviar pedido de cotización</button>

		</div>
	</div>

</form>

@endsection


@section('modals')
@include('_onsite.respuestosonsite.modalemail') 
@include('_onsite.respuestosonsite.modaleditpieza') 
@endsection

@section('scripts')
<script type="text/javascript" src="{!! asset('/assets/js/_onsite/respuestos-onsite-form.js') !!}"></script>
@endsection