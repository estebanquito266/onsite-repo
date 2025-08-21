@if( Request::segment(3) == 'edit' )
<div class="main-card mb-3 card ">
    <div class="card-header bg-secondary"></div>
    <div class="card-body">
        <div class="form-row mt-3">
            <div class="form-group col-lg-12 col-md-12">
                <label>Observación Ubicación</label>
                <input type="text" class="form-control" placeholder="Ingrese observación ubicación" name="observacion_ubicacion" value="{{ $reparacionOnsite->observacion_ubicacion }}">
            </div>

            <div class="form-group col-lg-6 col-md-6 ">
                <label>Fecha Coordinada</label>
                <div class="input-group">
                    <div class="input-group-prepend datepicker-trigger">
                        <div class="input-group-text">
                            <i class="fa fa-calendar-alt"></i>
                        </div>
                    </div>
                    <input type="text" class="form-control" data-toggle="datepicker-icon" id="fecha_coordinada" name="fecha_coordinada" value="{{ $reparacionOnsite->fecha_coordinada }}">
                </div>
            </div>

            <div class="form-group col-lg-6 col-md-6 ">
                <label>Fecha Cerrado</label>
                <div class="input-group date" id="fecha_cerrado" data-target-input="nearest">
                    <div class="input-group-append" data-target="#fecha_cerrado" data-toggle="datetimepicker">
                        <div class="input-group-text"><i class="fa fa-calendar"></i></div>
                    </div>
                    <input type="text" class="form-control datetimepicker-input" data-target="#fecha_cerrado" placeholder="Ingrese fecha y hora de cierre" name="fecha_cerrado" value="{{ $reparacionOnsite->fecha_cerrado }}">
                </div>
            </div>

            @if(Request::segment(2) != 'create')
            <div class="form-group col-lg-6 col-md-6 @if($reparacionOnsite->sla_status == 'IN') bg-success text-white @else bg-danger text-white @endif">
                <label>Sla Status</label>
                <label class="radio-inline">
                    <input type="radio" id="sla_status_in" name="sla_status" value="IN" {{ $reparacionOnsite->sla_status == "IN" ? "checked" : "" }}> IN
                </label>
                <label class="radio-inline">
                    <input id="sla_status_out" name="sla_status" type="radio" value="OUT" {{ $reparacionOnsite->sla_status == "OUT" ? "checked" : "" }}> OUT
                </label>
            </div>

            <div class="form-group col-lg-6 col-md-6 @if($reparacionOnsite->sla_status == 'IN') bg-success text-white @else bg-danger text-white @endif">
                <label for="sla_justificado">Sla Justificado</label>
                <input type="hidden" name="sla_justificado" value="0">
                <input type="checkbox" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="primary" data-offstyle="secondary" id="sla_justificado" name="sla_justificado" value="1" {{ (isset($reparacionOnsite) && $reparacionOnsite->sla_justificado) ? 'checked' : '' }}>
            </div>
            @endif
        </div>
    </div>
</div>
@endif

@if($companyId == \App\Models\Company::DEFAULT && (Request::segment(3) == 'edit' || Session::get('perfilAdmin')))
<div class="main-card mb-3 card ">
    <div class="card-header bg-secondary"></div>
    <div class="card-body">
        <div class="form-row mt-3">
            <div class="form-group col-lg-6 col-md-6 ">
                <label>Monto</label>
                <input type="text" class="form-control input-mask-trigger" value="{{ $reparacionOnsite->monto }}" data-inputmask="'alias': 'numeric', 'radixPoint': '.', 'groupSeparator': ' ', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '$ ', 'placeholder': '0', 'rightAlign': true, 'removeMaskOnSubmit': true" im-insert="true" id="monto" name="monto" style="text-align: right;">
            </div>

            <div class="form-group col-lg-6 col-md-6 ">
                <label>Monto Extra</label>
                <input type="text" class="form-control input-mask-trigger" value="{{ $reparacionOnsite->monto_extra }}" data-inputmask="'alias': 'numeric', 'radixPoint': '.', 'groupSeparator': ' ', 'autoGroup': true, 'digits': 2, 'digitsOptional': false, 'prefix': '$ ', 'placeholder': '0', 'rightAlign': true, 'removeMaskOnSubmit': true" im-insert="true" id="monto_extra" name="monto_extra" style="text-align: right;">
            </div>

            <div class="form-group col-lg-6 col-md-6 ">
                <label>Nro Factura de Proveedor</label>
                <input type="text" class="form-control" placeholder="Ingrese nro factura proveedor" name="nro_factura_proveedor" value="{{ $reparacionOnsite->nro_factura_proveedor }}">
            </div>

            <div class="form-group col-lg-6 col-md-6 pt-4">
                <label for="liquidado_proveedor">Liquidado por el Proveedor</label>
                <input type="hidden" name="liquidado_proveedor" value="0">
                <input type="checkbox" id="liquidado_proveedor" name="liquidado_proveedor" value="1" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="primary" data-offstyle="secondary" {{ (isset($reparacionOnsite) && $reparacionOnsite->liquidado_proveedor) ? 'checked' : '' }}>
            </div>
        </div>
    </div>
