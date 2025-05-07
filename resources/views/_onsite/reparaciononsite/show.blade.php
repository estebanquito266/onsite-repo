@extends('layouts.baseprocrud')

@section('content')


<fieldset disabled>
  <div class="main-card mb-3 card">
    <div class="card-header">
      <h3 class="mr-3">Reparacion Onsite</h3>
    </div>
  </div>

  <form method="POST" action="{{ url('reparacionOnsite/' . $reparacionOnsite->id) }}" id="reparacionesOnsiteForm" enctype="multipart/form-data">
    {{ csrf_field() }}
    <input name="_method" type="hidden" value="PUT">

    @include('_onsite.reparaciononsite.camposDetalle')

    @include('_onsite.reparaciononsite.camposDetalleEdit')

  </form>
</fieldset>

<div class="main-card mb-3 card">
  <div class="card-body">
    <button class="btn btn-info btn-pill mt-2 is-valid" name="consultarHistorialEstadoOnsite" data-toggle="modal" data-target="#modalHistorialEstadosOnsite" value="{{ $reparacionOnsite->id }}" type="button" aria-invalid="false">Historial</button>
    <button type="button" class='btn btn-info btn-pill mt-2 is-valid' name='agregarNota' data-toggle='modal' data-target='#modalAgregarNota' value='{{$reparacionOnsite->id}}'>Agregar nota</button>
  </div>
</div>

@endsection

@section('scripts')
  <script type="text/javascript" src="{!! asset('/assets/js/_onsite/reparaciones-onsite-terminalesreparaciones-onsite-terminales.js') !!}"></script>
  <script type="text/javascript" src="{!! asset('/assets/js/_onsite/reparaciones-onsite-terminalesreparaciones-onsite-sistemas.js') !!}"></script>
  <script type="text/javascript" src="{!! asset('/assets/js/_onsite/agregar-nota.js') !!}"></script>
@endsection

@section('modals')
  @include('_onsite.terminalonsite.modalpro')
  @include('_onsite.historialestadoonsite.modalpro')
  @include('_onsite.reparaciononsite.nota.modal-agregar')
@endsection