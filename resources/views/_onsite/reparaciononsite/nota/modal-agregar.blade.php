<div class="modal fade" id="modalAgregarNota" tabindex="-1" role="dialog" aria-labelledby="modalLabelAgregarNota">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="modalLabelAgregarNota">Agregar Nota</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>

      <div class="modal-body">
        <form id="formNota">
          @csrf
          @include('_onsite.reparaciononsite.nota.campos-modal-agregar')
          <button class='btn btn-primary btn-block' id='guardarNota'>Guardar</button>
        </form>
      </div>

      <div class="modal-footer"></div>
    </div>
  </div>
</div>