</div>
@endif

@if(Request::segment(3) == 'edit' || Session::get('perfilAdmin'))
<div class="main-card mb-3 card ">
    <div class="card-header bg-primary"></div>
    <div class="card-body">
        <div class="form-row mt-3">
            <div class="form-group col-lg-12 col-md-12">
                <label>Informe Técnico</label>
                <input type="text" class="form-control" placeholder="Ingrese informe técnico" name="informe_tecnico" value="{{ (isset($reparacionOnsite)) ? $reparacionOnsite->informe_tecnico : '' }}">
            </div>
        </div>
        @if( isset($reparacionOnsite) && in_array($reparacionOnsite->id_tipo_servicio, [50,60]))
        &nbsp;
        @else
        <div class="form-group col-lg-6 col-md-6 pt-4">
            <label for="problema_resuelto">Problema resuelto</label>
            <input type="hidden" name="problema_resuelto" value="0">
            <input type="checkbox" id="problema_resuelto" name="problema_resuelto" value="1" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="primary" data-offstyle="secondary" {{ (isset($reparacionOnsite) && $reparacionOnsite->problema_resuelto) ? 'checked' : '' }}>
        </div>
        @endif

        @if( Session::get('perfilAdmin') || Session::get('perfilAdminOnsite') )
        <div class="form-row mt-3">
            <div class="form-group col-lg-12 col-md-12">
                <label>Observaciones Internas</label>
                <input type="text" class="form-control" placeholder="Ingrese observaciones internas" name="observaciones_internas" value="{{ (isset($reparacionOnsite)) ? $reparacionOnsite->observaciones_internas : '' }}">
            </div>
        </div>
        @endif
    </div>
</div>

<div class="main-card mb-3 card ">
    <div class="card-header bg-primary"></div>
    <div class="card-body">
        <div class="form-row mt-3">
            <div class="form-group col-lg-6 col-md-6 pt-4">
                <label for="visible_cliente">Visible para Empresa/Cliente</label>
                <input type="hidden" name="visible_cliente" value="0">
                <input type="checkbox" id="visible_cliente" name="visible_cliente" value="1" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="primary" data-offstyle="secondary" {{ (isset($reparacionOnsite) && $reparacionOnsite->visible_cliente) ? 'checked' : '' }}>
            </div>

            <div class="form-group col-lg-6 col-md-6 pt-4">
                <label for="baja">Eliminar</label>
                <input type="hidden" name="baja" value="0">
                <input type="checkbox" id="baja" name="baja" value="1" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="danger" data-offstyle="secondary" {{ (isset($reparacionOnsite) && $reparacionOnsite->baja) ? 'checked' : '' }}>
            </div>
        </div>
    </div>
</div>
@endif

<div id="companyActivo" style="display: block;">
    @include('_onsite.reparaciononsite.camposEditCompanyDefault')
    @include('_onsite.reparaciononsite.camposEditActivo')
</div>


@include('_onsite.reparaciononsite.imagenesOnsite')

@include('_onsite.reparaciononsite.camposVisitas')

<div class="main-card mb-3 card ">
    <div class="card-header bg-alternate">
    </div>
    <div class="card-body">
        <div class="form-row mt-3">
            <div class="form-group col-lg-12 col-md-12">
                <label>Observación </label>
                <input type="text" class="form-control" placeholder="Ingrese observación" name="observacion">
            </div>
        </div>
    </div>
</div>


<div class="main-card mb-3 card ">
    <div class="card-header bg-alternate">
    </div>
    <div class="card-body">
        <div class="form-row mt-3">
            <div class="form-group col-lg-12 col-md-12">
                <label>Log </label>
                <input type="text" class="form-control" placeholder="Ingrese log" name="log" value="{{ (isset($reparacionOnsite)) ? $reparacionOnsite->log : '' }}">
            </div>
        </div>
    </div>
</div>

<div class="main-card mb-3 card ">
    <div class="card-header bg-alternate">
    </div>
    <div class="card-body">
        <div class="form-row mt-3">
            <div class="form-group col-lg-12 col-md-12">
                <label>Justificación </label>
                <input type="text" class="form-control" placeholder="Ingrese justificación" name="justificacion" value="{{ (isset($reparacionOnsite)) ? $reparacionOnsite->justificacion : '' }}">
            </div>
        </div>
    </div>
</div>