@extends('layouts.baseprocrud')

@section('content')

<div class="main-card mb-3 card">
	<div class="card-header">
		<h3 class="mr-3">Generar visita</h3>
	</div>
</div>

<fieldset disabled>
	@include('_onsite.obraonsite.campos')
</fieldset>
<fieldset disabled>
	@include('_onsite.solicitudonsite._campos')
</fieldset>


<form action="{{ route('solicitud.conversion',['id'=>$solicitudOnsite->id]) }}" method="POST">
	@csrf


	<div class="main-card mb-3 card ">
		<div class="card-header text-white bg-secondary"> Técnico </div>
		<div class="card-body">
			<div class="form-row mt-3">
				<div class="form-group col-6">
					<label for="tecnico_id">Técnico</label>
					<select name="tecnico_id" id="tecnico_id" class="form-control">
						@foreach($tecnicos as $id => $tecnico)
						<option value="{{$id}}">{{$tecnico}}</option>
						@endforeach
					</select>
				</div>
				<div class="form-group col-lg-6 col-md-6 ">
					<label>Fecha Vencimiento</label>
					<div class="input-group date" id="fecha_vencimiento" data-target-input="nearest">

						<div class="input-group-append" data-target="#fecha_vencimiento" data-toggle="datetimepicker">
							<div class="input-group-text"><i class="fa fa-calendar"></i></div>
						</div>
						<input type="text" class="form-control datetimepicker-input" data-target="#fecha_vencimiento" placeholder="Ingrese fecha y hora de vencimiento " name="fecha_vencimiento" value="{{ isset($reparacionOnsite) && !empty($reparacionOnsite->fecha_vencimiento) ? $reparacionOnsite->fecha_vencimiento : '' }}">
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="main-card mb-3 card">
		<div class="card-body">
			<input type="submit" value="Generar pedido de visita" class="btn btn-success" class="">
		</div>
	</div>

</form>


@endsection