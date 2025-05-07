<div class="main-card mb-3 card ">
    <div class="card-header bg-secondary text-light">
        <div class="text-left col-lg-6 col-md-6">Imágenes Unidad Interior</div>
        <div class="text-right col-lg-6 col-md-6">
            <a href="{!! URL::to('/unidadInterior/'.$unidadInterior->id.'/createImagen') !!}" data-toggle="tooltip" title="Crear Imagen Unidad Interior Onsite" data-placement="bottom" class="btn-shadow mr-3 btn btn-success"><i class="fa fa-plus"></i></a>
        </div>
    </div>
    <div class="card-body row">
        <table style="width: 100%;" class="table table-hover table-striped table-bordered ">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Previsualización</th>
                    <th>Tipo de Archivo</th>
                    <th>Descargar</th>
                </tr>
            </thead>
            <tbody class="small">
                @foreach($imagenes as $imagen)
                <tr>
                    <td><a href="{{ url('unidadInterior/' . $imagen->id . '/editImagen'  ) }}">{{$imagen->id}}</a></td>
                    <td class="text-center"><img src="{{ asset('/imagenes/unidades_interiores/'.$imagen->archivo) }}" width="60px" height="60px" style="border-radius: 100%"></td>
                    <td>{{$imagen->tipo_imagen->nombre}}</td>
                    <td><a href="{{ asset('/imagenes/unidades_interiores/'.$imagen->archivo) }}" download="">Descargar</a></td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>