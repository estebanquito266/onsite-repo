<div class="collapse border mb-5 pl-3 pr-3 pb-3" id="filtro">
    <form action="{{ url('filtrarVisitas') }}" method="POST">
        {{ csrf_field() }}
        <div class="form-row mt-3">


            <div class="col-lg-6">
                <div class="form-group">
                    <label>Ingrese texto </label>
                    <input type="text" name="texto" class="form-control" placeholder="Ingrese el texto de su búsqueda" id="texto" value="{{ (isset($texto)) ? $texto : '' }}">
                </div>
            </div>

            <div class="col-lg-6">
                <div class="form-group">
                    <label>Estado Onsite</label>
                    <select name="id_estado" id="id_estado" class="form-control">
                        <option selected="selected" value="">Seleccione estado onsite</option>
                        @foreach ($estadosOnsite as $estadoOnsite)
                        <option value="{{ $estadoOnsite->id }}" {{ (isset($id_estado) && $id_estado == $estadoOnsite->id) ? 'selected' : '' }}>{{ $estadoOnsite->nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>



            <div class="col-6">
                <div class="form-group">
                    <label>Obras y Sistemas</label>
                    <div class="row">
                        <div class="col-12">
                            <select name="sistema_onsite_id" id="sistema_onsite_id" class="form-control multiselect-dropdown">
                                <option value="0"> -- Seleccione uno --</option>
                                @if(isset($obrasOnsite))
                                @foreach ($obrasOnsite as $obra)
                                <optgroup label="Obra: {{$obra->nombre}}">
                                    @foreach ($obra->sistema_onsite as $sistema)
                                    <option value="{{ $sistema->id }}" data-idobra="{{ $sistema->obra_onsite_id }}" data-nombre_sistema="{{$sistema->nombre}}" {{ (isset($sistema_onsite_id) && $sistema_onsite_id == $sistema->id) ? 'selected' : '' }}>{{$sistema->id}}-{{$sistema->nombre}} (Obra: {{$obra->nombre}})
                                    </option>
                                    @endforeach
                                </optgroup>
                                @endforeach
                                @endif
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label>TipoServicio Onsite</label>
                    <select name="id_tipo_servicio" id="id_tipo_servicio" class="form-control">
                        <option selected="selected" value="">Seleccione tiposervicio onsite</option>
                        @foreach ($tiposServicios as $id => $nombre)
                        <option value="{{ $id }}" {{ (isset($id_tipo_servicio) && $id_tipo_servicio == $id) ? 'selected' : '' }}>{{ $nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-6">
                <div class="form-group">
                    <label>Técnicos Onsite</label>
                    <select name="id_tecnico" id="id_tecnico" class="form-control">
                        <option selected="selected" value="">Seleccione técnico onsite</option>
                        @foreach ($tecnicosOnsite as $id => $nombre)
                        <option value="{{ $id }}" {{ (isset($id_tecnico) && $id_tecnico == $id) ? 'selected' : '' }}>{{ $nombre }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="col-12 row">
                <div class="form-group col-6">
                    <label>Fecha de Ingreso - Desde</label>

                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="fa fa-calendar-alt"></i>
                            </div>
                        </div>

                        <input type="text" name="fecha_ingreso_desde" class="form-control datepicker" value="{{ (isset($fecha_ingreso_desde)) ? $fecha_ingreso_desde : '' }}">
                    </div>
                </div>
                <div class="form-group col-6">
                    <label>Fecha de Ingreso - Hasta</label>

                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="fa fa-calendar-alt"></i>
                            </div>
                        </div>

                        <input type="text" name="fecha_ingreso_hasta" class="form-control datepicker" value="{{ (isset($fecha_ingreso_hasta)) ? $fecha_ingreso_hasta : '' }}">
                    </div>
                </div>
            </div>


            <div class="col-12 row">
                <div class="form-group col-6">
                    <label>Fecha de Vencimiento - Desde</label>

                    <div class="input-group ">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="fa fa-calendar-alt datepicker-icon"></i>
                            </div>
                        </div>

                        <input type="text" name="fecha_vencimiento_desde" class="form-control datepicker" value="{{ (isset($fecha_vencimiento_desde)) ? $fecha_vencimiento_desde : '' }}">
                    </div>
                </div>
                <div class="form-group col-6">
                    <label>Fecha de Vencimiento - Hasta</label>

                    <div class="input-group">
                        <div class="input-group-prepend">
                            <div class="input-group-text">
                                <i class="fa fa-calendar-alt"></i>
                            </div>
                        </div>

                        <input type="text" name="fecha_vencimiento_hasta" class="form-control datepicker" value="{{ (isset($fecha_vencimiento_hasta)) ? $fecha_vencimiento_hasta : '' }}">
                    </div>
                </div>

            </div>


            <div class="col-12">
                <input type="hidden" name="obra_onsite_id" id="obra_onsite_id" value="{{old ('obra_onsite_id', isset($garantiaOnsite)?$garantiaOnsite->obra_onsite_id:null) }}">
                <input type="hidden" name="sistema_nombre" id="sistema_nombre">
            </div>

        </div>
        <input type="hidden" value="visitasOnsite" name="ruta" value="{{ (isset($ruta)) ? $ruta : '' }}">

        <div class="form-row mt-3">
            <div class=' col-lg-6'>
                <div class='form-group'>
                    <button type="submit" class="btn btn-primary btn-block btn-pill pull-right" name="boton_filtrar" value="filtrar">Filtrar</button>
                </div>
            </div>
            <div class=' col-lg-6'>
                <div class='form-group'>
                    <button type="submit" class="btn btn-success btn-block btn-pill pull-right" name="boton_filtrar" value="csv">Filtrar y Generar CSV</button>
                </div>
            </div>
        </div>

    </form>
</div>