<?php $isEditable = isset($viewMode) && $viewMode == 'edit'; ?>

<div class="main-card mb-3 card ">
    <div class="card-header bg-secondary text-light">
        <div class="text-left col-lg-6 col-md-6">Solicitudes</div>

    </div>
    <div class="card-body row">
        <table style="width: 100%;" class="table table-hover table-striped table-bordered ">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Tipo</th>
                    <th>Estado</th>
                    <th>Comentarios</th>
                    <th>Fecha creación</th>
                    <th>Acciones</th>
                </tr>
            </thead>
            <tbody class="small">
                @foreach($solicitudes as $solicitud)
                <tr>
                    <td>
                        <?php if($isEditable) :  ?>
                            <a href="{{ url('solicitudesOnsite/' . $solicitud->id . '/edit') }}">{{$solicitud->id}}</a>
                        <?php else:  ?>
                            {{$solicitud->id}}</td>
                        <?php endif;  ?>                        
                    </td>
                    <td>{{$solicitud->tipo->nombre}}</td>
                    <td>{{$solicitud->estado_solicitud_onsite->nombre}}</td>
                    <td>{{$solicitud->comentarios}}</td>
                    <td>{{$solicitud->created_at}}</td>
                    <td>
                        <span class="mr-2"><a href="{{ route('solicitudesOnsite.show',$solicitud->id) }}"><i class="fa fa-eye fa-2x"></i></a></span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>