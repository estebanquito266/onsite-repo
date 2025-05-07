
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta http-equiv="Content-Language" content="en">
<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

<title>SpeedUP!</title>

<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no, shrink-to-fit=no" />

<!-- Disable tap highlight on IE -->
<meta name="msapplication-tap-highlight" content="no">

<link rel="icon" href="/S!.png">
	
<meta name="author" content="Aziende.Global">

<link rel="stylesheet" href="/assets/css/base.min.css" type="text/css"> 

@include('layouts.base-head-scripts')

@section('css')
@show

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/tempusdominus-bootstrap-4/5.0.0-alpha14/css/tempusdominus-bootstrap-4.min.css" />



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





@section('scripts')
<script type="text/javascript" src="{!! asset('/assets/js/_onsite/garantias.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/assets/js/librerias/loader.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/assets/js/solicitudes/getEvents.js') !!}"></script>
<script src="/assets/js/vendors/form-components/form-wizard.js"></script>
<script src="/assets/js/scripts-init/form-components/form-wizard.js"></script>
@endsection