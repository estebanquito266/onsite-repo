    <div class="modal fade" id="modalAgregarVisita" tabindex="-1" role="dialog" aria-labelledby="modalLabelVisita" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="#" id="formAgregarVisita" enctype="multipart/form-data" method="POST">

                    <div class="modal-header">
                        <h5 class="modal-title" id="modalLabelVisita"></h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">

                        <div class="main-card mb-3 card ">
                            <div class="card-header bg-secondary text-white">Agregar Visita</div>

                        </div>

                    </div>
                    <div class="modal-footer">
                        <div class="align-items-center col-12">
                            <div class="col-md-12 text-center center">
                                <button type="submit" class="btn btn-primary " id="updateModalVisita">Cofirmar Nueva Visita</button>
                            </div>
                            <br>
                            <div class="col-md-12 text-center">
                                <div class="alert alert-danger" id="mensaje-error-terminal" style="display:none"></div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>