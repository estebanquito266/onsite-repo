<div class="main-card mb-3 card ">
    <div class="card-header bg-secondary text-white">Empresa Onsite</div>
    <div class="card-body">
        <div class="form-group col-lg-12 col-md-12">
            <select name="id_empresa_onsite" id="id_empresa_onsite" class="form-control multiselect-dropdown" placeholder='Seleccione empresa onsite id'>
                <option value=""> -- Seleccione uno --</option>
                @foreach ($empresasOnsite as $empresaOnsite)
                <option value="{{ $empresaOnsite->id }}" {{ ((isset($reparacionOnsite) && $reparacionOnsite->id_empresa_onsite == $empresaOnsite->id)?'selected':'') }}>[{{ $empresaOnsite->id }}] {{ $empresaOnsite->nombre }}</option>
                @endforeach
            </select>
        </div>
    </div>
</div>

<div class="main-card mb-3 card ">
    <div class="card-header bg-secondary">
    </div>
    <div class="card-body">

        <div class="form-row mt-3">
            <div class="form-group col-lg-6 col-md-6">
                <label for="clave">Clave</label>
                @if(Request::segment(2)=='create' )
                <input type="text" class="form-control" placeholder="Ingrese clave" id="clave" name="clave">
                @else
                <input type="text" class="form-control" placeholder="Ingrese clave" id="clave" name="clave" readonly value="{{ $reparacionOnsite->clave }}">
                @endif
            </div>

            <div class="form-group col-lg-6 col-md-6 ">
                <label for="fecha_ingreso">Fecha de Ingreso</label>
                <input type="text" class="form-control" placeholder="Ingrese fecha de ingreso" name="fecha_ingreso" readonly value="{{ isset($reparacionOnsite) && !empty($reparacionOnsite->fecha_ingreso) ? $reparacionOnsite->fecha_ingreso : '' }}">
            </div>

            <div class="form-group col-lg-6 col-md-6 ">
                <label>Nivel Interior</label>
                @if(Request::segment(2) != 'create' )
                <input class="form-control" placeholder="Nivel interior" readonly="true" name="nivel_interior" type="text" value="{{ ($nivelInterior?($nivelInterior->id.' - '.$nivelInterior->nombre):'--') }}">
                @else
                <input class="form-control" placeholder="Nivel interior" readonly="true" name="nivel_interior" type="text">
                @endif
            </div>

            <div class="form-group col-lg-6 col-md-6">
                <label>Técnico Onsite Asignado</label>
                <select name="id_tecnico_asignado" id="id_tecnico_asignado" class="form-control multiselect-dropdown" readonly>
                    <option selected="selected" value="">Seleccione el técnico</option>
                    @foreach ($tecnicosOnsite as $id => $nombre)
                    <option value="{{ $id }}" {{ isset($reparacionOnsite) && $reparacionOnsite->id_tecnico_asignado == $id ? 'selected' : '' }}>{{ $nombre }}</option>
                    @endforeach
                </select>
            </div>


            <div class="form-group col-md-6">
                <label>Buscar Sucursal Onsite</label>
                <div class="form-group input-group ">
                    <input type="text" class="form-control mr-2" placeholder="Ingrese código o razón social de la sucursal" id="textoBuscarSucursal" name="textoBuscarSucursal">
                    <span class="input-group-btn">
                        <button class="btn btn-primary mr-2" type="button" id="buscarSucursalReparacion"><i class="fa fa-search"></i></button>
                        <button class="btn btn-success" type="button" id="createSucursal" data-toggle='modal' data-target='#modalSucursales'><i class="fa fa-plus"></i></button>
                    </span>
                </div>
            </div>

            <div class="form-group col-md-6">
                <label>Seleccionar Sucursal</label>
                <div class="form-group input-group ">
                    <div class="col-md-11">
                        <select name="sucursal_onsite_id" id="sucursal_onsite_id" class=" multiselect-dropdown form-control mr-2" placeholder='Seleccione sucursal onsite id'>
                            <option value=""> -- Seleccione uno --</option>
                            @foreach ($sucursalesOnsite as $sucursalOnsite)
                            <option value="{{ $sucursalOnsite->id }}" {{ ((isset($reparacionOnsite) && $reparacionOnsite->sucursal_onsite_id == $sucursalOnsite->id)?'selected':'') }}>{{ $sucursalOnsite->razon_social.' ['.$sucursalOnsite->codigo_sucursal.'] ' }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-1">
                        <span class="input-group-btn">
                            @if(Request::segment(2)=='create')
                            <button class="btn btn-warning " type="button" id="editSucursal" data-toggle='modal' data-target='#modalSucursales' disabled><i class="fa fa-paint-brush"></i></button>
                            @else
                            <button class="btn btn-warning" type="button" id="editSucursal" data-toggle='modal' data-target='#modalSucursales'><i class="fa fa-paint-brush"></i></button>
                            @endif
                        </span>
                    </div>
                </div>
            </div>

            <div class="form-group col-lg-12 col-md-12">
                <label>Terminal Onsite Id</label>
                <div class="form-group input-group ">
                    <div class="col-md-9">
                        <select name="id_terminal" id="id_terminal" class="form-control mr-1 multiselect-dropdown" placeholder='Seleccione terminal onsite id'>
                            <option value=""> -- Seleccione uno --</option>
                            @foreach ($terminalesOnsite as $terminalOnsite)
                            <option value="{{ $terminalOnsite->nro }}" {{ ((isset($reparacionOnsite) && $reparacionOnsite->id_terminal == $terminalOnsite->nro)?'selected':'') }}>{{ $terminalOnsite->nro.' - '. $terminalOnsite->marca . ' - '.$terminalOnsite->modelo }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-3">
                        <span class="input-group-btn">
                            <button class="btn btn-warning " type="button" id="refreshTerminal"><i class="fa fa-recycle"></i></button>
                            <button class="btn btn-success" type="button" id="createTerminal" data-toggle='modal' data-target='#modalTerminales'><i class="fa fa-plus"></i></button>

                            @if(Request::segment(2)=='create')
                            <button class="btn btn-warning " type="button" id="editTerminal" data-toggle='modal' data-target='#modalTerminales' disabled><i class="fa fa-paint-brush"></i></button>
                            @else
                            <button class="btn btn-warning" type="button" id="editTerminal" data-toggle='modal' data-target='#modalTerminales'><i class="fa fa-paint-brush"></i></button>
                            @endif
                        </span>
                    </div>
                </div>
            </div>




            <div class="form-group col-lg-12 col-md-12">
                <label for="tarea">Tarea</label>
                <input type="text" class="form-control mr-2" placeholder="Ingrese tarea" id="tarea" name="tarea" value="{{ isset($reparacionOnsite) ? $reparacionOnsite->tarea : '' }}">
            </div>
            <div class="form-group col-lg-12 col-md-12">
                <label>Detalle de Tarea</label>
                <textarea class="form-control" placeholder="Ingrese detalle de tarea" name="tarea_detalle" cols="50" rows="10">{{ isset($reparacionOnsite) ? $reparacionOnsite->tarea_detalle : '' }}</textarea>
            </div>

            <div class="form-group  col-lg-6 col-md-6">
                <label for="id_tipo_servicio">Tipo de Servicio</label>
                <select name="id_tipo_servicio" id="id_tipo_servicio" class="form-control multiselect-dropdown">
                    <option selected="selected" value="">Seleccione tipo de servicio</option>
                    @foreach ($tiposServicios as $id => $nombre)
                    <option value="{{ $id }}" {{ ((isset($reparacionOnsite) && $reparacionOnsite->id_tipo_servicio == $id) ? 'selected' : '') }}>{{ $nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-lg-6 col-md-6">
                <label for="id_estado">Estado</label>
                <select name="id_estado" id="id_estado" class="form-control multiselect-dropdown">
                    <option selected="selected" value="">Seleccione estado</option>
                    @foreach ($estadosOnsite as $estadoOnsite)
                    <option value="{{ $estadoOnsite->id }}" {{ ((isset($reparacionOnsite) && $reparacionOnsite->id_estado == $estadoOnsite->id) ? 'selected' : '') }}>{{ $estadoOnsite->nombre }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group col-lg-6 col-md-6 ">
                <label>Fecha Vencimiento</label>
                <div class="input-group date" id="fecha_vencimiento" data-target-input="nearest">

                    <div class="input-group-append" data-target="#fecha_vencimiento" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                    <input type="text" class="form-control datetimepicker-input" data-target="#fecha_vencimiento" placeholder="Ingrese fecha y hora de vencimiento " name="fecha_vencimiento" value="{{ isset($reparacionOnsite) && !empty($reparacionOnsite->fecha_vencimiento) ? $reparacionOnsite->fecha_vencimiento : '' }}">
                </div>
            </div>


            <div class="form-group  col-lg-6 col-md-6">
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
</div>

<div class="main-card mb-3 card ">
    <div class="card-header bg-secondary"></div>
    <div class="card-body">

        <div class="form-row mt-3">
            <div class="form-group col-lg-12 col-md-12">
                <label>Documento link 1</label>
                <input class="form-control" placeholder="" name="doc_link1" type="text" value="{{ (isset($reparacionOnsite) ? $reparacionOnsite->doc_link1 : '' ) }}">
                @if(isset($reparacionOnsite))
                <a href="{{ $reparacionOnsite->doc_link1 }}">{{ $reparacionOnsite->doc_link1 }}</a>
                @endif
            </div>
            <div class="form-group col-lg-12 col-md-12">
                <label>Documento link 2</label>
                <input class="form-control" placeholder="" name="doc_link2" type="text" value="{{ (isset($reparacionOnsite) ? $reparacionOnsite->doc_link2 : '' ) }}">
                @if(isset($reparacionOnsite))
                <a href="{{ $reparacionOnsite->doc_link2 }}">{{ $reparacionOnsite->doc_link2 }}</a>
                @endif
            </div>
            <div class="form-group col-lg-12 col-md-12">
                <label>Documento link 3</label>
                <input class="form-control" placeholder="" name="doc_link3" type="text" value="{{ (isset($reparacionOnsite) ? $reparacionOnsite->doc_link3 : '' ) }}">
                @if(isset($reparacionOnsite))
                <a href="{{ $reparacionOnsite->doc_link3 }}">{{ $reparacionOnsite->doc_link3 }}</a>
                @endif
            </div>
        </div>
    </div>
</div>