@extends('layouts.baseprocrud')

@section('content')
@include('_onsite.obraonsite.top')

<div class="main-card mb-3 card">
	<div class="card-header">
		<h3 class="mr-3">Detalle Obra</h3>
	</div>
</div>

<form method="POST" action="{!! $obraOnsite !!}" accept-charset="UTF-8">

	<fieldset disabled>
		@include('_onsite.obraonsite.campos')
		@include('_onsite.obraonsite.imagenobraonsite.imagenesList')
		@include('_onsite.obraonsite.sistemasList')
		@include('_onsite.solicitudonsite.solicitudesList')
		@include('_onsite.garantiaonsite.garantiasList')		
	</fieldset>

</form>

@endsection

@section('scripts')
<script type="text/javascript" src="{!! asset('/assets/js/_onsite/obrasonsite.js') !!}"></script>
@endsection