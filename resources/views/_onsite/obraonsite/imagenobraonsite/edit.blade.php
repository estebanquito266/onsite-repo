@extends('layouts.baseprocrud')

@section('content')
    @include('_onsite.obraonsite.top')


    <div class="main-card mb-3 card">
        <div class="card-header">
            <h3 class="mr-3">Modificar Imagen Obra Onsite</h3>
        </div>
    </div>
    
    <form method="POST" action="{{ url('imagenobraonsite/' . $imagen->id) }}" id="imagenobraonsite" enctype="multipart/form-data">
        {{ csrf_field() }}
        <input name="_method" type="hidden" value="PUT">
            @include('_onsite.obraonsite.imagenobraonsite.campos')

        <div class="main-card mb-3 card">
            <div class="card-body">
                <button type="submit" class="btn btn-primary btn-pill mt-2" name="botonGuardarReturnSO" value="1">Guardar</button>
    </form>

    <form method="POST" action="{{ url('imagenobraonsite/' . $imagen->id) }}" style="display:inline;">
        {{ csrf_field() }}
        <input name="_method" type="hidden" value="DELETE">
        <button type="submit" class="btn btn-danger btn-pill mt-2" name="botonEliminar" value="1">Eliminar</button>
    </form>
    </div>
    </div>

@endsection

@section('scripts')
@endsection
