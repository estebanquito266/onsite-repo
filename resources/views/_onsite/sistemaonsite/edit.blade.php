@extends('layouts.baseprocrud')

@section('content')
    @include('_onsite.sistemaonsite.top')

    <div class="main-card mb-3 card">
        <div class="card-header">
            <h3 class="mr-3">Editar Sistema Onsite</h3>
        </div>
    </div>

    <form method="POST" action="{{ url('sistemaOnsite/'. $sistemaEditar->id) }}" id="sistemaOnsiteForm" novalidate="novalidate" enctype="multipart/form-data">
        {{ method_field('PUT') }}
        {{ csrf_field() }}
        <input type="hidden" name="id_unidad_exterior" id="id_unidad_exterior" value="{{ (isset($unidadExterior)) ? $unidadExterior->id : '' }}">
        <input type="hidden" name="id_unidad_interior" id="id_unidad_interior" value="{{ (isset($unidadInterior)) ? $unidadInterior->id : '' }}">
        @include('_onsite.sistemaonsite.campos')        

        <div class="main-card mb-3 card">
            <div class="card-body">
                <button type="submit" class="btn btn-primary btn-pill mt-2" name="botonGuardar" value="1">Guardar</button>
                <button type="submit" class="btn btn-primary btn-pill mt-2" name="botonGuardarCerrar" value="1">Guardar y Cerrar</button>
                <button type="reset" class="btn btn-secondary btn-pill mt-2">Resetear</button>

                </form>
                <form method="POST" action="{{ url('sistemaOnsite/' . $sistemaEditar->id) }}" style="display:inline;" id="formEliminar">
                    {{ csrf_field() }}
                    <input name="_method" type="hidden" value="DELETE">
                    <button type="button" class="btn btn-danger btn-pill mt-2" id="botonEliminar" value="1" data-sistema_id="{{ $sistemaEditar->id }}" data-sistema_nombre="{{$sistemaEditar->nombre}}" data-sistema_unidades="{{(($unidadesInteriores->count() > 0 || $unidadesExteriores->count() > 0) ? true : false)}}">Eliminar</button>
			    </form>                
            </div>
        </div>

        @include('_onsite.sistemaonsite.unidadesInteriores')
        @include('_onsite.sistemaonsite.unidadesExteriores')
        @include('_onsite.solicitudonsite.solicitudesList', ['viewMode' => 'edit'])
        @include('_onsite.visita.visitasList', ['viewMode' => 'edit'])
        @include('_onsite.garantiaonsite.garantiasList', ['viewMode' => 'edit'])

@endsection

@section('modals')
@include('modal.modalConfirmacion')
@endsection

@section('scripts')
    <script type="text/javascript" src="{!! asset('/assets/js/_onsite/sistemas-onsite.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('/assets/js/_onsite/visitas-onsite-sucursales.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('/assets/js/_onsite/validar-generar-identificador.js') !!}"></script>
@endsection
