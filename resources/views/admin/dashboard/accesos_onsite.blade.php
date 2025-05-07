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
      <div class="btn-actions-pane-right text-capitalize actions-icon-btn">
        <div class="btn-group dropdown">
          <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-icon btn-icon-only btn btn-link">
            <i class="pe-7s-menu btn-icon-wrapper"></i>
          </button>
          <!-- <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-right rm-pointers dropdown-menu-shadow dropdown-menu-hover-link dropdown-menu">
            <h6 tabindex="-1" class="dropdown-header">Header</h6>
            <button type="button" tabindex="0" class="dropdown-item">
              <i class="dropdown-icon lnr-inbox"> </i><span>Menus</span>
            </button>
            <button type="button" tabindex="0" class="dropdown-item">
              <i class="dropdown-icon lnr-file-empty"> </i><span>Settings</span>
            </button>
            <button type="button" tabindex="0" class="dropdown-item">
              <i class="dropdown-icon lnr-book"> </i><span>Actions</span>
            </button>
            <div tabindex="-1" class="dropdown-divider"></div>
            <div class="p-3 text-right">
              <button class="mr-2 btn-shadow btn-sm btn btn-link">View Details</button>
              <button class="mr-2 btn-shadow btn-sm btn btn-primary">Action</button>
            </div>
          </div> -->
        </div>
      </div>
    </div>

  </div>
  <div class="grid-menu grid-menu-3col">
    <div class="no-gutters row">
      @if(Session::has('respuestosOnsite') )
      <div class="col-sm-6 col-xl-4">
        <a href="{!! URL::to('/respuestosOnsite/') !!}" class="btn-icon-vertical btn-square btn-transition btn btn-outline-link">
          <i class="pe-7s-plug btn-icon-wrapper btn-icon-lg mb-3"> </i>Repuestos
        </a>
      </div>
      @endif

      @if(Session::has('exportarPedidosRepuesto') )
      <div class="col-sm-6 col-xl-4">
        <a href="{!! URL::to('/exportar_repuestos/') !!}" class="btn-icon-vertical btn-square btn-transition btn btn-outline-link botonExportador">
          <i class="pe-7s-server btn-icon-wrapper btn-icon-lg mb-3"> </i>Exportar
        </a>
      </div>
      @endif

      @if(Session::has('appTecnicosAcceso') )
      <div class="col-sm-6 col-xl-4">
        <a href="{!! URL::to('https://app.speedup.com.ar') !!}" target="_blank" class="btn-icon-vertical btn-square btn-transition btn btn-outline-link">
          <i class="pe-7s-science btn-icon-wrapper btn-icon-lg mb-3"> </i>APP Técnicos ONSITE
        </a>
      </div>
      @endif

      @if(Session::has('createObra') )
      <div class="col-sm-6 col-xl-4">
        <a href="{!! URL::to('/createObra/') !!}" class="btn-icon-vertical btn-square btn-transition btn btn-outline-link">
          <i class="lnr-construction btn-icon-wrapper btn-icon-lg mb-3"> </i>Crear Obras
        </a>
      </div>
      @endif

      @if( Session::get('perfilAdmin') || Session::get('perfilAdminOnsite'))
      <div class="col-sm-6 col-xl-4">
        <a href="{!! URL::to('/createSistema/') !!}" class="btn-icon-vertical btn-square btn-transition btn btn-outline-link">
          <i class="lnr-apartment btn-icon-wrapper btn-icon-lg mb-3"> </i>Crear Sistemas
        </a>
      </div>
      @endif
      

      @if(Session::has('solicitudesInspeccion') || Session::has('SolicitudPuestaMarcha') )
      <div class="col-sm-6 col-xl-4">
        <a href="{!! URL::to('/createSolicitudInspeccion') !!}" class="btn-icon-vertical btn-square btn-transition btn btn-outline-link">
          <i class="pe-7s-bicycle btn-icon-wrapper btn-icon-lg mb-3"> </i>Solicitud de Inspección
        </a>
      </div>
      @endif

      @if(Session::has('garantiaonsite') )
      <div class="col-sm-6 col-xl-4">
        <a href="{!! URL::to('/garantiaonsite/') !!}" class="btn-icon-vertical btn-square btn-transition btn btn-outline-link">
          <i class="pe-7s-shield btn-icon-wrapper btn-icon-lg mb-3"> </i>Garantías
        </a>
      </div>
      @endif

      @if( Session::get('perfilAdmin') || Session::get('perfilAdminOnsite'))
      <div class="col-sm-6 col-xl-4">
        <a target="_blank" href="http://help.speeduplatam.com/abm-usuarios-bgh/" class="btn-icon-vertical btn-square btn-transition btn btn-outline-link">
          <i class="lnr-users btn-icon-wrapper btn-icon-lg mb-3"> </i>ABM Usuarios
        </a>
      </div>
      @endif


    </div>
  </div>
  <div class="divider"></div>

</div>





@endif