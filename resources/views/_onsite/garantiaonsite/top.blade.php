                    <div class="app-page-title mt-1">
                        <div class="page-title-wrapper">
                            <div class="page-title-heading">
                                <div class="page-title-icon">
                                    <i class="pe-7s-home icon-gradient bg-tempting-azure"></i>
                                </div>
                                <div>
                                    Garantías
                                    <div class="page-title-subheading">Gestión de Garantias.</div>
                                </div>
                            </div>
                            <div class="page-title-actions">
                                @if( Session::get('perfilAdmin') || Session::get('perfilAdminOnsite'))
                                <a href="{!! URL::to('/garantiaonsite/create/') !!}" data-toggle="tooltip" title="Crear Garantia Onsite" data-placement="bottom" class="btn-shadow mr-3 btn btn-dark">
                                    <i class="fa fa-plus"></i>
                                </a>
                                @endif
                                <a href="{!! URL::to('/garantiaonsite/') !!}" data-toggle="tooltip" title="Listado de Garantias Onsite" data-placement="bottom" class="btn-shadow mr-3 btn btn-alternate">
                                    <i class="fa fa-list-ol"></i>
                                </a>



                            </div>
                        </div>
                    </div>