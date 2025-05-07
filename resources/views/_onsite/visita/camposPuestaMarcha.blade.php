<div class="main-card mb-3 card">
  <div class="card-header bg-alternate text-white">Visita Puesta en Marcha
    @if(isset($reparacionOnsite) && $reparacionOnsite->reparacion_onsite_puesta_marcha_id)
    - Visita de Seguimiento: &nbsp; <a href="/reparacionOnsite/{{ $reparacionOnsite->reparacion_onsite_puesta_marcha_id }}/edit" class="text-white"> {{ $reparacionOnsite->reparacion_onsite_puesta_marcha_id }}</a>
    @endif
  </div>
  <div class="card-body">
    <div class="form-group col-lg-12 col-md-12">
      @if(isset($reparacionesChecklistOnsite))
      <input type="hidden" name="reparacion_onsite_id" id="reparacion_onsite_id" value="{{ $reparacionesChecklistOnsite->reparacion_onsite_id }}" />
      @endif
      <div class="form-group ">
        <label for="alimentacion_definitiva">Alimentación Definitiva</label>
        <input type="checkbox" id="alimentacion_definitiva" name="alimentacion_definitiva" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="primary" data-offstyle="secondary" {{ (isset($reparacionesChecklistOnsite) && $reparacionesChecklistOnsite->alimentacion_definitiva) ? 'checked' : '' }}>
      </div>
      <div class="form-group ">
        <label for="unidades_tension_definitiva">Unidades Tensión Definitiva</label>
        <input type="checkbox" id="unidades_tension_definitiva" name="unidades_tension_definitiva" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="primary" data-offstyle="secondary" {{ (isset($reparacionesChecklistOnsite) && $reparacionesChecklistOnsite->unidades_tension_definitiva) ? 'checked' : '' }}>
      </div>
      <div class="form-group ">
        <label for="cable_alimentacion_comunicacion_seccion_ok">Cable Alimentación Comunicación Sección</label>
        <input type="checkbox" id="cable_alimentacion_comunicacion_seccion_ok" name="cable_alimentacion_comunicacion_seccion_ok" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="primary" data-offstyle="secondary" {{ (isset($reparacionesChecklistOnsite) && $reparacionesChecklistOnsite->cable_alimentacion_comunicacion_seccion_ok) ? 'checked' : '' }}>
      </div>
      <div class="form-group ">
        <label for="minimo_conexiones_frigorificas_exteriores">Mínimo Conexiones Frigoríficas Exteriores</label>
        <input type="checkbox" id="minimo_conexiones_frigorificas_exteriores" name="minimo_conexiones_frigorificas_exteriores" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="primary" data-offstyle="secondary" {{ (isset($reparacionesChecklistOnsite) && $reparacionesChecklistOnsite->minimo_conexiones_frigorificas_exteriores) ? 'checked' : '' }}>
      </div>
      <div class="form-group ">
        <label for="sistema_presurizado_41_5_kg">Sistema Presurizado 41,5 Kgs</label>
        <input type="checkbox" id="sistema_presurizado_41_5_kg" name="sistema_presurizado_41_5_kg" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="primary" data-offstyle="secondary" {{ (isset($reparacionesChecklistOnsite) && $reparacionesChecklistOnsite->sistema_presurizado_41_5_kg) ? 'checked' : '' }}>
      </div>
      <div class="form-group">
        <label for="sistema_presurizado_41_5_kg_tiempo_horas">Sistema Presurizado 41,5 Kgs Tiempo Horas</label>
        @if(isset($reparacionesChecklistOnsite))
        <input type="number" step="any" class="form-control" id="sistema_presurizado_41_5_kg_tiempo_horas" name="sistema_presurizado_41_5_kg_tiempo_horas" value="{{$reparacionesChecklistOnsite->sistema_presurizado_41_5_kg_tiempo_horas}}" />
        @else
        <input type="number" step="any" class="form-control" id="sistema_presurizado_41_5_kg_tiempo_horas" name="sistema_presurizado_41_5_kg_tiempo_horas" />
        @endif
      </div>
      <div class="form-group ">
        <label for="operacion_vacio">Operación Vacío</label>
        <input type="checkbox" id="operacion_vacio" name="operacion_vacio" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="primary" data-offstyle="secondary" {{ (isset($reparacionesChecklistOnsite) && $reparacionesChecklistOnsite->operacion_vacio) ? 'checked' : '' }}>
      </div>
      <div class="form-group ">
        <label for="unidad_exterior_tension_12_horas">Unidad Exterior Tensión 12 Horas</label>
        @if(isset($reparacionesChecklistOnsite))
        <input type="number" step="00.01" class="form-control" id="unidad_exterior_tension_12_horas" name="unidad_exterior_tension_12_horas" value="{{$reparacionesChecklistOnsite->unidad_exterior_tension_12_horas}}" />
        @else
        <input type="number" step="00.01" class="form-control" id="unidad_exterior_tension_12_horas" name="unidad_exterior_tension_12_horas" />
        @endif
      </div>
      <div class="form-group ">
        <label for="llave_servicio_odu_abiertos">Llave Servicio Odu Abiertos</label>
        <input type="checkbox" id="llave_servicio_odu_abiertos" name="llave_servicio_odu_abiertos" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="primary" data-offstyle="secondary" {{ (isset($reparacionesChecklistOnsite) && $reparacionesChecklistOnsite->llave_servicio_odu_abiertos) ? 'checked' : '' }}>
      </div>
      <div class="form-group ">
        <label for="carga_adicional_introducida">Carga Adicional Introducida</label>
        <input type="checkbox" id="carga_adicional_introducida" name="carga_adicional_introducida" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="primary" data-offstyle="secondary" {{ (isset($reparacionesChecklistOnsite) && $reparacionesChecklistOnsite->carga_adicional_introducida) ? 'checked' : '' }}>
      </div>
      <div class="form-group ">
        <label for="sistema_funcionando_15_min_carga_adicional">Sistema Funcionando 15 minutos Carga Adicional</label>
        <input type="checkbox" id="sistema_funcionando_15_min_carga_adicional" name="sistema_funcionando_15_min_carga_adicional" data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="primary" data-offstyle="secondary" {{ (isset($reparacionesChecklistOnsite) && $reparacionesChecklistOnsite->sistema_funcionando_15_min_carga_adicional) ? 'checked' : '' }}>
      </div>
      <div class="form-group ">
        <label for="carga_adicional_introducida_kg_adicional">Carga Adicional Introducida Kg Adicional</label>
        @if(isset($reparacionesChecklistOnsite))
        <input type="number" step="00.01" class="form-control" id="carga_adicional_introducida_kg_adicional" name="carga_adicional_introducida_kg_adicional" value="{{$reparacionesChecklistOnsite->carga_adicional_introducida_kg_adicional}}" />
        @else
        <input type="number" step="00.01" class="form-control" id="carga_adicional_introducida_kg_adicional" name="carga_adicional_introducida_kg_adicional" />
        @endif
      </div>
      <div class="form-group ">
        <label for="carga_adicional_introducida_kg_final">Carga Adicional Introducida Kg Final</label>
        @if(isset($reparacionesChecklistOnsite))
        <input type="number" step="00.01" class="form-control" id="carga_adicional_introducida_kg_final" name="carga_adicional_introducida_kg_final" value="{{$reparacionesChecklistOnsite->carga_adicional_introducida_kg_final}}" />
        @else
        <input type="number" step="00.01" class="form-control" id="carga_adicional_introducida_kg_final" name="carga_adicional_introducida_kg_final" />
        @endif
      </div>



      <div class="form-group ">
        <label for="puesta_marcha_satisfactoria">Resultado de Visita de Puesta en Marcha</label>

        <select name="puesta_marcha_satisfactoria" id="puesta_marcha_satisfactoria" class="form-control mr-1 multiselect-dropdown" placeholder='Seleccione opción'>
          <option value=""> -- Seleccione uno --</option>
          @foreach ($puestaMarchaSatisfactoriaEnumOptions as $id => $text)
            <option value="{{ $id }}" {{ ($reparacionesChecklistOnsite && $reparacionesChecklistOnsite->puesta_marcha_satisfactoria == $id) ? 'selected' : '' }}>{{ $text }}</option>
          @endforeach
        </select>
      </div>
    </div>
  </div>
</div>