@extends('layouts.baseprocrud')

@section('content')

@include('_onsite.obraonsite.top')


<div class="main-card mb-3 card">
	<div class="card-header">
		<h3 class="mr-3">BGH Eco Smart - Garantías</h3>
	</div>
</div>

<div class="card">
	<div class="card-body">
		<h4 class="card-title"></h4>
		<p class="card-text"></p>
		<div class="col-12">
			<?php

			echo ($comprobante);

			?>
		</div>
	</div>

</div>

@endsection



@section('scripts')
<script type="text/javascript" src="{!! asset('/assets/js/_onsite/garantias.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/assets/js/librerias/loader.js') !!}"></script>

<script src="/assets/js/vendors/form-components/form-wizard.js"></script>
<script src="/assets/js/scripts-init/form-components/form-wizard.js"></script>
@endsection