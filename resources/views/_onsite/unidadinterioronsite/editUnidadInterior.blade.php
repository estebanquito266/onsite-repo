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
        <input type="hidden" name="empresa_onsite_id" id="empresa_onsite_id" value="{{$unidadInterior->empresa_onsite_id}}">
        <input type="hidden" name="sucursal_onsite_id" id="sucursal_onsite_id" value="{{$unidadInterior->sucursal_onsite_id}}">
        <input type="hidden" name="sistema_onsite_id" id="sistema_onsite_id" value="{{$unidadInterior->sistema_onsite_id}}">
        @include('_onsite.unidadinterioronsite.campos')

        <div class="main-card mb-3 card">
            <div class="card-body">
                <button type="submit" class="btn btn-primary btn-pill mt-2" name="botonGuardarReturnSO" value="1">Guardar</button>
    </form>

    <form method="POST" action="{{ url('unidadInterior/' . $unidadInterior->id) }}" style="display:inline;">
        {{ csrf_field() }}
        <input name="_method" type="hidden" value="DELETE">
        <button type="submit" class="btn btn-danger btn-pill mt-2" name="botonEliminar" value="1">Eliminar</button>
    </form>
    </div>
    </div>

@endsection

@section('scripts')
    <script type="text/javascript" src="{!! asset('/assets/js/_onsite/UnidadesOnsite.js') !!}"></script>
@endsection
