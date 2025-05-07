@extends('layouts.baseprocrud')

@section('content')
@include('_onsite.solicitudonsite.top')

<div class="main-card mb-3 card">
	<div class="card-header">
		<h3 class="mr-3">Detalle Solicitud</h3>
	</div>
</div>

<form method="POST" action="{!! $solicitudOnsite !!}" accept-charset="UTF-8">

	<fieldset disabled>
		@include('_onsite.solicitudonsite._campos')
	</fieldset>

	<fieldset disabled>
		@include('_onsite.obraonsite.campos')
	</fieldset>	

</form>

@endsection

@section('scripts')
<script type="text/javascript" src="{!! asset('/assets/js/_onsite/solicitudesonsite.js') !!}"></script>
@endsection