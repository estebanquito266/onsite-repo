<div class="main-card mb-3 card ">
    <div class="card-header bg-alternate">
    </div>
    <div class="card-body">
        <div class="form-row mt-3">
            <div class="form-group col-12">
                <label>Cantidad Visitas </label>
                <input type="text" class="form-control" placeholder="Cantidad de visitas" name="cantidad_visitas" value="{{ $reparacionOnsite->reparacion_detalle->cantidad_visitas }}" readonly>


            </div>

            <div class="form-group col-12">
                <hr>
                @if(count($reparacionOnsite->visitas) > 0)


                <table class="table">
                    <thead>
                        <tr>
                            <th>Fecha</th>
                            <th>Fecha Vencimiento</th>
                            <th>Nuevo Vencimiento</th>
                            <th>Motivo</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($reparacionOnsite->visitas as $visita)
                        <tr>
                            @if(isset($visita->fecha_vencimiento) && isset($visita->fecha) && isset($visita->fecha_nuevo_vencimiento) && !is_null($visita->fecha_vencimiento) && !is_null($visita->fecha) && !is_null($visita->fecha_nuevo_vencimiento))
                            <td>{{$visita->fecha->format('d/m/Y')}}</td>
                            <td>{{ $visita->fecha_vencimiento->format('d/m/Y H:i') }}</td>
                            <td>{{ $visita->fecha_nuevo_vencimiento->format('d/m/Y H:i') }}</td>
                            <td>{{$visita->motivo}}</td>
                            @endif
                        </tr>
                        @endforeach
                    </tbody>
                </table>


                @endif

                <button type="button" class="btn btn-primary btn-lg" data-toggle="modal" data-target="#modal_reparacion_visitas">
                    Reportar Primer Visita
                </button>



            </div>
        </div>
    </div>
</div>