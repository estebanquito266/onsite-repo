@extends('layouts.baseprocrud')

@section('content')

@include('_onsite.visita.top')


<div class="main-card mb-3 card">
	<div class="card-header">
		<h3 class="mr-3">Crear Visita</h3>
	</div>
</div>

<form method="POST" action="{{ url('insertSolicitudPuestaMarcha') }}" id="insertSolicitudPuestaMarcha" enctype="multipart/form-data" novalidate="novalidate">
	{{ csrf_field() }}

	<div id="cargando_bgh"></div>
	@include('_onsite.visita.camposReparacion')



</form>

@endsection

@section('modals')
@include('_onsite.visita.modalConfirmacion') 

@endsection

@section('scripts')
<!-- Solicitudes -->
<script type="text/javascript" src="{!! asset('/assets/js/reparaciones/getEvents.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/assets/js/reparaciones/getQuerys.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/assets/js/reparaciones/postQuerys.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/assets/js/reparaciones/functions.js') !!}"></script>


<!-- librerías -->
<script type="text/javascript" src="{!! asset('/assets/js/librerias/makeFormControls.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/assets/js/librerias/messagesToast.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/assets/js/librerias/loader.js') !!}"></script>


<script src="/assets/js/vendors/form-components/form-wizard.js"></script>
<script src="/assets/js/scripts-init/form-components/form-wizard.js"></script>

@endsection