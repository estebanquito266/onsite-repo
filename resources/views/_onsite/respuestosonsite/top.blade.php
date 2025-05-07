
                    <div class="app-page-title mt-1">
                        <div class="page-title-wrapper">
                            <div class="page-title-heading">
                                <div class="page-title-icon">
                                    <i class="pe-7s-plug icon-gradient bg-tempting-azure"></i>
                                </div>
                                <div>
									BGH ECOSMART - REPUESTOS
                                    <div class="page-title-subheading"></div>
                                </div>
                            </div>
                            <div class="page-title-actions">
								<a href="{!! URL::to('/respuestosOnsite/create/') !!}" data-toggle="tooltip" title="Crear Pedido Repuestos " data-placement="bottom" class="btn-shadow mr-3 btn btn-dark">
                                    <i class="fa fa-plus"></i>
                                </a>
                                <a href="{!! URL::to('/respuestosOnsite/') !!}" data-toggle="tooltip" title="Listado de Pedidos de Repuestos " data-placement="bottom" class="btn-shadow mr-3 btn btn-alternate">
                                    <i class="fa fa-list-ol"></i>
                                </a>

                                @if( Session::get('perfilAdmin') || Session::get('perfilAdminOnsite'))

                                <a href="{!! URL::to('/precios/') !!}" data-toggle="tooltip" title="Precios de Repuestos " data-placement="bottom" class="btn-shadow mr-3 btn btn-alternate">
                                    <i class="pe-7s-cash"></i>
                                </a>

                                @endif
                                
                            </div>    
						</div>
                    </div>   
