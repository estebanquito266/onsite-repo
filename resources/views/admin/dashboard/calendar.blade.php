@if (!count($empresasOnsite))

<p>El usuario no tiene empresas onsite asignadas.
    Si considera que es un error, contacte al administrador</p>
@else

<div class="card mt-3">


    <div class="card-header card-header-tab  mt-3">
        <div class="card-header-title font-size-lg text-capitalize font-weight-normal">
            <i class="header-icon lnr-calendar-full mr-3 text-muted opacity-6"> </i>
            Calendario de Visitas Coordinadas
        </div>
        <div class="btn-actions-pane-right actions-icon-btn">
            <div class="btn-group dropdown">
                <button id="visitas_por_obra" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-icon btn-icon-only btn btn-link">
                    <i class="pe-7s-menu btn-icon-wrapper"></i>
                </button>
                <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-right rm-pointers dropdown-menu-shadow dropdown-menu-hover-link dropdown-menu" style="">
                    <button type="button" tabindex="0" class="dropdown-item">
                        <i class="dropdown-icon lnr-inbox"> </i><span>Visitas por Obra</span>
                    </button>
                    <button type="button" tabindex="0" class="dropdown-item" id="visitas_por_tecnico">
                        <i class="dropdown-icon lnr-file-empty"> </i><span>Visitas Por Técnico</span>
                    </button>
                </div>
            </div>
        </div>

    </div>
    <div class="card-body text-center">
    <iframe src="https://calendar.google.com/calendar/embed?height=600&wkst=1&bgcolor=%23ffffff&ctz=America%2FArgentina%2FBuenos_Aires&showTitle=0&showNav=1&showDate=1&showTabs=1&showCalendars=0&showTz=0&src=MGp0OXFsMGRoMzJldDZyZ2tnM2FzajRvbjBAZ3JvdXAuY2FsZW5kYXIuZ29vZ2xlLmNvbQ&color=%23D81B60" style="border:solid 1px #777" width="800" height="600" frameborder="0" scrolling="no"></iframe>
    </div>



</div>

@endif