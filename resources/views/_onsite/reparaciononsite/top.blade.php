<div class="app-page-title mt-1">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-medal icon-gradient bg-tempting-azure"></i>
            </div>
            <div>
                Reparaciones Onsite
                <div class="page-title-subheading">Gestión de las reparaciones onsite.</div>
            </div>
        </div>
        <div class="page-title-actions">
            <a href="{!! URL::to('/reparacionOnsite/create/') !!}" data-toggle="tooltip" title="Crear Reparación Onsite" data-placement="bottom" class="btn-shadow mr-3 btn btn-dark">
                <i class="fa fa-plus"></i>
            </a>
            <a href="{!! URL::to('/reparacionOnsite/') !!}" data-toggle="tooltip" title="Listado de Reparaciones Onsite" data-placement="bottom" class="btn-shadow mr-3 btn btn-alternate">
                <i class="fa fa-list-ol"></i>
            </a>
            @if(Session::has('reparacionOnsiteFacturada'))
            <a href="{!! URL::to('/reparacionOnsiteFacturada/') !!}" data-toggle="tooltip" title="Listado de Reparaciones Onsite Facturadas" data-placement="bottom" class="btn-shadow mr-3 btn btn-alternate">
                <i class="fa fa-list"></i>
            </a>
            @endif
            @if(Session::has('reparacionOnsitePosnet'))
            <a href="{!! URL::to('/reparacionOnsitePosnet/') !!}" data-toggle="tooltip" title="Listado de Reparaciones Onsite Posnet" data-placement="bottom" class="btn-shadow mr-3 btn btn-alternate">
                <i class="fa fa-th"></i>
            </a>
            @endif



        </div>
    </div>
</div>