@extends('layouts.baseprocrud')

@section('content')
@include('_onsite.respuestosonsite.top')

<meta name="csrf-token" content="{{ csrf_token() }}" />
<div class="main-card mb-3 card">
    <div class="card-header card_inicio_repuestos">
        <h3 class="mr-3">REPUESTOS</h3>
    </div>
</div>
<div class="row">
    <div class="col-md-6">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">Ajuste Listado de Precios</h5>

                <form method="POST" action="{{ url('/importPreciosRepuestos') }}" enctype="multipart/form-data">
                    @csrf
                    <!-- <div class="position-relative form-group">
                        <label>Coeficiente</label>
                        <input name="coeficiente" id="coeficiente" placeholder="inserte porcentaje de ajuste" type="number" step="0.01" class="form-control">
                    </div> -->
                    <!--  <div class="position-relative form-group">
                        <label>Versión</label>
                        <input readonly name="version" id="version" type="number" class="form-control" value="{{isset($version)? $version:0}}">
                    </div> -->

                    <!--  <div class="position-relative form-group">
                        <label>Vencimiento</label>
                        <input name="vencimiento_precio" id="vencimiento_precio" type="date" class="form-control">
                    </div> -->


                    <div class="position-relative form-group">
                        <label>Archivo</label>
                        <input name="file" id="file" type="file" class="form-control-file">
                        <small class="form-text text-muted">Puede cargar el archivo de excel con los precios modificados, respteando
                            el formato del archivo descargado. Se recomiendo dar un número correlativo ascendente a la nueva versión.
                        </small>
                    </div>
                    <button class="mt-1 btn btn-primary">Enviar</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-6">
        <div class="main-card mb-3 card">
            <div class="card-body">
                <h5 class="card-title">Versión Actual de Precios</h5>
                <div class="position-relative form-group">
                        <label>Versión</label>
                        <input readonly name="version" id="version" type="number" class="form-control" value="{{isset($version)? $version:0}}">
                    </div>
                <div class="text-center">
                   

                    <a href="/exportarPrecios/" class="btn-wide mb-2 mr-2 btn btn-primary">Descargar Versión</a>

                </div>
            </div>
        </div>

    </div>
</div>

@endsection


@section('modals')

@endsection

@section('scripts')
<script type="text/javascript" src="{!! asset('/assets/js/_onsite/respuestos-onsite-form.js') !!}"></script>
@endsection