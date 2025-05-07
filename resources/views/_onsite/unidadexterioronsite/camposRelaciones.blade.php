<div class="main-card mb-3 card">
    <div class="card-header bg-secondary text-white">Sistema Onsite</div>
    <div class="card-body row">

    
        <div class="form-group col-12 col-md-3">
            <!-- <label>Empresa Instaladora</label> -->
            <input  type="hidden" name='empresa_instaladora_nombre' id="empresa_instaladora_nombre" class='form-control' value="{{ ((count($user->empresa_instaladora) >0)?$user->empresa_instaladora[0]->nombre:null) }}">
            <input type="hidden" name="empresa_instaladora_id" id="empresa_instaladora_id" value="{{ ((count($user->empresa_instaladora) >0)?$user->empresa_instaladora[0]->id:null) }}">
        </div>

        <div class="col-12 col-md-9 form-group">
            <label>Sistemas</label>
            
            <input type="hidden" name="sistemas_onsite_id_edit" id="sistema_onsite_id_edit" value="{{isset($unidadExterior)? $unidadExterior->sistema_onsite_id : null}}">
            <input type="hidden" name="sistema_onsite_id" id="sistema_onsite_id" value="{{isset($unidadExterior)? $unidadExterior->sistema_onsite_id : null}}">
            <select disabled id="sistema_onsite_id_unidades" class="form-control multiselect-dropdown">
                <option value="{{isset($unidadExterior)? $unidadExterior->sistema_onsite_id : null}}"> {{ isset($unidadExterior) ? $unidadExterior->sistema_onsite->nombre : '-- Seleccione uno --'}}</option>

            </select>



        </div>


    </div>
</div>