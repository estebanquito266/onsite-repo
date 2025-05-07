<div class="modal fade" id="modalEditPieza" tabindex="-1" role="dialog" aria-labelledby="modalEditPieza" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalEditPiezaTitle"></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <!-- Administradores -->
            @if($user->perfil_usuario[0]->perfil->id == 1 || $user->perfil_usuario[0]->perfil->id == 62)
            <div class="modal-body">

             <!-- Administradores -->
                <div class="main-card mb-3 card ">
                    <div class="card-header card-header-tab  ">
                        <div class="card-header-title font-size-lg text-capitalize font-weight-normal">
                            <i class="header-icon pe-7s-cart mr-3 text-muted opacity-6"> </i>
                            Datos de la Pieza
                        </div>
                        <div class="btn-actions-pane-right actions-icon-btn">
                            <div class="btn-group dropdown">
                                <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-icon btn-icon-only btn btn-link">
                                    <i class="pe-7s-menu btn-icon-wrapper"></i>
                                </button>
                                <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-right rm-pointers dropdown-menu-shadow dropdown-menu-hover-link dropdown-menu" style="">

                                    <button type="button" tabindex="0" class="dropdown-item">
                                        <i class="dropdown-icon lnr-file-empty"> </i><span>Settings</span>
                                    </button>

                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="card-body align-items-center bodyModal">
                        <div class="form-row mt-3">

                            <!-- <div class="form-group col-6">
                                <label for="numero"><strong>Nº de Pieza:</strong></label>
                                <input class="form-control" type="text" id="numero_modal" name="numero">
                               
                            </div> -->

                            <div class="form-group col-6">
                                <label for="spare_parts_code"><strong>Código de Pieza</strong></label>
                                <input class="form-control" type="text" id="spare_parts_code_modal" name="spare_parts_code">
                                <small class="form-text text-muted">Código de la pieza
                                </small>
                            </div>

                            <div class="form-group col-12">
                                <label for="part_name"><strong>Nombre de la Pieza</strong></label>
                                <input class="form-control" type="text" id="part_name_modal" name="part_name">
                                <small class="form-text text-muted">part_name</small>
                            </div>


                            <div class="form-group col-12">
                                <label for="description"><strong>Descripción</strong></label>
                                <input class="form-control" type="text" id="description_modal" name="description">
                                <small class="form-text text-muted">description</small>
                            </div>

                            <!-- <div class="form-group col-12">
                                <label for="part_image"><strong>Imágen</strong></label>
                                <input class="form-control" type="file" id="part_image_modal" name="part_image">
                                <small class="form-text text-muted">part_image</small>

                            </div> -->

                            
                            <div class="form-group col-4">
                                <label for="moneda"><strong>Moneda</strong></label>
                                <select class="form-control" name="moneda" id="moneda_modal">                                    
                                    <option value="dolar">Dolar</option>
                                    <option value="euro">Euro</option>
                                    <option value="peso">Peso</option>
                                </select>
                                
                                <small class="form-text text-muted">moneda</small>
                            </div>

                            <div class="form-group col-4">
                                <label for="precio_fob"><strong>Precio</strong></label>
                                <input class="form-control" type="number" step="0.01" id="precio_fob_modal" name="precio_fob">
                                <small class="form-text text-muted">precio_fob</small>
                            </div>

                            <div class="form-group col-4">
                                <label for="peso"><strong>Peso</strong></label>
                                <input class="form-control" type="number" step="0.01" id="peso_modal" name="peso">
                                <small class="form-text text-muted">peso</small>
                            </div>

                            <div class="form-group col-6">
                                <label for="dimensiones"><strong>Dimensiones</strong></label>
                                <input class="form-control" type="text" id="dimensiones_modal" name="dimensiones">
                                <small class="form-text text-muted">dimensiones</small>
                            </div>

                            <div class="form-group col-6">
                                <label for="pia"><strong>PIA</strong></label>
                                <input class="form-control" type="text" id="pia_modal" name="pia">
                                <small class="form-text text-muted">pia</small>
                            </div>

                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer col-12 footer_modal_repuestos">
                <div class="col-9">
                    <button type="submit" class="btn btn-primary col-12" id="confirmarModalEditPieza" data-idpieza="0">Guardar Cambios</button>
                </div>

                <div class="col-3">
                    <button type="button" class="btn btn-secondary col-12" id="cerrarModalEditPieza">Cerrar</button>
                </div>

            </div>
            
            <!-- Usuarios -->
            @else
            <div class="modal-body">             
                <div class="main-card mb-3 card ">
                    <div class="card-header card-header-tab  ">
                        <div class="card-header-title font-size-lg text-capitalize font-weight-normal">
                            <i class="header-icon pe-7s-cart mr-3 text-muted opacity-6"> </i>
                            Información
                        </div>                        
                    </div>
                    <div class="card-body align-items-center bodyModal">
                        <div class="form-row mt-3">                            

                            <div class="form-group col-6">
                                <label for="spare_parts_code"><strong>No posee autorización para Editar. Si lo considera un error consulte al Administrador del sistema.</strong></label>
                                
                            </div>  

                        </div>
                    </div>
                </div>
            </div>
            <div class="modal-footer col-12 footer_modal_repuestos">
               
                <div class="col-3">
                    <button type="button" class="btn btn-secondary col-12" id="cerrarModalEditPieza">Cerrar</button>
                </div>

            </div>

            @endif

        </div>
    </div>
</div>