



@extends('layouts.baseprocrud')

@section('content')
    @include('_onsite.terminalonsite.top')

    <div class="main-card mb-3 card">
        <div class="card-header">
            <h3 class="mr-3">Modificar Unidad Exterior</h3>
        </div>
    </div>

    <form method="POST" action="{{ url('terminalOnsite/' . $terminalOnsite->nro) }}" id="terminalOnsiteForm" enctype="multipart/form-data">
        {{ csrf_field() }}
        <input name="_method" type="hidden" value="PUT">

        <div class="main-card mb-3 card logica-terminal-empresa-modal">
            <div class="card-header bg-secondary text-white">Empresa Onsite</div>
            <div class="card-body">
                <div class="form-row mt-12">
                    <div class="form-group col-lg-12 col-md-12">
                        <select name="empresa_onsite_id" id="terminal_empresa_onsite_id" class="form-control multiselect-dropdown " placeholder='Seleccione empresa onsite id' {!! (Request::segment(2)=='create' ) ? 'onchange="validarGenerarNumeroTerminal();"' : '' !!}">
                        <option value=""> -- Seleccione uno --</option>
                        @foreach ($empresasOnsite as $empresaOnsite)
                            <option value="{{ $empresaOnsite->id }}" selected>{{ $terminalOnsite->empresa_onsite->nombre }}</option>
                            @endforeach
                            </select>
                    </div>
                </div>
            </div>
        </div>

        <div class="main-card mb-3 card ">
            <div class="card-header bg-secondary text-light">Datos Terminal</div>
            <div class="card-body">
                <div class="form-row mt-3">
                    <div class="form-group col-lg-6 col-md-6">
                        <label>Nro</label>
                            <input id="nro" name="nro" type="text" disabled value="{{$terminalOnsite->nro}}" class="form-control" placeholder="Ingrese nro">

                    </div>

                    <div class="form-group col-lg-6 col-md-6">
                        <label for="all_terminales_sucursal">All Terminales - Sucursal</label>
                        <input type="checkbox" id="all_terminales_sucursal" name="all_terminales_sucursal" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="primary" data-offstyle="secondary" @if($terminalOnsite->all_terminales_sucursal) checked @endif >
                    </div>

                    <div class="form-group col-lg-12 col-md-12 logica-terminal-sucursal-modal">
                        <label>Sucursal Onsite Id</label>

                        <div class="form-group input-group ">
                            <select name="sucursal_onsite_id" id="terminal_sucursal_onsite_id" class=" multiselect-dropdown form-control mr-1" placeholder='Seleccione sucursal onsite id'>
                                <option value=""> -- Seleccione uno --</option>
                                @foreach ($sucursalesOnsite as $sucursalOnsite)
                                    <option value="{{ $sucursalOnsite->id }}" {{ ((isset($terminalOnsite) && isset($terminalOnsite->sucursal_onsite_id) && $terminalOnsite->sucursal_onsite_id == $sucursalOnsite->id)?'selected':'') }}>{{ $sucursalOnsite->codigo_sucursal .' - '.$sucursalOnsite->razon_social }}</option>
                                @endforeach
                            </select>

                            <span class="input-group-btn">
						<button class="btn btn-warning " type="button" id="refreshSucursal"><i class="fa fa-reply-all"></i></button>
					</span>
                        </div>
                    </div>

                    <div class="form-group col-lg-6 col-md-6">
                        <label>Modelo</label>
                        <input type="text" name='modelo' id="modelo" class='form-control' placeholder='Ingrese modelo' value="{{$terminalOnsite->modelo}}">
                    </div>
                    <div class="form-group col-lg-6 col-md-6">
                        <label>Serie</label>
                        <input type="text" name='serie' id="serie" class='form-control' placeholder='Ingrese serie' value="{{$terminalOnsite->serie}}">
                    </div>


                    <div class="form-group col-lg-6 col-md-6">
                        <label>Medida 1 a</label>
                        <input type="number" step="0.01" name='medida_figura_1_a' id="medida_figura_1_a" class='form-control' placeholder='Ingrese medida' value="{{$terminalOnsite->medida_figura_1_a}}">
                    </div>

                    <div class="form-group col-lg-6 col-md-6">
                        <label>Medida 1 b</label>
                        <input type="number" step="0.01" name='medida_figura_1_b' id="medida_figura_1_b" class='form-control' placeholder='Ingrese medida' value="{{$terminalOnsite->medida_figura_1_b}}">
                    </div>
                    <div class="form-group col-lg-6 col-md-6">
                        <label>Medida 1 c</label>
                        <input type="number" step="0.01" name='medida_figura_1_c' id="medida_figura_1_c" class='form-control' placeholder='Ingrese medida' value="{{$terminalOnsite->medida_figura_1_c}}">
                    </div>
                    <div class="form-group col-lg-6 col-md-6">
                        <label>Medida 1 b</label>
                        <input type="number" step="0.01" name='medida_figura_1_d' id="medida_figura_1_d" class='form-control' placeholder='Ingrese medida' value="{{$terminalOnsite->medida_figura_1_d}}">
                    </div>

                    <div class="form-group col-lg-6 col-md-6">
                        <label>Medida 2 a</label>
                        <input type="number" step="0.01" name='medida_figura_2_a' id="medida_figura_2_a" class='form-control' placeholder='Ingrese medida' value="{{$terminalOnsite->medida_figura_2_a}}">
                    </div>

                    <div class="form-group col-lg-6 col-md-6">
                        <label>Medida 2 b</label>
                        <input type="number" step="0.01" name='medida_figura_2_b' id="medida_figura_2_b" class='form-control' placeholder='Ingrese medida' value="{{$terminalOnsite->medida_figura_2_b}}">
                    </div>

                    <div class="form-group col-lg-6 col-md-6">
                        <label>Medida 2 c</label>
                        <input type="number" step="0.01" name='medida_figura_2_c' id="medida_figura_2_c" class='form-control' placeholder='Ingrese medida' value="{{$terminalOnsite->medida_figura_2_c}}">
                    </div>
                    <div class="form-group col-lg-6 col-md-6"></div>
                    <div class="form-group col-lg-4 col-md-4">
                        <label>Anclaje Piso</label>
                        <input type="checkbox" name='anclaje_piso' id="anclaje_piso" class='form-control'  data-html="true"  data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="primary" data-offstyle="secondary" value="{{$terminalOnsite->anclaje_piso}}" @if($terminalOnsite->anclaje_piso)  checked  @endif>
                    </div>
                    <div class="form-group col-lg-4 col-md-4">
                        <label>Contra Sifon</label>
                        <input type="checkbox" name='contra_sifon' id="contra_sifon" class='form-control' data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="primary" data-offstyle="secondary" value="{{$terminalOnsite->contra_sifon}}"  @if($terminalOnsite->contra_sifon)  checked   @endif>
                    </div>
                    <div class="form-group col-lg-4 col-md-4">
                        <label>500mm última derivación curva</label>
                        <input type="checkbox" name='mm_500_ultima_derivacion_curva' id="mm_500_ultima_derivacion_curva" class='form-control' data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="primary" data-offstyle="secondary"  value="{{$terminalOnsite->mm_500_ultima_derivacion_curva}}" @if($terminalOnsite->mm_500_ultima_derivacion_curva)  checked  @endif>
                    </div>
                    <div class="form-group col-lg-12 col-md-12">
                        <label>Observaciones</label>
                        <input type="text" name='observaciones' id="observaciones" class='form-control' placeholder='Ingrese observaciones' value="{{$terminalOnsite->observaciones}}">
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
