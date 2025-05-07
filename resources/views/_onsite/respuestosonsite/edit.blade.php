@extends('layouts.baseprocrud')

@section('content')
@include('_onsite.reparaciononsite.top')


<div class="main-card mb-3 card">
  <div class="card-header">
    <h3 class="mr-3">Modificar Reparacion Onsite</h3>
  </div>
</div>

<form method="POST" action="{{ url('reparacionOnsite/' . $reparacionOnsite->id) }}" id="reparacionesOnsiteForm" enctype="multipart/form-data">

  {{ csrf_field() }}
  <input name="_method" type="hidden" value="PUT">

  @include('_onsite.reparaciononsite.campos')

  @include('_onsite.reparaciononsite.camposEdit')

  <div class="main-card mb-3 card">
    <div class="card-body">
      <button type="submit" class="btn btn-primary btn-pill mt-2" name="botonGuardar" value="1">Guardar</button>
      <button type="submit" class="btn btn-primary btn-pill mt-2" name="botonGuardarNotificar" value="1">Guardar y Finalizar Visita</button>

      @if(Session::has('reparacionOnsiteFacturada'))
      <button type="submit" class="btn btn-primary btn-pill mt-2" name="botonGuardarCerrarFacturadas" value="1">Guardar y Volver a Facturadas</button>
      @endif

      <button type="submit" class="btn btn-primary btn-pill mt-2" name="botonGuardarNotificarTecnico" value="1">Guardar y Reenviar aviso de visita pendiente al técnico</button>


</form>

<button class="btn btn-info btn-pill mt-2 is-valid" name="consultarHistorialEstadoOnsite" id="consultarHistorialEstadoOnsite" data-toggle="modal" data-target="#modalHistorialEstadosOnsite" value="{{ $reparacionOnsite->id }}" type="button" aria-invalid="false">Historial</button>

<button type="button" class='btn btn-info btn-pill mt-2 is-valid' name='agregarNota' data-toggle='modal' data-target='#modalAgregarNota' value='{{$reparacionOnsite->id}}'>Agregar nota</button>

<form method="POST" action="{{ url('reparacionOnsite/' . $reparacionOnsite->id) }}" style="display:inline;">
  {{ csrf_field() }}
  <input name="_method" type="hidden" value="DELETE">
  <button type="submit" class="btn btn-danger btn-pill mt-2" name="botonEliminar" value="1">Eliminar</button>
</form>

</div>
</div>

@endsection

@section('scripts')
<script type="text/javascript" src="{!! asset('/assets/js/_onsite/visitas-onsite-form.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/assets/js/_onsite/visitas-onsite-imagenes.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/assets/js/_onsite/visitas-onsite-sucursales.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/assets/js/_onsite/reparaciones-onsite-terminales.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/assets/js/_onsite/visitas-onsite-sistemas.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/assets/js/_onsite/validar-generar-identificador.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/assets/js/_onsite/agregar-nota.js') !!}"></script>
@endsection

@section('modals')
  @include('_onsite.sistemaonsite.modalpro')
  @include('_onsite.terminalonsite.modalpro')
  @include('_onsite.sucursalonsite.modalpro')

  @include('_onsite.reparaciononsite.modalImagenOnsite')
  @include('_onsite.reparaciononsite.nota.modal-agregar')
  @include('_onsite.historialestadoonsite.modalpro')

  
  
@endsection