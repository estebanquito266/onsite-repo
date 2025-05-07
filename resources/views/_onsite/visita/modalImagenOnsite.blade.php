<form action="#" id="formAgregarImagenOnsite" enctype="multipart/form-data" method="POST">
<div class="modal fade" id="modalImagenOnsite" tabindex="-1" role="dialog" aria-labelledby="modalLabelImagenOnsite" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabelImagenOnsite"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                
                    <div class="main-card mb-3 card ">
                            <div class="card-header bg-secondary text-white">Agregar Evidencia</div>
                            <div class="card-body">
                                <div class="form-row mt-3">

                                    <div class="form-group col-lg-12 col-md-12 ">
                                        <label>Archivo</label>
                                        <input type="hidden" id="reparacion_onsite_id" name="reparacion_onsite_id" value="{{$reparacionOnsite->id}}">
                                        <input type="file" class="form-control" id="imagen_onsite_archivo" name="imagen_onsite_archivo" required="required" />
                                        
                                    </div>

                                    <div class="form-group col-lg-12 col-md-12 ">
                                        <label>Tipo</label>
                                        <select id="imagen_onsite_tipo_id" name="imagen_onsite_tipo_id" required="required" class="form-control multiselect-dropdown" placeholder="Seleccione un tipo de evidencia">
                                            @foreach ($tiposImagenOnsite as $tipoImagenOnsite)
                                                <option value="{{$tipoImagenOnsite->id}}">{{$tipoImagenOnsite->nombre}}</option>
                                            @endforeach
                                        </select>   
                                    </div>

                                </div>
                            </div>
                        </div>
                
            </div>
            <div class="modal-footer">
                <div class="col-md-6">
					<button type="submit" class="btn btn-primary" id="storeModalImagenOnsite">Agregar</button>
					<button type="button" class="btn btn-secondary" id="cerrarModalImagenOnsite">Cerrar</button>
				</div>
				<br>
				<div class="col-md-6">
					<div class="alert alert-danger"  id="mensaje-error-terminal" style="display:none"></div>
				</div>
            </div>
        </div>
    </div>
</div>
</form>