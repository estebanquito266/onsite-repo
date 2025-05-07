@extends('layouts.baseprocrud')

@section('content')

@include('_onsite.obraonsite.top')


<div class="main-card mb-3 card">
	<div class="card-header">
		<h3 class="mr-3">Crear Obra</h3>
	</div>
</div>

<form method="POST" action="{{ url('storeObra') }}" id="storeObra" enctype="multipart/form-data" novalidate="novalidate">
	{{ csrf_field() }}

	<div id="cargando_bgh"></div>
	@include('_onsite.obraonsite.camposObra')



</form>

@endsection

@section('modals')
@include('_onsite.obraonsite.modalConfirmacion')
@include('_onsite.unidadexterioronsite.createUEmodal')
@include('_onsite.unidadinterioronsite.createUImodal')
@include('_onsite.sistemaonsite.createCompradorModal')

@endsection

@section('scripts')
<script type="text/javascript" src="{!! asset('/assets/js/_onsite/UnidadesOnsite.js') !!}"></script>

<script type="text/javascript" src="{!! asset('/assets/js/obras/getEvents.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/assets/js/obras/functions.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/assets/js/obras/getQuerys.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/assets/js/obras/postQuery.js') !!}"></script>

<script type="text/javascript" src="{!! asset('/assets/js/librerias/loader.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/assets/js/_onsite/form-wizard-obra.js') !!}"></script>
<script src="/assets/js/vendors/form-components/form-wizard.js"></script>

<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCrK_SmK6NrXU72tphpRZKccnEIPnSieR8&libraries=places&v=weekly" defer></script>

<script type="text/javascript" src="{!! asset('/assets/js/librerias/googleMaps.js') !!}"></script>

@endsection