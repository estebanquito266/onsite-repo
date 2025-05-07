@extends('layouts.baseprocrud')

@section('content')
    @include('_onsite.unidadexterioronsite.top')


    <div class="main-card mb-3 card">
        <div class="card-header">
            <h3 class="mr-3">Modificar Imagen Unidad Exterior Onsite</h3>
        </div>
    </div>
    
    <form method="POST" action="{{ url('imagenUnidadOnsite/' . $imagen->id) }}" id="imagenUnidadOnsiteForm" enctype="multipart/form-data">
        {{ csrf_field() }}
        <input name="_method" type="hidden" value="PUT">
            @include('_onsite.imagenunidadonsite.campos')

        <div class="main-card mb-3 card">
            <div class="card-body">
                <button type="submit" class="btn btn-primary btn-pill mt-2" name="botonGuardarReturnSO" value="1">Guardar</button>
    </form>

    <form method="POST" action="{{ url('imagenUnidadOnsite/' . $imagen->id) }}" style="display:inline;">
        {{ csrf_field() }}
        <input name="_method" type="hidden" value="DELETE">
        
        @if(isset($imagen->unidad_exterior_onsite_id))
            <input type="hidden" id="tipo_unidad" name="tipo_unidad" value="exterior">
            <input type="hidden" id="unidad_exterior_onsite_id" name="unidad_exterior_onsite_id" value="{{$imagen->unidad_exterior_onsite_id}}">
        @else
            <input type="hidden" id="tipo_unidad" name="tipo_unidad" value="interior">
            <input type="hidden" id="unidad_interior_onsite_id" name="unidad_interior_onsite_id" value="{{$imagen->unidad_interior_onsite_id}}">
        @endif
        <button type="submit" class="btn btn-danger btn-pill mt-2" name="botonEliminar" value="1">Eliminar</button>
    </form>
    </div>
    </div>

@endsection

@section('scripts')
@endsection
