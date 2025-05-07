<div class="main-card mb-3 card ">
    <div class="card-header bg-secondary text-light">Datos Imagen Obra Onsite</div>
    <div class="card-body row">

    </div>
    <div class="card-body row">
        <input type="hidden" name="obra_onsite_id" value="{{ $obra_onsite_id ?? '' }}">

        @if(isset($imagen))
        <div class="form-group col-6">
            <img src="{{asset('/imagenes/reparaciones_onsite/'.$imagen->archivo)}}" width="20%">
            <a href="{{ asset('/imagenes/reparaciones_onsite/'.$imagen->archivo)}}" download="">Descargar</a>
        </div>
        @else
        <div class="col-6">
            <div class="font-icon-wrapper">
                <i class="fa fa-fw" aria-hidden="true" title="Copy to use cogs"></i>
                <p>Esperando imágenes...</p>
            </div>
        </div>
        @endif


        <div class="form-group col-6">
            <label>Tipo de Imagen Onsite</label>
            <select name="tipo_imagen_onsite_id" id="tipo_imagen_onsite_id" class="form-control" placeholder="Ingrese el tipo de imagen">
                @foreach($tipos_imagenes as $tipo)
                <option value="{{$tipo->id}}" {{ ((isset($imagen) && $tipo->id == $imagen->tipo_imagen_onsite_id) ? "selected" : null) }}>
                    {{$tipo->nombre}}
                </option>
                @endforeach
            </select>
        </div>
        <div class="form-group col-12">
            <label>Archivo</label>
            <input type="file" multiple name="archivos[]" id="archivos" class="form-control" placeholder="Ingrese el archivo" value="{{ (isset($imagen)) ? $imagen->archivo : null }}">
        </div>

        <div class="col-xs-12 col-12">
            <label for="descripcion">Descripción</label>
            <textarea class="form-control" name="descripcion" id="descripcion" cols="30" rows="5"> {{$imagen->descripcion ?? ''}} </textarea>
        </div>
    </div>
</div>