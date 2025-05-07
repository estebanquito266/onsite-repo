<div class="main-card mb-3 card ">
    <div class="card-header bg-secondary text-light">Datos Unidad Interior</div>
    <div class="card-body row">
        
        <div class="form-group col-12 col-md-3">
            <label>Modelo</label>
            <input type="hidden" name='clave' id="clave_ui" class='form-control'  value="{{ (isset($unidadInterior)) ? $unidadInterior->clave : '1' }}">
            <input type="text" name='modelo' id="modelo_ui" class='form-control form_ui' placeholder='Ingrese modelo'  value="{{ (isset($unidadInterior)) ? $unidadInterior->modelo : '' }}">
        </div>
        <div class="form-group col-12 col-md-3">
            <label>Serie</label>
            <input type="text" name='serie' id="serie_ui" class='form-control form_ui' placeholder='Ingrese serie' value="{{ (isset($unidadInterior)) ? $unidadInterior->serie : '' }}">
        </div>        
        <div class="form-group col-12 col-md-3">
            <label>Dirección</label> 
            <input type="text" name='direccion' id="direccion_ui" class='form-control form_ui' placeholder='Ingrese dirección' value="{{ (isset($unidadInterior)) ? $unidadInterior->direccion : '' }}">
        </div>
        <div class="form-group col-12 col-md-3">
            <label>Faja Garantía</label>
            <input type="text" name='faja_garantia' id="faja_garantia_ui" class='form-control form_ui' placeholder='Ingrese serie' value="{{ (isset($unidadExterior)) ? $unidadExterior->serie : '' }}">
        </div>

        <div class="form-group  col-12   col-md-12">
            <label>Observaciones</label>
            <input type="text" name='observaciones' id="observaciones_ui" class='form-control form_ui' placeholder='Ingrese observaciones' value="{{ (isset($unidadInterior)) ? $unidadInterior->observaciones : '' }}">
        </div>
    </div>
</div>




