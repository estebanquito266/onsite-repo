<div class="app-page-title mt-1">
    <div class="page-title-wrapper">
        <div class="page-title-heading">
            <div class="page-title-icon">
                <i class="pe-7s-medal icon-gradient bg-tempting-azure"></i>
            </div>
            <div>
                Tickets
                <div class="page-title-subheading">Gestión de Tickets</div>
            </div>
        </div>
        <div class="page-title-actions">
            @php 
                $createticketbygroup = Session::has('createticketbygroup');
                $createticket = Session::has('createticket');
            @endphp
            
            @if($createticketbygroup)
            
                <a href="{!! URL::to('/ticket/createbygroup/') !!}" data-toggle="tooltip" title="Crear Ticket por grupo" data-placement="bottom" class="btn-shadow mr-3 btn btn-dark">
                    <i class="fa fa-users"></i>
                </a>

                @if($createticket)
                    <a href="{!! URL::to('/ticket/create/') !!}" data-toggle="tooltip" title="Crear Ticket" data-placement="bottom" class="btn-shadow mr-3 btn btn-dark">
                        <i class="fa fa-plus"></i>
                    </a>
                @endif

            
            @else 

                <a href="{!! URL::to('/ticket/create/') !!}" data-toggle="tooltip" title="Crear Ticket" data-placement="bottom" class="btn-shadow mr-3 btn btn-dark">
                        <i class="fa fa-plus"></i>
                </a>
            @endif
            
            <a href="{!! URL::to('/ticket/') !!}" data-toggle="tooltip" title="Listado de Tickets" data-placement="bottom" class="btn-shadow mr-3 btn btn-alternate">
                <i class="fa fa-list-ol"></i>
            </a>

        </div>
    </div>
</div>