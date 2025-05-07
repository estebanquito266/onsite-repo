<div class="modal fade" id="modalTerminales" tabindex="-1" role="dialog" aria-labelledby="modalLabelTerminales" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabelTerminales">Unidades Interiores Onsite</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="datosTerminales">
                    @csrf
					@include('_onsite.terminalonsite.campos')
				</form>			
            </div>
            <div class="modal-footer">
                <div class="col-md-6">
					<button type="button" class="btn btn-primary" id="storeModalTerminal">Crear</button>
					<button type="button" class="btn btn-primary" id="updateModalTerminal">Modificar</button>
					<button type="reset" class="btn btn-secondary">Resetear</button>
				</div>
				<br>
				<div class="col-md-6">
					<div class="alert alert-danger"  id="mensaje-error-terminal" style="display:none"></div>
				</div>
            </div>
        </div>
    </div>
</div>
