<div class="collapse border mb-5 pl-3 pr-3 pb-3" id="filtro">
    <form action="{{ url('filtrarReparacionOnsiteCerradas') }}" method="POST">
        {{ csrf_field() }}
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
                    <select name="id_empresa" id="id_empresa" class="form-control">
                        <option selected="selected" value="">Seleccione empresa onsite</option>
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
            <div class=' col-lg-4'>
                <div class='form-group'>
                    <label>Estado Onsite</label>
                    <select name="id_estado" id="id_estado" class="form-control">
                        <option selected="selected" value="">Seleccione estado onsite</option>
                        @foreach ($estadosOnsite as $estadoOnsite)
                        <option value="{{ $estadoOnsite->id }}" {{ (isset($id_estado) && $id_estado == $estadoOnsite->id) ? 'selected' : '' }}>{{ $estadoOnsite->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
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
                        <div class="input-group-prepend datepicker-trigger">
                            <div class="input-group-text">
                                <i class="fa fa-calendar-alt"></i>
                            </div>
                        </div>
                        <input type="text" name="fecha_vencimiento" class="form-control" data-toggle="datepicker-icon" id="fechavencimiento" value="{{ (isset($fecha_vencimiento)) ? $fecha_vencimiento : '' }}">
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

            <div class=' col-lg-4'>
                <div class="form-group ">
                    <label>Confirmadas</label>

                    <select id="select_confirmadas" name="select_confirmadas" class="form-control multiselect-dropdown">
                        <option value="1" {{ ($select_confirmadas === 1) ? 'selected' : null }}>Confirmadas</option>
                        <option value="0" {{ ($select_confirmadas === 0) ? 'selected' : null }}>Sin confirmar</option>
                        <option value="TODAS" {{ ($select_confirmadas === "TODAS") ? 'selected' : null }}>Todas</option>
                    </select>
                </div>
            </div>


            <input name="ruta" type="hidden" value="{{ (isset($ruta)) ? $ruta : 'reparacionOnsite' }}">

            <div class=' col-lg-6'>
                <div class='form-group'>
                    <button type="submit" class="btn btn-primary btn-block btn-pill pull-right" name="boton" value="filtrar">Filtrar</button>
                </div>
            </div>

        </div>
    </form>
</div>