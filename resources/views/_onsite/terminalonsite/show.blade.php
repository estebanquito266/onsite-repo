@extends('layouts.baseprocrud')

@section('content')
    @include('_onsite.terminalonsite.top')


    <div class="main-card mb-3 card">
        <div class="card-header">
            <h3 class="mr-3">Modificar Terminal Onsite</h3>
        </div>
    </div>

    <form method="POST" action="{{ url('terminalOnsite/' . $terminalOnsite->nro) }}" id="terminalOnsiteForm" enctype="multipart/form-data">
        {{ csrf_field() }}
        <input name="_method" type="hidden" value="PUT">
        <fieldset disabled>
        @include('_onsite.terminalonsite.campos')
        </fieldset>

        

    </form>




@endsection

@section('scripts')
    <script type="text/javascript" src="{!! asset('/assets/js/_onsite/terminales-onsite.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('/assets/js/_onsite/reparaciones-onsite-sucursales.js') !!}"></script>
@endsection
