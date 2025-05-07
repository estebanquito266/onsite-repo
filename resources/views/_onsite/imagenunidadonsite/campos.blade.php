
<div class="main-card mb-3 card ">
    <div class="card-header bg-secondary text-light">Datos Imagen Unidad {{$tipo_unidad}}</div>
    <div class="card-body row">            
        <input type="hidden" name='tipo_unidad' id="tipo_unidad" class='form-control' value="{{$tipo_unidad}}">
        <input type="hidden" name='company_id' id="company_id" class='form-control' value="{{$company}}">
        <input type="hidden" name="unidad_{{$tipo_unidad}}_onsite_id" id="unidad_{{$tipo_unidad}}_onsite_id" class='form-control' value="{{$unidadOnsite}}">
    </div>
    <div class="card-body row">
        @if(isset($imagen))
        <div class="form-group col-lg-3 col-md-3">
            <img src="{{asset('/imagenes/unidades_'.$tipo_unidad.'es/'.$imagen->archivo)}}" width="100%">
            <a href="{{ asset('/imagenes/unidades_'.$tipo_unidad.'es/'.$imagen->archivo)}}" download="">Descargar</a>
        </div>
        
        @endif
        <div class="form-group col-lg-3 col-md-3">
            <label>Tipo de Imagen Onsite</label>
            <select  name='tipo_imagen_onsite_id' id="tipo_imagen_onsite_id" class='form-control' placeholder='Ingrese el tipo de imagen'>
                @foreach($tipos_imagenes as $tipo)
                <option value="{{$tipo->id}}" {{ ((isset($imagen) && $tipo->id == $imagen->tipo_imagen_onsite_id)?'selected':'') }}>{{$tipo->nombre}}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-lg-3 col-md-3">
            <label>Archivo</label>
            <input type="file" name='archivo' id="archivo" class='form-control' placeholder='Ingrese el archivo' value="{{ (isset($imagen)) ? $imagen->archivo : '' }}">
        </div>        
    </div>
</div>




