@extends('layouts.baseprocrud')

@section('content')
@include('_onsite.garantiaonsite.top')
<div class="main-card mb-3 card">
	<div class="card-header">
		<h3 class="mr-3">BGH Eco Smart - Garantías - crear</h3>
	</div>
</div>
<form method="POST" action="{{ url('garantiaonsite') }}" id="garantiaonsite" enctype="multipart/form-data" novalidate="novalidate">
	{{ csrf_field() }}
	<div id="cargando_bgh"></div>
	@include('_onsite.garantiaonsite.campos')
</form>
@endsection

@section('modals')
@include('_onsite.garantiaonsite.modalConfirmacion')
@endsection

@section('scripts')
<script type="text/javascript" src="{!! asset('/assets/js/_onsite/garantias.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/assets/js/librerias/loader.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/assets/js/solicitudes/getQuerys.js') !!}"></script>
<script src="/assets/js/vendors/form-components/form-wizard.js"></script>
<script src="/assets/js/scripts-init/form-components/form-wizard.js"></script>
@endsection