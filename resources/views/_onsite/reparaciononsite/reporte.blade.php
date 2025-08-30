@extends('layouts.baseprolist')

@section('content')

@include('_onsite.reparaciononsite.top')

<div class="main-card mb-3 card">
    <div class="card-header">
     
        
        @switch(intval($exitoso))
            @case(3)
                <h3 class="mr-3">Reporte de Servicios Onsite</h3>
                @break

            @case(4)
                <h3 class="mr-3">Reporte de Casos Activos</h3>
                @break

            @default
                <h3 class="mr-3">Reporte de Reparaciones Onsite</h3>
        @endswitch

    </div>
    <div class="card-body text-center">

        <div class=" border mb-5 pl-3 pr-3 pb-3" id="filtro">
            <form action="{{ url('generarReporteReparacionOnsite') }}" method="POST">
                {{ csrf_field() }}
                <input type="hidden" name="exitoso" value="{{$exitoso}}">
                <div class="form-row mt-3">
                    <div class="col-lg-4">
                        <div class="form-group">
                            <label>Ingrese texto </label>
                            <input type="text" name="texto" class="form-control" placeholder="Ingrese el texto de su búsqueda" id="texto" value="{{ (isset($texto)) ? $texto : '' }}">
                        </div>
                    </div>
                    <div class=' col-lg-4'>
                        <div class='form-group'>
                            <label>Empresa Onsite</label>
                            <select name="id_empresa[]" id="id_empresa" class="form-control multiselect-dropdown" multiple required>
                                @foreach ($empresasOnsite as $empresaOnsite)
                                <option value="{{ $empresaOnsite->id }}" {{ (isset($id_empresa) && $id_empresa == $empresaOnsite->id) ? 'selected' : '' }}>[{{ $empresaOnsite->id }}] {{ $empresaOnsite->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class=' col-lg-4'>
                        <div class='form-group'>
                            <label>TipoServicio Onsite</label>
                            <select name="id_tipo_servicio" id="id_tipo_servicio" class="form-control">
                                <option selected="selected" value="">Seleccione tiposervicio onsite</option>
                                @foreach ($tiposServicios as $id => $nombre)
                                <option value="{{ $id }}" {{ (isset($id_tipo_servicio) && $id_tipo_servicio == $id) ? 'selected' : '' }}>{{ $nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    
                    @if(intval($exitoso) === 3)
                        <div class=' col-lg-4'>
                            <div class='form-group swithdisabled swithdisabled-on '>
                                <label>Solo Estados Activos</label>
                                <input disabled type="checkbox" id="estados_activo" name="estados_activo" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="primary" data-offstyle="secondary" {{ (($estados_activo) ? '' : '')}}>
                            </div>
                        </div>
                    @else 
                        <div class=' col-lg-4'>
                            <div class='form-group {{ intval($exitoso) === 4 ? "swithdisabled swithdisabled-on" : "" }} '>
                                <label>Solo Estados Activos</label>
                                <input {{ intval($exitoso) === 4 ? 'disabled' : '' }} type="checkbox" id="estados_activo" name="estados_activo" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="primary" data-offstyle="secondary" {{ (($estados_activo) ? 'checked' : '')}}>
                            </div>
                        </div>
                    @endif
                    @if($exitoso == 0)
                    <div class=' col-lg-4'>
                        <div class='form-group'>
                            <label>Estado Onsite</label>
                            <select name="id_estado[]" id="id_estado" class="form-control multiselect-dropdown" multiple="multiple">
                                @foreach ($estadosOnsite as $estadoOnsite)
                                <option value="{{ $estadoOnsite->id }}" {{ (isset($id_estado) && $id_estado == $estadoOnsite->id) ? 'selected' : '' }}>{{ $estadoOnsite->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @endif
                    <div class=' col-lg-4'>
                        <div class='form-group'>
                            <label>Técnicos Onsite</label>
                            <select name="id_tecnico" id="id_tecnico" class="form-control">
                                <option selected="selected" value="">Seleccione técnico onsite</option>
                                @foreach ($tecnicosOnsite as $id => $nombre)
                                <option value="{{ $id }}" {{ (isset($id_tecnico) && $id_tecnico == $id) ? 'selected' : '' }}>{{ $nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class=' col-lg-4'>
                        <div class='form-group'>
                            <label>Ingrese Fecha de Vencimiento </label>
                            <div class="input-group">
                                <div class="input-group-prepend ">
                                    <div class="input-group-text">
                                        <i class="fa fa-calendar-alt"></i>
                                    </div>
                                </div>
                                <input type="text" name="fecha_vencimiento" class="form-control" id="fechavencimiento" value="{{ (isset($fecha_vencimiento)) ? $fecha_vencimiento : '' }}" autocomplete="off">
                            </div>
                        </div>
                    </div>
                    <div class=' col-lg-4'>
                        <div class='form-group'>
                            <label>Sucursal Onsite</label>
                            <input type="text" name="sucursal_onsite" class="form-control" placeholder="Ingrese clave o razón social" id="sucursal_onsite" value="{{ (isset($sucursal_onsite)) ? $sucursal_onsite : '' }}">
                        </div>
                    </div>
                    <div class=' col-lg-4'>
                        <div class='form-group'>
                            <label>Terminal Onsite</label>
                            <input type="text" name="terminal_onsite" class="form-control" placeholder="Ingrese clave, marca, modelo, serie o rótulo " id="terminal_onsite" value="{{ (isset($terminal_onsite)) ? $terminal_onsite : '' }}">
                        </div>
                    </div>


                    <div class=' col-lg-6'>
                        <div class='form-group'>
                            <label>Ingrese Fecha de Creación Desde </label>
                            <div class="input-group">
                                <div class="input-group-prepend ">
                                    <div class="input-group-text">
                                        <i class="fa fa-calendar-alt"></i>
                                    </div>
                                </div>
                                <input type="date" name="fecha_creacion_desde" class="form-control" id="fechacreaciondesde" value="{{ (isset($fecha_creacion_desde)) ? $fecha_creacion_desde : '' }}" autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <div class=' col-lg-6'>
                        <div class='form-group'>
                            <label>Ingrese Fecha de Creación Hasta </label>
                            <div class="input-group">
                                <div class="input-group-prepend ">
                                    <div class="input-group-text">
                                        <i class="fa fa-calendar-alt"></i>
                                    </div>
                                </div>
                                <input type="date" name="fecha_creacion_hasta" class="form-control" id="fechacreacionhasta" value="{{ (isset($fecha_creacion_hasta)) ? $fecha_creacion_hasta : '' }}" autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <div class=' col-lg-6'>
                        <div class='form-group'>
                            <label>Ingrese Fecha de Ingreso Desde </label>
                            <div class="input-group">
                                <div class="input-group-prepend ">
                                    <div class="input-group-text">
                                        <i class="fa fa-calendar-alt"></i>
                                    </div>
                                </div>
                                <input type="date" name="fecha_ingreso_desde" class="form-control" id="fechacreaciondesde" value="{{ (isset($fecha_ingreso_desde)) ? $fecha_ingreso_desde : '' }}" autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <div class=' col-lg-6'>
                        <div class='form-group'>
                            <label>Ingrese Fecha de Ingreso Hasta </label>
                            <div class="input-group">
                                <div class="input-group-prepend ">
                                    <div class="input-group-text">
                                        <i class="fa fa-calendar-alt"></i>
                                    </div>
                                </div>
                                <input type="date" name="fecha_ingreso_hasta" class="form-control" id="fechacreacionhasta" value="{{ (isset($fecha_ingreso_hasta)) ? $fecha_ingreso_hasta : '' }}" autocomplete="off">
                            </div>
                        </div>
                    </div>

                    <div class='col-12'>
                        <div class='form-group text-center'>

                            <table class="table">
                                <thead>
                                    <tr>
                                        <th>id</th>
                                        <th>archivo</th>
                                        <th>creado</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reportesGenerados as $exportacion)
                                    <tr>
                                        <td scope="row">{{$exportacion->id}}</td>
                                        <td><a href="/exportaciones/{{$exportacion->notificacion}}">{{$exportacion->notificacion}}</a></td>
                                        <td>{{$exportacion->created_at}}</td>
                                    </tr>
                                    @endforeach

                                </tbody>
                            </table>



                            <button type="submit" class="btn btn-primary btn-pill pull-right" name="boton" value="filtrar">Generar nuevo Reporte</button>
                        </div>
                    </div>

                </div>
            </form>
        </div>


        <div class="form-row mt-3">
            <div class="form-group col-12 text-center">
                <a href="exports/listado_reparaciononsite_extendido_{{ $user_id }}.xlsx" class="btn btn-success btn-lg btn-block p-2 m2" style="display:inline;">Descargar Reporte de Reparaciones Onsite</a>
            </div>

        </div>
    </div>
</div>

@endsection

@section('scripts')
<script type="text/javascript" src="{!! asset('/assets/js/_onsite/reparacion-reporte.js') !!}"></script>

<style>
		.swithdisabled-on .btn-primary.toggle-on {
			background-color: #829ee6 !important;
			/* Color de fondo */
		}

		.swithdisabled-on .toggle-off {
			font-size: 12px !important;
		}

		.swithdisabled-on .toggle {
			border: 2px solid #e1e1e1;
		}
	</style>

@endsection


	
