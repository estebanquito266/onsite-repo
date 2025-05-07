@extends('layouts.baseprocrud')

@section('content')
    @include('_onsite.terminalonsite.top')

    <div class="main-card mb-3 card">
        <div class="card-header">
            <h3 class="mr-3">Crear Unidad Interior</h3>
        </div>
    </div>

    <form method="POST" action="{{ url('terminalOnsite') }}" id="terminalOnsiteForm" novalidate="novalidate" enctype="multipart/form-data">
        {{ csrf_field() }}
        <input type="hidden" name="tipo_terminal" id="tipo_terminal" value="3">

        @if(isset($sistemaOnsite))
            <input type="hidden" name="sistemas_onsite_id" id="sistemas_onsite_id" value="{{$sistemaOnsite->id}}">
            <input type="hidden" name="terminal_company_onsite_id" id="terminal_company_onsite_id" value="{{$sistemaOnsite->company_id}}">
            <input type="hidden" name="terminal_empresa_onsite_id" id="terminal_empresa_onsite_id" value="{{$sistemaOnsite->empresa_onsite_id}}">
            <input type="hidden" name="sucursal_onsite_id" id="terminal_sucursal_onsite_id" value="{{$sistemaOnsite->sucursal_onsite_id}}">
            <div class="main-card mb-3 card logica-terminal-empresa-modal">
                <div class="card-header bg-secondary text-white">Empresa Onsite</div>
                <div class="card-body">
                    <div class="form-row mt-12">
                        <div class="form-group col-lg-4 col-md-4">
                            <b>Empresa:</b> {{$sistemaOnsite->empresa_onsite->nombre}} 
                        </div>
                        <div class="form-group col-lg-4 col-md-4">
                            <b>Sucursal:</b> {{$sistemaOnsite->sucursal_onsite->razon_social}} 
                        </div>
                        <div class="form-group col-lg-4 col-md-4">
                            <b>Sistema:</b> {{$sistemaOnsite->nombre}} 

                        </div>
                    </div>
                </div>
            </div>
        @else                            
        <div class="main-card mb-3 card logica-terminal-empresa-modal">
            <div class="card-header bg-secondary text-white">Empresa Onsite</div>
            <div class="card-body">
                <div class="form-row mt-12">
                    <div class="form-group col-lg-12 col-md-12">
                        <select name="empresa_onsite_id" id="terminal_empresa_onsite_id" class="form-control multiselect-dropdown " placeholder='Seleccione empresa onsite id' {!! (Request::segment(2)=='create' ) ? 'onchange="validarGenerarNumeroTerminal();"' : '' !!}">
                        <option value=""> -- Seleccione uno --</option>
                        @foreach ($empresasOnsite as $empresaOnsite)
                            <option value="{{ $empresaOnsite->id }}" >{{ $empresaOnsite->nombre }}</option>
                            @endforeach
                            </select>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-group col-lg-12 col-md-12 logica-terminal-sucursal-modal">
                        <label>Sucursal Onsite Id</label>
                        <div class="form-group input-group ">
                            <select name="sucursal_onsite_id" id="terminal_sucursal_onsite_id" class=" multiselect-dropdown form-control mr-1" placeholder='Seleccione sucursal onsite id'>
                                <option value=""> -- Seleccione uno --</option>
                                @foreach ($sucursalesOnsite as $sucursalOnsite)
                                    <option value="{{ $sucursalOnsite->id }}" >{{ $sucursalOnsite->codigo_sucursal .' - '.$sucursalOnsite->razon_social }}</option>
                                @endforeach
                            </select>

                            <span class="input-group-btn">
                        <button class="btn btn-warning " type="button" id="refreshSucursal"><i class="fa fa-reply-all"></i></button>
                    </span>
                        </div>
                    </div>
        @endif

        <div class="main-card mb-3 card ">
            <div class="card-header bg-secondary text-light">Datos Unidad Interior</div>
            <div class="card-body">
                <div class="form-row mt-3">
                     <div class="form-group col-lg-12 col-md-12">
                        <label for="all_terminales_sucursal">All Terminales - Sucursal</label>
                        <input type="checkbox" id="all_terminales_sucursal" name="all_terminales_sucursal" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="primary" data-offstyle="secondary">
                    </div>

                    <div class="form-group col-lg-6 col-md-6">
                        <label>Nro</label>
                        <input id="nro" name="nro" type="text" value="" class="form-control" placeholder="Ingrese nro">
                    </div>

                   

                    

                    <div class="form-group col-lg-6 col-md-6">
                        <label>Modelo</label>
                        <input type="text" name='modelo' id="modelo" class='form-control' placeholder='Ingrese modelo' value="">
                    </div>
                    <div class="form-group col-lg-6 col-md-6">
                        <label>Serie</label>
                        <input type="text" name='serie' id="serie" class='form-control' placeholder='Ingrese serie' value="">
                    </div>


                    <div class="form-group col-lg-6 col-md-6">
                        <label>Direccion</label>
                        <input type="text" name='direccion' id="direccion" class='form-control' placeholder='Ingrese direccion' value="">
                    </div>

                    <input type="hidden" name='tipo_terminal' id="tipo_terminal" class='form-control' placeholder='Ingrese medida' value="3">
                    <div class="form-group col-lg-12 col-md-12">
                        <label>Observaciones</label>
                        <input type="text" name='observaciones' id="terminal_observaciones" class='form-control' placeholder='Ingrese observaciones' value="">
                    </div>

                </div>

            </div>

</div>
            <div class="main-card mb-3 card">
                <div class="card-body">
                    @if(!isset($sistemaOnsite))
                    <button type="submit" class="btn btn-primary btn-pill mt-2" name="botonGuardar" value="1">Guardar</button>
                    <button type="submit" class="btn btn-primary btn-pill mt-2" name="botonGuardarCerrar" value="1">Guardar y Cerrar</button>
                    @else
                    <button type="submit" class="btn btn-success btn-pill mt-2" name="botonGuardarSistemaOnsite" value="1">Guardar</button>
                    @endif

    </form>


    </div>
    </div>

@endsection

@section('scripts')
    <script type="text/javascript" src="{!! asset('/assets/js/_onsite/terminales-onsite.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('/assets/js/_onsite/reparaciones-onsite-sucursales.js') !!}"></script>
@endsection
