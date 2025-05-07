                    <div class="app-page-title mt-1">
                        <div class="page-title-wrapper">
                            <div class="page-title-heading">
                                <div class="page-title-icon">
                                    <i class="pe-7s-home icon-gradient bg-tempting-azure"></i>
                                </div>
                                <div>
                                    Obra
                                    <div class="page-title-subheading">Gestión de Obras.</div>
                                </div>
                            </div>
                            <div class="page-title-actions row">
                                <span class="text-center col-md-3 small">
                                    <a href="{!! URL::to('/createObra/') !!}" data-toggle="tooltip" title="Crear Obra Onsite" data-placement="bottom" class="btn-shadow btn btn-dark">
                                        <i class="fa fa-plus"></i>
                                    </a>
                                    <br/>
                                    Crear
                                </span>

                                <span class="text-center col-md-3 small">
                                    <a href="{!! URL::to('/obrasOnsite/') !!}" data-toggle="tooltip" title="Listado de Obras Onsite" data-placement="bottom" class="btn-shadow btn btn-alternate">
                                        <i class="fa fa-list-ol"></i>
                                    </a>
                                    <br/>
                                    Listado
                                </span>                                

                                @if( Session::get('perfilAdmin') || Session::get('perfilAdminOnsite'))
                                <span class="text-center col-md-3 small">
                                    <a href="{!! URL::to('/obrasOnsiteUnificado/') !!}" data-toggle="tooltip" title="Listado UNIFICADO" data-placement="bottom" class="btn-shadow btn btn-alternate">
                                        <i class="pe-7s-rocket"></i>
                                    </a>
                                    <br/>
                                    Unificado
                                </span>     

                                <span class="text-center col-md-3 small">
                                    <a href="{!! URL::to('/viewDashboardObra/') !!}" data-toggle="tooltip" title="Detalle" data-placement="bottom" class="btn-shadow btn btn-alternate">
                                        <i class="pe-7s-graph"></i>
                                    </a>
                                    <br/>
                                    Detalle
                                </span>                                         
                                @endif
                                



                            </div>
                        </div>
                    </div>