<style>
    input.select2-search__field {
        width: 1000px;
    }
</style>

<div class="main-card mb-3 card formulario_obra" hidden>
    <div class="card-body">
        <div id="smartwizard" class="sw-main sw-theme-default">
            <ul class="forms-wizard nav nav-tabs step-anchor">

                <li class="nav-item active">
                    <a href="#step-1" class="nav-link">
                        <em>1</em><span>Empresa y Obra</span>
                    </a>
                </li>

                <li class="nav-item done">
                    <a href="#step-2" class="nav-link">
                        <em>2</em><span>Tarea</span>
                    </a>
                </li>

                <li class="nav-item done">
                    <a href="#step-3" class="nav-link">
                        <em>3</em><span>Documentos</span>
                    </a>
                </li>

                <li class="nav-item done">
                    <a href="#step-4" class="nav-link">
                        <em>4</em><span>Finalizar</span>
                    </a>
                </li>

                
            </ul>
            <div class="form-wizard-content sw-container tab-content" style="min-height: 478.734px;">


                <!-- empresa y obra ############### -->
                <div id="step-1" class="tab-pane step-content" style="display: block;">

                    <div class="form-row mt-3">
                        @if( Session::get('perfilAdmin') || Session::get('perfilAdminOnsite'))
                        <div class="form-group col-12 col-md-12">
                            <label>Seleccione Empresa Instaladora</label>
                            <select name="empresa_instaladora_admins" id="empresa_instaladora_admins" class="multiselect-dropdown form-control">
                                <option value="0">Seleccione</option>
                                @foreach ($empresas_instaladoras_admins as $empresa)
                                <option value="{{ $empresa->id }}" data-email="{{ $empresa->email }}" data-nombre="{{ $empresa->nombre }}" data-telefono="{{ $empresa->celular }}" {{old('empresa')==$empresa->id ? 'selected':null}}>{{$empresa->nombre}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-12 col-md-3">
                            <label>Empresa Instaladora</label>
                            <input readonly name='empresa_instaladora_nombre' id="empresa_instaladora_nombre" class='form-control'>
                            <input readonly type="hidden" name="empresa_instaladora_id" id="empresa_instaladora_id">
                            <input type="hidden" name="user_id" id="user_id" value="{{ (isset($user)?$user->id:null) }}">
                        </div>
                        <div class="form-group col-12 col-md-3">
                            <label>Email </label>
                            <input readonly name='responsable_email' id="responsable_email" class='form-control'>
                        </div>
                        <div class="form-group col-12 col-md-3">
                            <label>Nombre </label>
                            <input readonly name='responsable_nombre' id="responsable_nombre" class='form-control'>
                        </div>
                        <div class="form-group col-12 col-md-3">
                            <label>Teléfono </label>
                            <input readonly name='responsable_telefono' id='responsable_telefono' class='form-control'>
                        </div>

                        @else
                        <div class="form-group col-12 col-md-3">
                            <label>Empresa Instaladora</label>
                            <input readonly name='empresa_instaladora_nombre' id="empresa_instaladora_nombre" class='form-control' value="{{ (isset($user)?$user->empresa_instaladora[0]->nombre:null) }}">
                            <input type="hidden" name="empresa_instaladora_id" id="empresa_instaladora_id" value="{{ (isset($user)?$user->empresa_instaladora[0]->id:null) }}">
                            <input type="hidden" name="user_id" id="user_id" value="{{ (isset($user)?$user->id:null) }}">
                        </div>
                        <div class="form-group col-12 col-md-3">
                            <label>Email </label>
                            <input readonly name='responsable_email' id="responsable_email" class='form-control' placeholder='Ingrese responsable_email' value="{{ (isset($user)?$user->empresa_instaladora[0]->email:null) }}">
                        </div>
                        <div class="form-group col-12 col-md-3">
                            <label>Nombre </label>
                            <input readonly name='responsable_nombre' id="responsable_nombre" class='form-control' value="{{ (isset($user)?$user->name:null) }}">
                        </div>
                        <div class="form-group col-12 col-md-3">
                            <label>Teléfono </label>
                            <input readonly name='responsable_telefono' id='responsable_telefono' class='form-control' placeholder='Ingrese responsable_telefono' value="{{ (isset($user)?$user->empresa_instaladora[0]->celular:null) }}">
                        </div>
                        @endif

                    </div>

                    <div class="row pt-3">
                        <div class="col-12 col-md-6">
                            <div class="form-group">
                                <label for="solicitud_tipo_id">Tipo de Solicitud</label>
                                <select class="form-control multiselect-dropdown" name="solicitud_tipo_id" id="solicitud_tipo_id">
                                    @foreach ($solicitudesTipos as $tipo)
                                    <option value="{{$tipo->id}}" data-precio="{{ ((isset($tipo->solicitud_tipo_tarifa_base) && isset($tipo->solicitud_tipo_tarifa_base[0])) ? $tipo->solicitud_tipo_tarifa_base[0]->precio : 0) }}" data-idtarifa="{{ ((isset($tipo->solicitud_tipo_tarifa_base) && isset($tipo->solicitud_tipo_tarifa_base[0])) ? $tipo->solicitud_tipo_tarifa_base[0]->id : 1) }}">{{$tipo->nombre}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="form-group col-12 col-md-6">
                            <label>Técnico Onsite Asignado</label>
                            <select name="id_tecnico_asignado" id="id_tecnico_asignado" class="form-control multiselect-dropdown">
                                <option selected="selected" value="">Seleccione el técnico</option>
                                @foreach ($tecnicosOnsite as $id => $nombre)
                                <option value="{{ $id }}" {{ isset($reparacionOnsite) && $reparacionOnsite->id_tecnico_asignado == $id ? 'selected' : '' }}>{{ $nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>



                    <div class="row pt-3">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="solicitud_tipo_id">Obras</label>
                                <select class="form-control multiselect-dropdown" name="obra_id" id="obra_id">
                                    @if(isset($obrasOnsite))
                                    @foreach ($obrasOnsite as $obra)
                                    <option value="{{$obra->id}}">{{$obra->nombre}}</option>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row pt-3">
                        <div class="col-12">
                            <div class="form-group">
                                <label>Sistemas</label>
                                <select multiple="multiple" name="sistema_onsite_id" id="sistema_onsite_id" class=" multiselect-dropdown" data-mdb-placeholder="Example placeholder">

                                    @if(isset($obrasOnsite))
                                    @foreach ($obrasOnsite as $obra)
                                    <optgroup label="Obra: {{$obra->nombre}}">
                                        @foreach ($obra->sistema_onsite as $sistema)
                                        <option value="{{ $sistema->id }}" data-idobra="{{ $sistema->obra_onsite_id }}" data-nombre_sistema="{{$sistema->nombre}}">{{$sistema->nombre}} (Obra: {{$obra->nombre}})
                                        </option>
                                        @endforeach
                                    </optgroup>
                                    @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="row mt-3  form-group col-12" id="sistemas_seleccionados_solicitud" hidden>

                        <div class="col-12 col-md-12"><strong>Sistemas Seleccionados</strong></div>
                        <div class="col-12 col-md-4">Sistema</div>
                        <div class="col-12 col-md-4">Obs. Internas</div>
                        <div class="col-12 col-md-4">Nota Cliente</div>


                        <div class="row mt-3 form-group col-12" id="tbody_sistemas_seleccionados_solicitud">
                            <!-- completo dinamicamente -->
                        </div>

                    </div>

                    <div class="row pt-3">
                        <div class="col-12">
                            <div class="form-group">
                                <label for="solicitud_id">Solicitudes</label>
                                <select class="form-control multiselect-dropdown" name="solicitud_id" id="solicitud_id"></select>
                            </div>
                        </div>
                    </div>

                    <div class="row form-group col-1">
                        <input readonly type="hidden" class="form-control" name="obra_onsite_id" id="obra_onsite_id" value="{{old ('obra_onsite_id') }}">
                        <input readonly type="hidden" class="form-control" name="obra_nombre" id="obra_nombre">
                        <input readonly type="hidden" class="form-control" name="empresa_onsite_id" id="empresa_onsite_id">
                        <input type="hidden" name='nombre' id="nombre" class='form-control' placeholder='Ingrese nombre' value="{{ old('nombre') }}">

                    </div>




                    <hr>




                </div>

              

                <!-- detalle tarea -->
                <div id="step-2" class="tab-pane step-content" style="display: block;">

                    <div class="form-group col-lg-12 col-md-12">
                        <label for="tarea">Tarea</label>
                        <input type="text" class="form-control mr-2" placeholder="Ingrese tarea" id="tarea" name="tarea" value="{{ isset($reparacionOnsite) ? $reparacionOnsite->tarea : '' }}" autocomplete="off">
                    </div>
                    <div class="form-group col-lg-12 col-md-12">
                        <label>Detalle de Tarea</label>
                        <textarea class="form-control" placeholder="Ingrese detalle de tarea" id="tarea_detalle" name="tarea_detalle" cols="50" rows="10">{{ isset($reparacionOnsite) ? $reparacionOnsite->tarea_detalle : '' }}</textarea>
                    </div>

                    <div class="row pt-3">
                        <div class="form-group  col-lg-6 col-md-5">
                            <label for="id_tipo_servicio">Tipo de Servicio</label>
                            <select name="id_tipo_servicio" id="id_tipo_servicio" class="form-control multiselect-dropdown">
                                <option selected="selected" value="">Seleccione tipo de servicio</option>
                                @foreach ($tiposServicios as $id => $nombre)
                                <option value="{{ $id }}" {{ ((isset($reparacionOnsite) && $reparacionOnsite->id_tipo_servicio == $id) ? 'selected' : '') }}>{{ $nombre }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-lg-6 col-md-5">
                            <label for="id_estado">Estado</label>
                            <select name="id_estado" id="id_estado" class="form-control multiselect-dropdown">
                                <option selected="selected" value="">Seleccione estado</option>
                                @foreach ($estadosOnsite as $estadoOnsite)
                                <option value="{{ $estadoOnsite->id }}" {{ ((isset($reparacionOnsite) && $reparacionOnsite->id_estado == $estadoOnsite->id) ? 'selected' : '') }}>{{ $estadoOnsite->nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                    <div class="row pt-3">
                        <div class="form-group col-lg-6 col-md-5 ">
                            <label>Fecha Vencimiento</label>
                            <div class="input-group date" id="fecha_vencimiento" data-target-input="nearest">

                                <div class="input-group-append" data-target="#fecha_vencimiento" data-toggle="datetimepicker">
                                    <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                                </div>
                                <input type="text" class="form-control datetimepicker-input" data-target="#fecha_vencimiento" placeholder="Ingrese fecha y hora de vencimiento " name="fecha_vencimiento" value="{{ isset($reparacionOnsite) && !empty($reparacionOnsite->fecha_vencimiento) ? $reparacionOnsite->fecha_vencimiento : '' }}">
                            </div>
                        </div>


                        <div class="form-group  col-lg-6 col-md-5">
                            <label for="prioridad">Prioridad</label>
                            <select name="prioridad" id="prioridad" class="form-control multiselect-dropdown">
                                <option selected="selected" value="">Seleccione prioridad</option>
                                @foreach ($prioridades as $id => $nombre)
                                <option value="{{ $id }}" {{ ((isset($reparacionOnsite) && $reparacionOnsite->prioridad == $id) ? 'selected' : '') }}>{{ $nombre }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>

                </div>

                <!-- doc links -->

                <div id="step-3" class="tab-pane step-content" style="display: block;">

                    <div class="main-card mb-3 card ">
                        <div class="card-header bg-secondary"></div>
                        <div class="card-body">

                            <div class="form-row mt-3">
                                <div class="form-group col-lg-12 col-md-12">
                                    <label>Documento link 1</label>
                                    <input class="form-control" placeholder="" id="doc_link1" name="doc_link1" type="text" value="{{ (isset($reparacionOnsite) ? $reparacionOnsite->doc_link1 : '' ) }}" autocomplete="off">
                                    @if(isset($reparacionOnsite))
                                    <a href="{{ $reparacionOnsite->doc_link1 }}">{{ $reparacionOnsite->doc_link1 }}</a>
                                    @endif
                                </div>
                                <div class="form-group col-lg-12 col-md-12">
                                    <label>Documento link 2</label>
                                    <input class="form-control" placeholder="" name="doc_link2" id="doc_link2" type="text" value="{{ (isset($reparacionOnsite) ? $reparacionOnsite->doc_link2 : '' ) }}" autocomplete="off">
                                    @if(isset($reparacionOnsite))
                                    <a href="{{ $reparacionOnsite->doc_link2 }}">{{ $reparacionOnsite->doc_link2 }}</a>
                                    @endif
                                </div>
                                <div class="form-group col-lg-12 col-md-12">
                                    <label>Documento link 3</label>
                                    <input class="form-control" placeholder="" id="doc_link3" name="doc_link3" type="text" value="{{ (isset($reparacionOnsite) ? $reparacionOnsite->doc_link3 : '' ) }}" autocomplete="off">
                                    @if(isset($reparacionOnsite))
                                    <a href="{{ $reparacionOnsite->doc_link3 }}">{{ $reparacionOnsite->doc_link3 }}</a>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>




                <!-- Confirma Formulario -->
                <div id="step-4" class="tab-pane step-content" style="display: none;">
                    <div class="no-results">
                        <div class="swal2-icon swal2-success swal2-animate-success-icon">
                            <div class="swal2-success-circular-line-left" style="background-color: rgb(255, 255, 255);"></div>
                            <span class="swal2-success-line-tip"></span>
                            <span class="swal2-success-line-long"></span>
                            <div class="swal2-success-ring"></div>
                            <div class="swal2-success-fix" style="background-color: rgb(255, 255, 255);"></div>
                            <div class="swal2-success-circular-line-right" style="background-color: rgb(255, 255, 255);"></div>
                        </div>

                        <div class="swal2-icon swal2-error swal2-animate-error-icon" style="display: flex;" hidden>
                            <span class="swal2-x-mark">
                                <span class="swal2-x-mark-line-left"></span>
                                <span class="swal2-x-mark-line-right"></span>
                            </span>
                        </div>

                        <!-- <div class="results-subtitle mt-4">Completo!</div> -->
                        <div class="results-subtitle mt-4 resumen_form"></div>

                        <!-- <div class="results-title">Click para enviar</div> -->
                        <div class="mt-3 mb-3"></div>
                        <div class="text-center">
                            <button class="btn-shadow btn-wide btn btn-success btn-lg" id="boton_enviar">Enviar</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="divider"></div>
        <div class="card-body clearfix">
            <button type="button" id="reset-btn" class="btn-shadow float-left btn btn-link">Reset</button>
            <button type="button" id="next-btn" class="btn-shadow btn-wide float-right btn-pill btn-hover-shine btn btn-primary">Siguiente</button>
            <button type="button" id="prev-btn" class="btn-shadow float-right btn-wide btn-pill mr-3 btn btn-outline-secondary">Anterior</button>


        </div>
    </div>
</div>