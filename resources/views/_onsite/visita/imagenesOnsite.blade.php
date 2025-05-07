<div class="main-card mb-3 card ">
    <div class="card-header bg-primary text-light">EVIDENCIAS</div>
    <div class="card-body">
        <div class="form-group mb-12">
            <button class="btn btn-success" type="button" id="agregarImagenOnsite" data-toggle="modal" data-target="#modalImagenOnsite">
                Agregar Evidencia
            </button>
        </div>
        <table style="width: 100%;" id="fotos" class="table table-hover table-striped table-bordered ">
            <thead class="encabezado-imagenes">
                @if(count($reparacionOnsite->imagenesOnsite)>0)
                <tr>
                    <th class="text-center">Archivo</th>
                    <th class="text-center">Tipo</th>
                    <th>Vista Previa</th>
                    <th>Comandos</th>
                </tr>
                @endif
            </thead>
            <tbody class="small" id="tbody_imagenes_onsite">
                @foreach($reparacionOnsite->imagenesOnsite as $imagenOnsite)
                <?php
                if ($imagenOnsite->archivo) {
                    $exists = Storage::disk('local2')->exists($imagenOnsite->archivo);
                    if ($exists == true)
                        $file_name = './imagenes/reparaciones_onsite/' . $imagenOnsite->archivo;
                }
                ?>
                @if($exists)
                <tr id="tr_imagen_onsite_{{$imagenOnsite->id}}">
                    <td class="text-center">
                        <a href="/imagenes/reparaciones_onsite/{{$imagenOnsite->archivo}} " class="badge badge-primary" target="_BLANK">Link</a>
                    </td>
                    <td class="text-center">
                        <span class="badge badge-pill badge-warning">
                            {{ ($imagenOnsite->tipoImagenOnsite) ? $imagenOnsite->tipoImagenOnsite->nombre : ''}}
                        </span>
                    </td>
                    <td>
                        <a href="/imagenes/reparaciones_onsite/{{$imagenOnsite->archivo}}" target="_BLANK">
                            @if (substr(mime_content_type($file_name),0, 5) == 'image')
                            <img src="/imagenes/reparaciones_onsite/{{$imagenOnsite->archivo}}" width="100">
                            @else
                            @if (mime_content_type($file_name) == 'application/pdf')
                            <img src="/imagenes/reparaciones_onsite/pdf.png" width="100">
                            @else
                            @if (mime_content_type($file_name) == 'application/vnd.ms-excel' || mime_content_type($file_name) == 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet')
                            <img src="/imagenes/reparaciones_onsite/excel.png" width="100">
                            @else
                            <img src="/imagenes/reparaciones_onsite/aplication.png" width="100">
                            @endif
                            @endif
                            @endif
                        </a>

                    </td>
                    <td class="text-right">
                        <button class="btn btn-danger eliminar-imagen-onsite" type="button" data-id="{{$imagenOnsite->id}}" id="eliminarImagenOnsite{{$imagenOnsite->id}}" onclick="eliminarImagen(this);">
                            <i class="fa fa-trash"></i>
                        </button>
                    </td>
                </tr>
                
                @endif
                @endforeach
            </tbody>
        </table>
    </div>
</div>