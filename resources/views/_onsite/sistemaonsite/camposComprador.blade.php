<div class="main-card mb-3 card ">
    <div class="card-header bg-secondary text-light">Datos Comprador</div>
    <!-- comprador ############### -->

    <div class="form-row mt-3 card-body">

        <div class="form-group  col-md-4 col-12">
            <label>DNI</label>
            <input type="number" step="1" name='dni' id="dni" class='form-control form_comprador' placeholder='Ingrese dni' value="{{ old('dni' ,(isset($garantiaOnsite)?$garantiaOnsite->comprador_onsite->dni:null)) }}">
            <input type="hidden" id="sistema_onsite_id_comprador">
        </div>

        <div class="form-group  col-md-4 col-12">
            <label>Nombre</label>
            <input type="text" name='primer_nombre' id="primer_nombre" class='form-control form_comprador' placeholder='Ingrese primer_nombre' value="{{ old('primer_nombre', isset($garantiaOnsite)?$garantiaOnsite->comprador_onsite->primer_nombre:null) }}">
        </div>

        <div class="form-group  col-md-4 col-12">
            <label>Apellido</label>
            <input type="text" name='apellido' id="apellido" class='form-control form_comprador' placeholder='Ingrese apellido' value="{{old('apellido', isset($garantiaOnsite)?$garantiaOnsite->comprador_onsite->apellido:null) }}">
            <input type="hidden" name='nombre' id="nombre_comprador" class='form-control form_comprador' value="{{ (isset($obraOnsite)?$obraOnsite->nombre:null) }}">
        </div>

        <div class="form-group col-12 col-md-6">
            <label>email</label>
            <input type="text" name='email' id="email" class='form-control form_comprador' placeholder='Ingrese email' value="{{old('email', isset($garantiaOnsite) ? $garantiaOnsite->comprador_onsite->email: null) }}">
        </div>

        <div class="form-group col-12 col-md-6">
            <label>celular</label>
            <input type="text" name='celular' id="celular" class='form-control form_comprador' placeholder='Ingrese celular' value="{{old('celular', isset($garantiaOnsite) ? $garantiaOnsite->comprador_onsite->celular: null)}}">
        </div>


    </div>

</div>