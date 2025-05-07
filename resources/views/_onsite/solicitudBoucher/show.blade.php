@extends('layouts.baseprocrud')

@section('content')
    @include('_onsite.sucursalonsite.top')


    <div class="main-card mb-3 card">
        <div class="card-header">
            <h3 class="mr-3">Detalle Sucursal Onsite</h3>
        </div>
    </div>

    <form method="POST" action="{{ url('sucursalOnsite/' . $sucursalOnsite->id) }}" id="sucursalOnsiteForm" enctype="multipart/form-data">
        {{ csrf_field() }}
        <input name="_method" type="hidden" value="PUT">

        <fieldset disabled>
            @include('_onsite.sucursalonsite.campos')
        </fieldset>

    </form>

@endsection

@section('scripts')
    <script type="text/javascript" src="{!! asset('/assets/js/_onsite/sucursalesonsite.js') !!}"></script>
@endsection