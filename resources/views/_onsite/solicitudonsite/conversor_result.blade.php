@extends('layouts.baseprocrud')

@section('content')

<div class="main-card mb-3 card">
	<div class="card-header">
		<h3 class="mr-3">Resumen de visita generada</h3>
	</div>
</div>


<div class="main-card mb-3 card ">
	<div class="card-header text-white bg-secondary">  </div>
	<div class="card-body">
		<div class="form-row mt-3">
			<div class="form-group col-6">
				<label for="empresa">Obra: </label>
				<p>
					 <span>{{ $empresaOnsite->id }} - {{ $empresaOnsite->nombre }}</span>
				</p>
			</div>
			<div class="form-group col-6">
				<label for="sucursal">Sucursal: </label>
				<p>
					<a href="/sucursalesOnsite/{{ $sucursalOnsite->id }}/edit"> {{ $sucursalOnsite->id }} - {{ $sucursalOnsite->razon_social }}</a>
				</p>
			</div>

			<div class="form-group col-6">
				<label for="sistema">Sistema: </label>
				<p>
					<a href="/sistemaOnsite/{{ $sistemaOnsite->id }}/edit"> {{ $sistemaOnsite->id }} - {{ $sistemaOnsite->nombre }}</a>
				</p>
			</div>

			@if($reparacionOnsiteSeguimiento)
			<div class="form-group col-6">
				<label for="reparacion_seguimiento">ID seguimiento de Obra: </label>
				<p>
					<a href="/visitasOnsite/{{ $reparacionOnsiteSeguimiento->id }}/edit"> {{ $reparacionOnsiteSeguimiento->id }}</a>
				</p>
			</div>
			@endif

			<div class="form-group col-6">
				<label for="reparacion_puesta_marcha">ID solicitud de Visita: </label>
				<p>
					<a href="/visitasOnsite/{{ $reparacionOnsitePuestaMarcha->id }}/edit"> {{ $reparacionOnsitePuestaMarcha->id }}</a>
				</p>
			</div>
		</div>
	</div>


	@endsection