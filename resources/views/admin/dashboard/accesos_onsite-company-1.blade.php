@if (!count($empresasOnsite))

<p>El usuario no tiene empresas onsite asignadas.
  Si considera que es un error, contacte al administrador</p>
@else


<div class="card-body">
  <div class="text-center">
    <!-- <h5 class="card-title">Accesos ONSITE</h5> -->
    <div class="card-header-tab card-header">
      <div class="card-header-title font-size-lg text-capitalize font-weight-normal">
        <i class="header-icon lnr-cloud-download icon-gradient bg-happy-itmeo"></i>
        Técnicos ONSITE
      </div>
    </div>

  </div>
  <div class="grid-menu grid-menu-3col">
    <div class="no-gutters row">

        <div class="col-sm-6 col-xl-4"> 
          <a href="{!! URL::to('/reparacionOnsite/') !!}" class="btn-icon-vertical btn-square btn-transition btn btn-outline-link">
            <i class="pe-7s-tools btn-icon-wrapper btn-icon-lg mb-3"> </i>Reparaciones
          </a>
        </div>

        @if(Session::has('localidadOnsite') )
        <div class="col-sm-6 col-xl-4"> 
          <a href="{!! URL::to('/localidadOnsite/') !!}" class="btn-icon-vertical btn-square btn-transition btn btn-outline-link">
            <i class="pe-7s-map-marker btn-icon-wrapper btn-icon-lg mb-3"> </i>Localidades
          </a>
        </div>
        @endif
        
        @if(Session::has('terminalOnsite') )
        <div class="col-sm-6 col-xl-4"> 
          <a href="{!! URL::to('/terminalOnsite/') !!}" class="btn-icon-vertical btn-square btn-transition btn btn-outline-link">
            <i class="pe-7s-monitor btn-icon-wrapper btn-icon-lg mb-3"> </i>Terminales
          </a>
        </div>
        @endif

        @if(Session::has('sucursalesOnsite') )
        <div class="col-sm-6 col-xl-4"> 
          <a href="{!! URL::to('/sucursalesOnsite/') !!}" class="btn-icon-vertical btn-square btn-transition btn btn-outline-link">
            <i class="pe-7s-culture btn-icon-wrapper btn-icon-lg mb-3"> </i>Sucursales
          </a>
        </div>        
        @endif

        @if(Session::has('reparacionOnsite') )
        <div class="col-sm-6 col-xl-4"> 
          <a href="{!! URL::to('/soporteReparacionesOnsite/') !!}" class="btn-icon-vertical btn-square btn-transition btn btn-outline-link">
            <i class="pe-7s-note2 btn-icon-wrapper btn-icon-lg mb-3"> </i>Tablas de soporte
          </a>
        </div>        
        @endif

        <div class="col-sm-6 col-xl-4"> </div>

    </div>
  </div>
  <div class="divider"></div>

</div>





@endif