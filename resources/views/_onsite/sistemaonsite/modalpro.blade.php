
<div class="modal fade" id="modalSistemas" tabindex="-1" role="dialog" aria-labelledby="modalLabelSistemas" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalLabelSistemas">Sistema</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="datosSistemas">
                    @csrf
					@include('_onsite.sistemaonsite.campos')
				</form>			
            </div>
            <div class="modal-footer">
                <div class="col-md-6">
					<button type="button" class="btn btn-primary" id="storeModalSistema">Crear</button>
					<button type="button" class="btn btn-primary" id="updateModalSistema">Modificar</button>
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
@section('scripts')
    <script type="text/javascript" src="{!! asset('/assets/js/_onsite/sistemas-onsite.js') !!}"></script>
@endsection


