<?php $isEditable = isset($viewMode) && $viewMode == 'edit'; ?>

<div class="main-card mb-3 card ">
    <div class="card-header bg-secondary text-light">
        <div class="text-left col-lg-6 col-md-6">Visitas</div>

    </div>
    <div class="card-body row">

        <table style="width: 100%;"  class="table table-hover table-striped table-bordered ">
            <thead>
                <tr>
                <th>Clave</th>
                <th>Obra</th>
                
                <th>Tipo de Servicio</th>
                <th>Estado</th>

                <th>Técnico Asignado</th>

                <th>Fecha de Ingreso</th>
                <th>Fecha Vencimiento</th>

                </tr>
            </thead>
            <tbody class="small">
                @foreach($reparacionesOnsite as $reparacionOnsite)
                <tr>
                <td>
                    <?php if($isEditable) :  ?>
                        <a href="{{ url('reparacionOnsite/' . $reparacionOnsite->id . '/edit') }}">{{ $reparacionOnsite->clave }}</a>
                    <?php else:  ?>
                        {{$reparacionOnsite->clave}}
                    <?php endif;  ?>                      
                </td>
                <td>{{($reparacionOnsite->sistema_onsite && $reparacionOnsite->sistema_onsite->obra_onsite) ? $reparacionOnsite->sistema_onsite->obra_onsite->nombre : ''}}</td>
                

                
                <!-- <td>{{ ($reparacionOnsite->tipo_servicio_onsite) ? $reparacionOnsite->tipo_servicio_onsite->nombre : '' }}</td> -->

                <td>{{ ($reparacionOnsite->id_tipo_servicio) ? $reparacionOnsite->tipo_servicio_onsite->nombre : '' }}</td>

                <td>
                    {{ ($reparacionOnsite->estado_onsite) ? $reparacionOnsite->estado_onsite->nombre : '' }}
                </td>

                <td>{{ ($reparacionOnsite->tecnicoAsignado) ? $reparacionOnsite->tecnicoAsignado->name : '' }}</td>

                <td>{{$reparacionOnsite->fecha_ingreso}}</td>
                <td>
                    {{$reparacionOnsite->fecha_vencimiento}}
                </td>

                </tr>
                @endforeach
            </tbody>

            </table>




    </div>
</div>