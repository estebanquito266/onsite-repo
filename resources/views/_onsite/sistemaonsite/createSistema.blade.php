@extends('layouts.baseprocrud')

@section('content')

@include('_onsite.sistemaonsite.top')


<div class="main-card mb-3 card">
	<div class="card-header">
		<h3 class="mr-3">BGH Eco Smart</h3>
	</div>
</div>

<form method="POST" id="storeSistema" enctype="multipart/form-data" novalidate="novalidate">
	{{ csrf_field() }}

	<div id="cargando_bgh"></div>
	@include('_onsite.sistemaonsite.camposSistema')



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
<script type="text/javascript" src="{!! asset('/assets/js/_onsite/createSistema.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/assets/js/librerias/loader.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/assets/js/_onsite/form-wizard-obra.js') !!}"></script>
<script src="/assets/js/vendors/form-components/form-wizard.js"></script>
@endsection