@extends('layouts.baseprocrud')

@section('content')
    @include('_onsite.terminalonsite.top')

    <div class="main-card mb-3 card">
        <div class="card-header">
            <h3 class="mr-3">Modificar Unidad Interior</h3>
        </div>
    </div>

    <form method="POST" action="{{ url('terminalOnsite/' . $terminalOnsite->nro) }}" id="terminalOnsiteForm" enctype="multipart/form-data">
        {{ csrf_field() }}
        <input name="_method" type="hidden" value="PUT">
        <input type="hidden" name="company_id" id="terminal_company_id" value="{{$terminalOnsite->company_id}}">
        <input type="hidden" name="empresa_onsite_id" id="terminal_empresa_onsite_id" value="{{$terminalOnsite->empresa_onsite_id}}">
        <input type="hidden" name="sucursal_onsite_id" id="terminal_suscursal_onsite_id" value="{{$terminalOnsite->sucursal_onsite_id}}">
        <input type="hidden" name="sistemas_onsite_id" id="terminal_sistemas_onsite_id" value="{{$terminalOnsite->sistemas_onsite_id}}">


        <!--div class="main-card mb-3 card logica-terminal-empresa-modal">
            <div class="card-header bg-secondary text-white">Empresa Onsite</div>
            <div class="card-body">
                <div class="form-row mt-12">
                    <div class="form-group col-lg-12 col-md-12">
                        <select name="empresa_onsite_id" id="terminal_empresa_onsite_id" class="form-control multiselect-dropdown " placeholder='Seleccione empresa onsite id' {!! (Request::segment(2)=='create' ) ? 'onchange="validarGenerarNumeroTerminal();"' : '' !!}">
                        <option value=""> -- Seleccione uno --</option>
                        @foreach ($empresasOnsite as $empresaOnsite)
                            <option value="{{ $empresaOnsite->id }}" @if($empresaOnsite->id == $terminalOnsite->empresa_onsite_id) selected @endif>{{ $empresaOnsite->nombre }}</option>
                        @endforeach
                            </select>
                    </div>
                </div>
            </div>
        </div-->

        <div class="main-card mb-3 card ">
            <div class="card-header bg-secondary text-light">Datos Terminal</div>
            <div class="card-body">
                <div class="form-row mt-3">
                    <div class="form-group col-lg-6 col-md-6">
                        <label>Nro</label>
                        @if(Request::segment(2)=='create' || Request::segment(1)=='reparacionOnsite' )
                            <input id="nro" name="nro" type="text" value="" class="form-control" placeholder="Ingrese nro">
                        @else
                            <input id="nro" name="nro" type="text" readonly class="form-control" placeholder="Ingrese nro" value="{{ $terminalOnsite->nro }}">
                        @endif
                    </div>

                    <div class="form-group col-lg-6 col-md-6">
                        <label for="all_terminales_sucursal">All Terminales - Sucursal</label>
                        <input type="checkbox" id="all_terminales_sucursal" name="all_terminales_sucursal" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="primary" data-offstyle="secondary" @if($terminalOnsite->all_terminales_sucursal) checked @endif >
                    </div>

                    <!--div class="form-group col-lg-12 col-md-12 logica-terminal-sucursal-modal">
                        <label>Sucursal Onsite Id</label>

                        <div class="form-group input-group ">
                            <select name="sucursal_onsite_id" id="terminal_sucursal_onsite_id" class=" multiselect-dropdown form-control mr-1" placeholder='Seleccione sucursal onsite id'>
                                <option value=""> -- Seleccione uno --</option>
                                @foreach ($sucursalesOnsite as $sucursalOnsite)
                                    <option value="{{ $sucursalOnsite->id }}" @if($sucursalOnsite->id == $terminalOnsite->sucursal_onsite_id) selected @endif>{{ $sucursalOnsite->codigo_sucursal .' - '.$sucursalOnsite->razon_social }}</option>
                                @endforeach
                            </select>

                            <span class="input-group-btn">
						<button class="btn btn-warning " type="button" id="refreshSucursal"><i class="fa fa-reply-all"></i></button>
					</span>
                        </div>
                    </div-->

                    <div class="form-group col-lg-6 col-md-6">
                        <label>Modelo</label>
                        <input type="text" name='modelo' id="modelo" class='form-control' placeholder='Ingrese modelo' value="{{$terminalOnsite->modelo}}">
                    </div>
                    <div class="form-group col-lg-6 col-md-6">
                        <label>Serie</label>
                        <input type="text" name='serie' id="serie" class='form-control' placeholder='Ingrese serie' value="{{$terminalOnsite->serie}}">
                    </div>


                    <div class="form-group col-lg-6 col-md-6">
                        <label>Direccion</label>
                        <input type="text" name='direccion' id="direccion" class='form-control' placeholder='Ingrese medida' value="{{$terminalOnsite->direccion}}">
                    </div>

                    <div class="form-group col-lg-12 col-md-12">
                        <label>Observaciones</label>
                        <input type="text" name='observaciones' id="terminal_observaciones" class='form-control' placeholder='Ingrese observaciones' value="{{$terminalOnsite->observaciones}}">
                    </div>

                </div>

            </div>


        <div class="main-card mb-3 card">
            <div class="card-body">
                    <button type="submit" class="btn btn-primary btn-pill mt-2" name="botonGuardar" value="1">Guardar</button>
                    <button type="submit" class="btn btn-primary btn-pill mt-2" name="botonGuardarCerrar" value="1">Guardar y Cerrar</button>

    </form>

    <form method="POST" action="{{ url('terminalOnsite/' . $terminalOnsite->nro) }}" style="display:inline;">
        {{ csrf_field() }}
        <input name="_method" type="hidden" value="DELETE">
        <button type="submit" class="btn btn-danger btn-pill mt-2" name="botonEliminar" value="1">Eliminar</button>
    </form>
    </div>
    </div>

@endsection

@section('scripts')
    <script type="text/javascript" src="{!! asset('/assets/js/_onsite/terminales-onsite.js') !!}"></script>
    <script type="text/javascript" src="{!! asset('/assets/js/_onsite/reparaciones-onsite-sucursales.js') !!}"></script>
@endsection
