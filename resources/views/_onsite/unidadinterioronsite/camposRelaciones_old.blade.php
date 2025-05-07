<div class="main-card mb-3 card">
    <div class="card-header bg-secondary text-white">Sistema Onsite</div>
    <div class="card-body row">
        <div class="form-group col-lg-12 col-md-12">
            
            <label>Obra</label>
            <select name="empresa_onsite_id" id="empresa_onsite_id" class="form-control multiselect-dropdown " placeholder='Seleccione obra'>
                <option value=""> -- Seleccione uno --</option>
                @foreach ($empresasOnsite as $empresa)
                    <option value="{{ $empresa->id }}" {{ ((isset($unidadInterior) && $empresa->id == $unidadInterior->empresa_onsite_id)?'selected':'') }}>{{ $empresa->nombre }}</option>
                @endforeach
            </select>
        </div>             
                
        <div class="form-group col-lg-12 col-md-12">
            <label>Sucursal Onsite</label>
            <div class="form-group input-group ">
                <select name="sucursal_onsite_id" id="sucursal_onsite_id" class=" multiselect-dropdown form-control mr-1" placeholder='Seleccione sucursal onsite id'>
                    <option value=""> -- Seleccione uno --</option>   
                    @if(isset($unidadInterior))                             
                    <option value="{{$unidadInterior->sucursal_onsite_id}}" selected="">{{$unidadInterior->sucursal_onsite->razon_social}}</option>
                    @endif                             
                </select>
                <span class="input-group-btn">
                    <button class="btn btn-warning " type="button" id="refreshSucursal"><i class="fa fa-reply-all"></i></button>
                </span>
            </div>
        </div>

        <div class="form-group col-lg-12 col-md-12">
            <label>Sistema Onsite</label>
            <div class="form-group input-group ">
                <select name="sistema_onsite_id" id="sistema_onsite_id" class=" multiselect-dropdown form-control mr-1" placeholder='Seleccione sucursal onsite id'>
                    <option value=""> -- Seleccione uno --</option>   
                    @if(isset($unidadInterior))                             
                    <option value="{{$unidadInterior->sistema_onsite_id}}" selected="">{{$unidadInterior->sistema_onsite->nombre}}</option>
                    @endif
                </select>
                <span class="input-group-btn">
                    <button class="btn btn-warning " type="button" id="refresSistemas"><i class="fa fa-reply-all"></i></button>
                </span>
            </div>
        </div>
    </div>
</div>