@extends('layouts.baseprocrud')

@section('content')
    @include('_onsite.unidadinterioronsite.top')


    <div class="main-card mb-3 card">
        <div class="card-header">
            <h3 class="mr-3">Modificar Unidad Interior Onsite</h3>
        </div>
    </div>

    <form method="POST" action="{{ url('unidadInterior/' . $unidadInterior->id) }}" id="unidadInteriorForm" enctype="multipart/form-data">
        {{ csrf_field() }}
        <input name="_method" type="hidden" value="PUT">
        @include('_onsite.unidadinterioronsite.camposRelaciones')
        @include('_onsite.unidadinterioronsite.campos')
        @include('_onsite.unidadinterioronsite.imagenesList')

        <div class="main-card mb-3 card">
            <div class="card-body">
                <button type="submit" class="btn btn-primary btn-pill mt-2" name="botonGuardar" value="1">Guardar</button>
                <button type="submit" class="btn btn-primary btn-pill mt-2" name="botonGuardarCerrar" value="1">Guardar y Cerrar</button>
    </form>

    <form method="POST" action="{{ url('unidadInterior/' . $unidadInterior->id) }}" style="display:inline;" id="formEliminar">
        {{ csrf_field() }}
        <input name="_method" type="hidden" value="DELETE">
        <button type="button" data-modal_title="Unidad interna" class="btn btn-danger btn-pill mt-2" name="botonEliminar" id="botonEliminar" value="1">Eliminar</button>
    </form>
    </div>
    </div>

@endsection


@section('modals')
@include('modal.modalConfirmacion')
@endsection

@section('scripts')
    <script type="text/javascript" src="{!! asset('/assets/js/_onsite/UnidadesOnsite.js') !!}"></script>
@endsection
