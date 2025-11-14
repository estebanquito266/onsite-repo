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
                @php
                    use Illuminate\Support\Str;
                @endphp

                @foreach($reparacionOnsite->imagenesOnsite as $imagenOnsite)

                    @php
                        $archivo = $imagenOnsite->archivo;

                        $esUrl = Str::startsWith($archivo, ['http://', 'https://']);

                        if ($esUrl) {
                            $url = $archivo;
                            $exists = true; 
                            $file_name = $archivo;
                        } else {
                            $exists = Storage::disk('local2')->exists($archivo);
                            $url = "/imagenes/reparaciones_onsite/" . $archivo;
                            if ($exists) {
                                $file_name = './imagenes/reparaciones_onsite/' . $archivo;
                            }
                        }
                    @endphp

                    @if($exists)
                        <tr id="tr_imagen_onsite_{{$imagenOnsite->id}}">
                            <td class="text-center">
                                <a href="{{ $url }}" class="badge badge-primary" target="_BLANK">Link</a>
                            </td>

                            <td class="text-center">
                                <span class="badge badge-pill badge-warning">
                                    {{ optional($imagenOnsite->tipoImagenOnsite)->nombre }}
                                </span>
                            </td>

                            <td>
                                <a href="{{ $url }}" target="_BLANK">
                                    @php
                                        $mime = $esUrl ? 'image' : mime_content_type($file_name);
                                    @endphp

                                    @if (Str::startsWith($mime, 'image'))
                                        <img src="{{ $url }}" width="100">
                                    @elseif ($mime === 'application/pdf')
                                        <img src="/imagenes/reparaciones_onsite/pdf.png" width="100">
                                    @elseif (in_array($mime, [
                                        'application/vnd.ms-excel',
                                        'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                                    ]))
                                        <img src="/imagenes/reparaciones_onsite/excel.png" width="100">
                                    @else
                                        <img src="/imagenes/reparaciones_onsite/aplication.png" width="100">
                                    @endif
                                </a>
                            </td>

                            <td class="text-right">
                                <button class="btn btn-danger eliminar-imagen-onsite"
                                        type="button"
                                        data-id="{{$imagenOnsite->id}}"
                                        id="eliminarImagenOnsite{{$imagenOnsite->id}}"
                                        onclick="eliminarImagen(this);">
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