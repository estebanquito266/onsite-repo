@extends('layouts.baseprocrud')

@section('content')

@include('_onsite.obraonsite.top')


<div class="main-card mb-3 card">
	<div class="card-header">
		<h3 class="mr-3">BGH Eco Smart</h3>
	</div>
</div>
<div id="cargando_bgh"></div>

	@include('_onsite.obraonsite.camposDashboardObra')



@endsection

@section('modals')
@include('_onsite.obraonsite.modalConfirmacion') 


@endsection

@section('scripts')
<!-- obras -->
<script type="text/javascript" src="{!! asset('/assets/js/_onsite/UnidadesOnsite.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/assets/js/obras/dashboardObra.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/assets/js/obras/getQuerys.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/assets/js/obras/postQuerys.js') !!}"></script>

<!-- solicitudes -->
<script type="text/javascript" src="{!! asset('/assets/js/solicitudes/getQuerys.js') !!}"></script>

<!-- librerias -->
<script type="text/javascript" src="{!! asset('/assets/js/librerias/loader.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/assets/js/librerias/messagesToast.js') !!}"></script>

<script type="text/javascript" src="{!! asset('/assets/js/_onsite/form-wizard-obra.js') !!}"></script>
<script src="/assets/js/vendors/form-components/form-wizard.js"></script>

@endsection


