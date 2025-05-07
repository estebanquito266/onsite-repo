@if (!count($empresasOnsite))

<p>El usuario no tiene empresas onsite asignadas.
    Si considera que es un error, contacte al administrador</p>
@else

<div class="card">


    <div class="card-header card-header-tab  mt-3">
        <div class="card-header-title font-size-lg text-capitalize font-weight-normal">
            <i class="header-icon pe-7s-note2 mr-3 text-muted opacity-6"> </i>
            Indicadores de Obra
        </div>
        <div class="btn-actions-pane-right actions-icon-btn">
            <div class="btn-group dropdown">
                <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-icon btn-icon-only btn btn-link">
                    <i class="pe-7s-menu btn-icon-wrapper"></i>
                </button>
                <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-right rm-pointers dropdown-menu-shadow dropdown-menu-hover-link dropdown-menu" style="">
                    <button type="button" tabindex="0" class="dropdown-item" id="visitas_por_obra" >
                        <i class="dropdown-icon pe-7s-paint"> </i><span>Visitas por Obra</span>
                    </button>

                    <button type="button" tabindex="1" class="dropdown-item" id="visitas_por_tecnico">
                        <i class="dropdown-icon pe-7s-user"> </i><span>Visitas Por Técnico</span>
                    </button>

                    <button type="button" tabindex="2" class="dropdown-item" id="observadas_por_empresa">
                        <i class="dropdown-icon pe-7s-home"> </i><span>Obs/Rech Por Empresa</span>
                    </button>

                    <button type="button" tabindex="2" class="dropdown-item" id="observadas_por_tecnico">
                        <i class="dropdown-icon pe-7s-delete-user"> </i><span>Obs/Rech Por Técnico</span>
                    </button>



                    
                </div>
            </div>
        </div>

    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="table_indicadores_obras" class="table table-striped table-bordered table_indicadores_obras" cellspacing="0" width="100%">
                <!-- completa dinamico -->
            </table>
        </div>
    </div>



</div>

@endif