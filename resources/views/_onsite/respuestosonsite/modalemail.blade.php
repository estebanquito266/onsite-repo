<div class="modal fade bd-example-modal-lg" id="modalUserRepuestos" tabindex="-1" role="dialog" aria-labelledby="modalUserRepuestos" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalUserRepuestosTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">

                <div class="main-card mb-3 card ">
                    <div class="card-header card-header-tab  ">
                        <div class="card-header-title font-size-lg text-capitalize font-weight-normal">
                            <i class="header-icon pe-7s-cart mr-3 text-muted opacity-6"> </i>
                            Confirmación del Pedido
                        </div>

                    </div>
                    <div class="card-body align-items-center bodyModal">
                        <div class="form-row mt-3">

                            <div class="form-group col-12 col-md-6">
                                <label for="userName"><strong>Empresa Instaladora:</strong> {{isset($user->empresa_instaladora[0])?$user->empresa_instaladora[0]->nombre : ''}}</label>
                                <input type="hidden" id="empresa_onsite_id" value="{{isset($user->empresa_instaladora[0])?$user->empresa_instaladora[0]->id : ''}}">

                            </div>

                            <div class="form-group col-12 col-md-6">
                                <p><label><strong>Usuario:</strong> {{isset($user)?$user->name : ''}} {{isset($user)? '('. $user->email .')' : ''}}</label></p>


                            </div>

                            <div class="form-group col-12 col-md-6">
                                <label for="userName">Nombre del Solicitante</label>
                                <input type="text" id="nombre_solicitante" class="form-control" placeholder="{{'Ingrese su nombre'}}" value="{{isset($user)?$user->name : ''}}"></input>
                            </div>

                            <div class="form-group col-12 col-md-6">
                                <label for="userEmail">Email del solicitante</label>
                                <input type="text" id="email_solicitante" class="form-control" placeholder="{{'Ingrese email'}}" value="{{isset($user)?$user->email : ''}}"></input>
                                <small class="form-text text-muted">Datos de la persona solicitante.
                                </small>
                            </div>
                            <hr>
                            <div style="overflow-y: scroll; height:200px;">
                                <small id="disclaimer_repuestos">
                                    {{isset($disclaimer_repuestos)? $disclaimer_repuestos->body_text : 'Consulte Administrador por claúsulas de solicitud'}}
                                </small>
                            </div>
                            <br>
                            <div class="mt-2 ml-4 position-relative form-group col-12 custom-checkbox custom-control aceptar_condiciones">
                                <input type="checkbox" id="aceptar_condiciones" class="custom-control-input">
                                <label class="custom-control-label" for="aceptar_condiciones">Acepto los términos y condiciones</label>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer col-12 footer_modal_repuestos">
                <div class="col-9">
                    <button disabled type="submit" class="btn btn-primary col-12" id="confirmarModal" data-estadopedido="0">Confirmar y enviar pedido</button>
                </div>

                <div class="col-3">
                    <button type="button" class="btn btn-secondary col-12" id="cerrarModal">Cerrar</button>
                </div>

            </div>
        </div>
    </div>
</div>