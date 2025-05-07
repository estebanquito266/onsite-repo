<div class="main-card mb-3 card ">
    <div class="card-header bg-secondary text-light">Datos Unidad Exterior</div>
    <div class="card-body row">



        <div class="form-group col-12 col-md-3">
            <label>Modelo</label>
            <input type="hidden" name='clave' id="clave" class='form-control' value="{{ (isset($unidadExterior)) ? $unidadExterior->clave : '1' }}">
            <input type="text" name='modelo' id="modelo" class='form-control form_ue' placeholder='Ingrese modelo' value="{{ (isset($unidadExterior)) ? $unidadExterior->modelo : '' }}">
        </div>
        <div class="form-group col-12 col-md-3">
            <label>Serie</label>
            <input type="text" name='serie' id="serie" class='form-control form_ue' placeholder='Ingrese serie' value="{{ (isset($unidadExterior)) ? $unidadExterior->serie : '' }}">
        </div>
        <div class="form-group col-12 col-md-3">
            <label>Faja Garantía</label>
            <input type="text" name='faja_garantia' id="faja_garantia" class='form-control form_ue' placeholder='Ingrese serie' value="{{ (isset($unidadExterior)) ? $unidadExterior->serie : '' }}">
        </div>
        <div class="form-group col-12 col-md-3">
            <label>Dirección</label>
            <input type="text" name='direccion' id="direccion" class='form-control form_ue' placeholder='Ingrese serie' value="{{ (isset($unidadExterior)) ? $unidadExterior->serie : '' }}">
        </div>
    </div>
    <div class="card-body row medidas">

        <div class="col-4"> </div>
        <div class="form-group col-4 text-center">
            <label>Medida Figura 1 a</label>
            <input type="number" step="0.01" name='medida_figura_1_a' id="medida_figura_1_a" class='form-control form_ue' placeholder='Ingrese medida_figura_1_a' value="{{ (isset($unidadExterior)) ? $unidadExterior->medida_figura_1_a : '' }}">
        </div>
        <div class="col-4"> </div>

        <div class="form-group col-4 text-center">
            <label>Medida Figura 1 c</label>
            <input type="number" step="0.01" name='medida_figura_1_c' id="medida_figura_1_c" class='form-control form_ue' placeholder='Ingrese medida_figura_1_c' value="{{ (isset($unidadExterior)) ? $unidadExterior->medida_figura_1_c : '' }}">
        </div>

        <div class="col-4 text-center">
            <img src="https://app-test.speedup.com.ar/assets/img/unidades-externas/image4.png" alt="">
        </div>



        <div class="form-group col-4 text-center">
            <label>Medida Figura 1 b</label>
            <input type="number" step="0.01" name='medida_figura_1_b' id="medida_figura_1_b" class='form-control form_ue' placeholder='Ingrese medida_figura_1_b' value="{{ (isset($unidadExterior)) ? $unidadExterior->medida_figura_1_b : '' }}">
        </div>
        <div class="col-4"> </div>
        <div class="form-group col-4 text-center">
            <label>Medida Figura 1 d</label>
            <input type="number" step="0.01" name='medida_figura_1_d' id="medida_figura_1_d" class='form-control form_ue' placeholder='Ingrese medida_figura_1_d' value="{{ (isset($unidadExterior)) ? $unidadExterior->medida_figura_1_d : '' }}">
        </div>
        <div class="col-4"> </div>
    </div>

    <div class="card-body row">
        <div class="col-4"> </div>

        <div class="form-group col-4 text-center">
            <label>Medida Figura 2 a</label>
            <input type="number" step="0.01" name='medida_figura_2_a' id="medida_figura_2_a" class='form-control form_ue' placeholder='Ingrese medida_figura_2_a' value="{{ (isset($unidadExterior)) ? $unidadExterior->medida_figura_2_a : '' }}">
        </div>
        <div class="col-4"> </div>



        <div class="form-group col-4 text-center">
            <label>Medida Figura 2 c</label>
            <input type="number" step="0.01" name='medida_figura_2_c' id="medida_figura_2_c" class='form-control form_ue' placeholder='Ingrese medida_figura_2_c' value="{{ (isset($unidadExterior)) ? $unidadExterior->medida_figura_2_c : '' }}">
        </div>
        <div class="col-4 text-center">
            <img src="https://app-test.speedup.com.ar/assets/img/unidades-externas/image5.png" alt="">
        </div>
        <div class="form-group col-4 text-center">
            <label>Medida Figura 2 b</label>
            <input type="number" step="0.01" name='medida_figura_2_b' id="medida_figura_2_b" class='form-control form_ue' placeholder='Ingrese medida_figura_2_b' value="{{ (isset($unidadExterior)) ? $unidadExterior->medida_figura_2_b : '' }}">
        </div>
<hr>


    </div>
    <div class="card-body row">
        <div class="form-group col-lg-4 col-md-4">
            <label>Anclaje Piso</label>
            <input type="checkbox" name='anclaje_piso' id="anclaje_piso" class='form-control form_ue' data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="primary" data-offstyle="secondary" {{ (( isset($unidadExterior) && isset($unidadExterior->anclaje_piso) && $unidadExterior->anclaje_piso) ? 'checked' : '') }}>
        </div>
        <div class="form-group col-lg-4 col-md-4">
            <label>Contra Sifon</label>
            <input type="checkbox" name='contra_sifon' id="contra_sifon" class='form-control form_ue' data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="primary" data-offstyle="secondary" {{ (( isset($unidadExterior) && isset($unidadExterior->contra_sifon) && $unidadExterior->contra_sifon) ? 'checked' : '') }}>
        </div>
        <div class="form-group col-lg-4 col-md-4">
            <label>500mm Última Derivación Curva</label>
            <input type="checkbox" name='mm_500_ultima_derivacion_curva' id="mm_500_ultima_derivacion_curva" class='form-control form_ue' data-toggle="toggle" data-on="SI" data-off="NO" data-onstyle="primary" data-offstyle="secondary" {{ (( isset($unidadExterior) && isset($unidadExterior->mm_500_ultima_derivacion_curva) && $unidadExterior->mm_500_ultima_derivacion_curva) ? 'checked' : '') }}>
        </div>
    </div>
    <div class="card-body row">
        <div class="form-group col-lg-12 col-md-12">
            <label>Observaciones</label>
            <input type="text" name='observaciones' id="observaciones" class='form-control form_ue' placeholder='Ingrese observaciones' value="{{ (isset($unidadExterior)) ? $unidadExterior->observaciones : '' }}">
        </div>
    </div>
</div>