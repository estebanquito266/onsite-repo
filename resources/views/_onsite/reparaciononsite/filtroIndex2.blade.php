<div id="accordion" class="accordion-wrapper mb-3 myaccordion">


    <div id="headingFilter" class="card-header card-header-assurant card-header-tab" style="cursor: pointer;padding: .7rem;">
        <div style="width: 100%;" class="myresponsive-text collapsed myaccordion-button simil-card-header card-header-title card-header-assurant font-size-lg text-capitalize text-capitalize-assurant font-weight-normal " data-toggle="collapse" data-target="#collapseFilter1" aria-expanded="false" aria-controls="collapseFilter">



            <i class="header-icon pe-7s-filter ml-2 mr-3 text-muted opacity-6"> </i>
            <div class="row row100" id="row100">
                <div class="col-md-11" style="padding-top: 3px;font-size: 1.1rem !important;">

                    FILTROS
                </div>
                <div class="col-md-1">
                    <button class="btn-icon btn-icon-only btn-shadow btn-outline-2x btn btn-outline-success btn-xs print-btn" style="display: none;"><i class="lnr-printer btn-icon-wrapper"> </i></button>

                </div>
            </div>
        </div>

    </div>

    <div data-parent="#accordion" id="collapseFilter1" aria-labelledby="headingFilter" class="collapse" style="">
            <div class=" border mb-5 pl-3 pr-3 pb-3" id="filtro">
                <form action="{{ url('filtrarReparacionOnsite') }}" method="POST" id="filtrarReparacionOnsite">
                    @csrf
                    <input name="ruta" type="hidden" value="{{ (isset($ruta)) ? $ruta : 'reparacionOnsite' }}">
                    <input name="includeEmpresa" type="hidden" value="{{ (isset($includeEmpresa)) ? $includeEmpresa : '' }}">
                    <input name="excludeEmpresa" type="hidden" value="{{ (isset($excludeEmpresa)) ? $excludeEmpresa : '' }}">

                    <div class="form-row mt-3">
                        <div class="col-lg-3">
                            <div class="form-group">
                                <label>Ingrese texto </label>
                                <input maxlength="150" type="text" name="texto" class="form-control" placeholder="Ingrese el texto a buscar" id="texto" value="{{ ((null !== old('texto') && Request::path() =='filtrarReparacionOnsite')?old('texto'):'')}}" autocomplete="off">
                            </div>
                        </div>
                        <div class=' col-lg-3'>
                            <div class='form-group'>
                                <label>Empresa</label>
                                <select name="id_empresa" id="id_empresa" class="form-control "  placeholder="Seleccione actividad">
                                <option selected="selected" disabled value=""> Seleccione empresa</option>
                                    @foreach ($empresasOnsite as $empresaOnsite)
                                    <option value="{{ $empresaOnsite->id }}"
                                        {{ ((null !== old('id_empresa') && Request::path() =='filtrarReparacionOnsite' && in_array($empresaOnsite->id,[old('id_empresa')]))?'selected':'')}}>[{{ $empresaOnsite->id }}] {{ $empresaOnsite->nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class=' col-lg-3'>
                            <div class='form-group'>
                                <label>TipoServicio</label>
                                <select name="id_tipo_servicio" id="id_tipo_servicio" class="form-control" placeholder=" Seleccione tipo de servicio">
                                    <option selected="selected" value=""> Seleccione tipo de servicio</option>
                                    @foreach ($tiposServicios as $id => $nombre)
                                    <option value="{{ $id }}" {{ ((null !== old('id_tipo_servicio') && Request::path() =='filtrarReparacionOnsite' &&  old('id_tipo_servicio') == $id)?'selected':'')}}>{{ $nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class=' col-lg-3'>
                            <div class='form-group'>
                                <label>Mostrar</label>
                                <select name="estados_activo[]" id="estados_activo" class="form-control multiselect-dropdown2" multiple="multiple">
                                    <option value="activos" {{ (isset($estados_activo)  && in_array('activos',$estados_activo)) ? 'selected' : '' }}>Activos</option>
                                    <option value="inactivos" {{ (isset($estados_activo)  && in_array('inactivos',$estados_activo)) ? 'selected' : '' }}>Inactivos</option>
                                    <option value="todos" {{ (isset($estados_activo) && in_array('todos',$estados_activo)) ? 'selected' : '' }}>Todos</option>
                                </select>
                            </div>
                        </div>

                        <div class=' col-lg-3'>
                            <div class='form-group'>
                                <label>Estado </label>
                                <select name="id_estado" id="id_estado" class="form-control" >
                                <option selected="selected" value="">Seleccione estado</option>
                                    @foreach ($estadosOnsite as $estadoOnsite)
                                    <option value="{{ $estadoOnsite->id }}"
                                        {{ ((null !== old('id_estado') && Request::path() =='filtrarReparacionOnsite' && in_array($estadoOnsite->id,[old('id_estado')]))?'selected':'')}}>
                                        {{ $estadoOnsite->nombre }}
                                    </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class=' col-lg-3'>
                            <div class='form-group'>
                                <label>Técnicos </label>
                                <select name="id_tecnico" id="id_tecnico" class="form-control">
                                    <option selected="selected" value="">Seleccione técnico</option>
                                    @foreach ($tecnicosOnsite as $id => $nombre)
                                    <option value="{{ $id }}" {{ ((null !== old('id_tecnico') && Request::path() =='filtrarReparacionOnsite' &&  old('id_tecnico') == $id)?'selected':'')}}>{{ $nombre }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class=' col-lg-3'>
                            <div class='form-group'>
                                <label>Fecha de Vencimiento </label>
                                <div class="input-group">

                                    <input type="date" name="fecha_vencimiento" class="form-control" id="fechavencimiento" value="{{ ((null !== old('fecha_vencimiento') && Request::path() =='filtrarReparacionOnsite')?old('fecha_vencimiento'):'')}}" autocomplete="off">
                                </div>
                            </div>
                        </div>
                        <div class=' col-lg-3'>
                            <div class='form-group'>
                                <label>Sucursal</label>
                                <input maxlength="150" type="text" name="sucursal_onsite" class="form-control" placeholder="Ingrese clave o razón social" id="sucursal_onsite" value="{{ ((null !== old('sucursal_onsite') && Request::path() =='filtrarReparacionOnsite')?old('sucursal_onsite'):'')}}" autocomplete="off">
                            </div>
                        </div>
                        <div class=' col-lg-3'>
                            <div class='form-group'>
                                <label>Terminal </label>
                                <input maxlength="150" type="text" name="terminal_onsite" class="form-control" placeholder="Ingrese clave, marca, modelo, serie o rótulo " id="terminal_onsite" value="{{ ((null !== old('terminal_onsite') && Request::path() =='filtrarReparacionOnsite')?old('terminal_onsite'):'')}}" autocomplete="off">
                            </div>
                        </div>




                        <div class='col-12'>
                            <div class="row justify-content-end">
                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-primary btn-block pull-right mt-1 clickoverlay">Filtrar</button>
                                </div>

                                

                                <div class="col-md-2">
                                    <a href="/importarReparacionOnsite" class="btn btn-info btn-block pull-right mt-1 clickoverlay" id="btn-limpiar">Importar</a>

                                </div>

                                <div class="col-md-2">
                                    <a href="/reparacionOnsite" class="btn btn-danger btn-block pull-right mt-1 clickoverlay" id="btn-limpiar">Limpiar</a>

                                </div>

                                

                                <div class="col-md-2">
                                    <button type="submit" class="btn btn-info btn-block pull-right mt-1 clickoverlay" name="boton" value="csv">Filtrar y Generar CSV</button>

                                </div>

                                <div class="col-md-2">
                                    <a href="/exports/listado_reparaciononsite_{{ $user_id }}.xlsx" class="btn btn-info btn-block pull-right mt-1" name="boton" value="csv">Exportar</a>

                                </div>
                                
                            </div>
                        </div>


                    </div>

                </form>
            </div>

    </div>

</div>

<style>



        
input::-moz-placeholder,
textarea::-moz-placeholder {
    color:#c9c9c9 !important;
}

input:-ms-input-placeholder,
textarea:-ms-input-placeholder {
    color:#c9c9c9 !important;
}

input::placeholder,
textarea::placeholder {
    color:#c9c9c9 !important;
}


.row100 {
width: 100%;
}
.mytab-header {
display: flex;
align-items: center;
border-bottom-width: 1px;
padding-top: 0;
padding-bottom: 0;
padding-right: .625rem;
height: 3.5rem;
padding: 0 !important;
}


.simil-card-header {
color: #555f78eb;
}

.simil-card-header:not(.collapsed) {
color: #000000b5;
}


.simil-card-header:hover {
color: #000000b5;
transition: transform 0.2s; 
}



.myaccordion-button {
display: flex;
}

.myaccordion-button::after {
flex-shrink: 0;
width: 1.35rem;
height: 1.35rem;
margin-left: auto;
margin-right: 10px;
content: "";
background-image: url('/assets/images/down.svg');
background-repeat: no-repeat;
background-size: 1.25rem;
transition: transform .2s ease-in-out;
}

.myaccordion-button:not(.collapsed)::after {
background-image: url('/assets/images/down.svg');
transform: rotate(-180deg);
}

.sem-gradient-danger {
background-image: linear-gradient(140deg, #981a38 -30%, #d92550 90%);
background-color: #981a38;
border-color: #981a38;
color: #fff;
width: 20px !important;
padding-top: 1px;
}

.sem-gradient-warning {
background-image: linear-gradient(140deg, #c78f07 -30%, #f7b924 90%);
background-color: #c78f07;
border-color: #c78f07;
color: #fff;
width: 20px !important;
padding-top: 1px;
}

.sem-gradient-success {
background-image: linear-gradient(140deg, #298957 -30%, #3ac47d 90%);
background-color: #298957;
border-color: #298957;
color: #fff;
width: 20px !important;
padding-top: 1px;
}

</style>

<script>


    document.addEventListener("DOMContentLoaded", function(event) {

   
            


                $(".multiselect-dropdown2").select2({
                    theme: "bootstrap4",
                    placeholder:"Seleccione empresa"
                });

                $("#id_tipo_servicio").select2({
                    theme: "bootstrap4",
                    placeholder: " Seleccione tipo de servicio",
                    inheritClass: true
                });

                $("#estado_activo").select2({
                    theme: "bootstrap4",
                    inheritClass: true
                });

                $("#id_estado").select2({
                    theme: "bootstrap4",
                    placeholder: " Seleccione estado",
                    inheritClass: true
                });

                $("#id_tecnico").select2({
                    theme: "bootstrap4",
                    placeholder: "Seleccione técnico",
                    inheritClass: true
                });

                $("#id_empresa").select2({
                    theme: "bootstrap4",
                    placeholder: "Seleccione empresa",
                    inheritClass: true
                });
});

</script>