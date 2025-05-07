@extends('layouts.baseprocrud')

@section('content')
@include('_onsite.obraonsite.top')

<div class="main-card mb-3 card">
	<div class="card-header">
		<h3 class="mr-3">Modificar Obra</h3>
	</div>
</div>

<form method="POST" action="{{ url('obrasOnsite/' . $obraOnsite->id) }}" id="obraOnsiteForm" enctype="multipart/form-data">
	{{ csrf_field() }}
	<input name="_method" type="hidden" value="PUT">
	@include('_onsite.obraonsite.camposEdit')
	@include('_onsite.obraonsite.campos')
	@include('_onsite.obraonsite.imagenobraonsite.imagenesList', ['viewMode' => 'edit'])
	@include('_onsite.obraonsite.sistemasList', ['viewMode' => 'edit'])
	@include('_onsite.solicitudonsite.solicitudesList', ['viewMode' => 'edit'])
	@include('_onsite.garantiaonsite.garantiasList', ['viewMode' => 'edit'])

	<div class="main-card mb-3 card">
		<div class="card-body">
			<button type="submit" class="btn btn-primary btn-pill mt-2" name="botonGuardar" value="1">Guardar</button>
			<button type="submit" class="btn btn-primary btn-pill mt-2" name="botonGuardarCerrar" value="1">Guardar y Cerrar</button>

</form>

<form method="POST" action="{{ url('obrasOnsite/' . $obraOnsite->id) }}" style="display:inline;" id="formEliminar">
	{{ csrf_field() }}
	<input name="_method" type="hidden" value="DELETE">
	<button type="button" class="btn btn-danger btn-pill mt-2" id="botonEliminar" value="1" data-obra_id="{{ $obraOnsite->id }}" data-obra_nombre="{{$obraOnsite->nombre}}" data-obra_msj="{{$deleteMsj}}">Eliminar</button>
</form>

</div>
</div>

@endsection

@section('modals')
@include('modal.modalConfirmacion')
@endsection

@section('scripts')
<script type="text/javascript" src="{!! asset('/assets/js/_onsite/obrasonsite.js') !!}"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCrK_SmK6NrXU72tphpRZKccnEIPnSieR8&libraries=places&v=weekly" defer></script>

<script type="text/javascript" src="{!! asset('/assets/js/librerias/googleMaps.js') !!}"></script>
@endsection