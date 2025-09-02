@extends('layouts.baseprocrud')

@section('content')
@include('_onsite.reparaciononsite.top')


<div class="main-card mb-3 card">
    <div class="card-header">
        <h3 class="mr-3">Modificar Reparacion Onsite</h3>
    </div>
</div>

<form method="POST" action="{{ url('reparacionOnsite/' . $reparacionOnsite->id) }}" id="reparacionesOnsiteForm"
    enctype="multipart/form-data">
    {{ csrf_field() }}
    <input name="_method" type="hidden" value="PUT">

    @include('_onsite.reparaciononsite.camposDetalle')

    @include('_onsite.reparaciononsite.camposDetalleEdit')

    <div class="main-card mb-3 card">
        <div class="card-body">
            <button type="submit" class="btn btn-primary btn-pill mt-2" name="botonGuardar" value="1">Guardar</button>
            <button type="submit" class="btn btn-primary btn-pill mt-2" name="botonGuardarCerrar" value="1">Guardar y
                Cerrar</button>

            @if(Session::has('reparacionOnsiteFacturada'))
            <button type="submit" class="btn btn-primary btn-pill mt-2" name="botonGuardarCerrarFacturadas"
                value="1">Guardar y Volver a Facturadas</button>
            @endif

</form>

<form method="POST" action="{{ url('reparacionOnsite/' . $reparacionOnsite->id) }}" style="display:inline;">
    {{ csrf_field() }}
    <input name="_method" type="hidden" value="DELETE">
    <button type="submit" class="btn btn-danger btn-pill mt-2" name="botonEliminar" value="1">Eliminar</button>
</form>

<button class="btn btn-info btn-pill mt-2 is-valid" name="consultarHistorialEstadoOnsite" data-toggle="modal"
    data-target="#modalHistorialEstadosOnsite" value="{{ $reparacionOnsite->id }}" type="button"
    aria-invalid="false">Historial</button>

<a href="{{ route('reenviarMailTecnico', $reparacionOnsite->id) }}" type="button"
    class="btn btn-info btn-pill mt-2">Reenviar email al técnico</a>

<button type="button" class='btn btn-info btn-pill mt-2 is-valid' name='agregarNota' data-toggle='modal'
    data-target='#modalAgregarNota' value='{{$reparacionOnsite->id}}'>Agregar nota</button>

<button type="button" class='btn btn-secondary btn-pill mt-2 is-valid' name='agregarVisita' data-toggle='modal'
    data-target='#modalAgregarVisita' value='{{$reparacionOnsite->id}}'>Agregar Visita</button>

</div>
</div>
{{-- {{dd($reparacionOnsite);}} --}}
@include('_onsite.reparaciononsite._cardsTickets',['tickets'=>$reparacionOnsite->tickets])

@endsection

@section('scripts')
<script type="text/javascript" src="{!! asset('/assets/js/_onsite/reparaciones-onsite-form.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/assets/js/_onsite/reparaciones-onsite-imagenes.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/assets/js/_onsite/reparaciones-onsite-sucursales.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/assets/js/_onsite/reparaciones-onsite-terminales.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/assets/js/_onsite/validar-generar-identificador.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/assets/js/_onsite/agregar-nota.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/assets/js/tickets/tickets.js') !!}"></script>


@endsection

@section('modals')
@include('_onsite.terminalonsite.modalpro')
@include('_onsite.sucursalonsite.modalpro')
@include('_onsite.reparaciononsite.modalImagenOnsite')
@include('_onsite.reparaciononsite.nota.modal-agregar')
@include('_onsite.historialestadoonsite.modalpro')
@include('_onsite.reparaciononsite.modalVisita')
@include('_onsite.reparaciononsite.modal_reparacion_visitas')
@include('tickets.modal-form')
@endsection