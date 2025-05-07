@if (Session::has('localidadOnsite') || Session::has('terminalOnsite') || Session::has('sucursalesOnsite') || Session::has('reparacionOnsite'))
<li @if( Request::segment(1)=='filtrarEmpresaOnsite' || Request::segment(1)=='slaOnsite' || Request::segment(1)=='filtrarSlaOnsite' || Request::segment(1)=='localidadOnsite' || Request::segment(1)=='filtrarLocalidadOnsite' ) class="mm-active" @endif>
  <a href="#">
    <i class="metismenu-icon pe-7s-settings"></i>
    Configuración
    <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
  </a>
  <ul @if( Request::segment(1)=='filtrarEmpresaOnsite' || Request::segment(1)=='slaOnsite' || Request::segment(1)=='filtrarSlaOnsite' || Request::segment(1)=='localidadOnsite' || Request::segment(1)=='filtrarLocalidadOnsite' ) class="mm-show" @endif>

    @if(Session::has('localidadOnsite') )
    <li>
      <a href="{!! URL::to('/localidadOnsite') !!}" @if( Request::segment(1)=='localidadOnsite' || Request::segment(1)=='filtrarLocalidadOnsite' ) class="mm-active" @endif>
        <i class="metismenu-icon pe-7s-news-paper"></i>Localidades
      </a>
    </li>
    @endif

    @if(Session::has('terminalOnsite') )
    <li>
      <a href="{!! URL::to('/terminalOnsite') !!}" @if( Request::segment(1)=='terminalOnsite' || Request::segment(1)=='filtrarTerminalOnsite' ) class="mm-active" @endif>
        <i class="metismenu-icon pe-7s-news-paper"></i>Terminales
      </a>
    </li>
    <li>

      @endif

      @if(Session::has('sucursalesOnsite') )
    <li>
      <a href="{!! URL::to('/sucursalesOnsite') !!}" @if( Request::segment(1)=='sucursalesOnsite' || Request::segment(1)=='filtrarSucursalesOnsite' ) class="mm-active" @endif>
        <i class="metismenu-icon pe-7s-news-paper"></i>Sucursales
      </a>
    </li>
    @endif
    @if(Session::has('reparacionOnsite') )
    <li>
      <a href="{!! URL::to('/soporteReparacionesOnsite') !!}" @if( Request::segment(1)=='soporteReparacionesOnsite' ) class="mm-active" @endif>
        <i class="metismenu-icon pe-7s-news-paper"></i>Tablas de Soporte
      </a>
    </li>
    @endif


  </ul>
</li>
@endif