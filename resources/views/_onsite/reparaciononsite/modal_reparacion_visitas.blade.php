<!-- Modal -->
<div class="modal fade" id="modal_reparacion_visitas" tabindex="-1" role="dialog" aria-labelledby="modelTitleId" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Primer Visita</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="{{route('registrar_visita', $reparacionOnsite->id)}}" method="post">
            <div class="modal-body">

                    @csrf
                    <div class="form-group">
                        <label for="fecha">Fecha</label>
                        <input type="datetime-local" name="fecha" id="fecha" class="form-control" placeholder="" aria-describedby="helpId">
                        <small id="helpId" class="text-muted">Inserte Fecha de visita</small>
                    </div>

                    <div class="form-group">
                        <label for="fecha">Fecha nuevo vencimiento</label>
                        <input type="datetime-local" name="fecha_nuevo_vencimiento" id="fecha_nuevo_vencimiento" class="form-control" placeholder="" aria-describedby="helpId">
                        <small id="helpId" class="text-muted">Inserte Fecha del nuevo vencimiento determinado</small>
                    </div>

                    <div class="form-group">
                        <label for="">Motivo</label>
                        <textarea class="form-control" name="motivo" id="motivo"> </textarea>
                        <small id="helpId" class="text-muted">Describa el motivo de la visita</small>
                    </div>

                    <input type="hidden" value="{{$reparacionOnsite->fecha_vencimiento}}" name="fecha_vencimiento">
                    <input type="hidden" value="{{$reparacionOnsite->company_id}}" name="company_id">



                    
                    
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Enviar</button>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Cerrar</button>
                </div>
            </form>
        </div>
    </div>
</div>