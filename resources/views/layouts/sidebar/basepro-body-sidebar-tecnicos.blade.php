@if(Session::has('respuestosOnsite') || Session::has('exportarPedidosRepuesto') || Session::has('appTecnicosAcceso') || Session::has('createObra') || Session::get('createSistema') ||
Session::has('solicitudesInspeccion') || Session::has('createSolicitudInspeccion') || Session::has('SolicitudPuestaMarcha') || Session::has('garantiaonsite') || Session::get('usuario') ||
Session::get('solicitudesTiposTarifas') || Session::get('mapeo_usuarios') || Session::get('mapeo_obras'))
<li class="app-sidebar__heading">Técnicos Onsite</li>

@if(Session::has('respuestosOnsite') )
<li>
  <a href="{!! URL::to('/respuestosOnsite/') !!}" @if( Request::segment(1)=='respuestosOnsite' || Request::segment(1)=='filtrarRespuestosOnsite' ) class="mm-active" @endif>
    <i class="metismenu-icon pe-7s-plug"></i>Repuestos
  </a>
</li>
@endif

@if(Session::has('exportarPedidosRepuesto') )
<li>
  <a href="{!! URL::to('/exportar_repuestos/') !!}" class="botonExportador">
    <i class="metismenu-icon pe-7s-server"></i>Exportar Pedidos
  </a>
</li>
@endif

@if(Session::has('appTecnicosAcceso') )

<li>
  <a href="{!! URL::to('https://app.speedup.com.ar') !!}" target="_blank">
    <i class="metismenu-icon pe-7s-science"></i>APP Técnicos
  </a>
</li>

@endif

@if(Session::has('createObra') )
<li>
  <a href="{!! URL::to('/createObra/') !!}" @if( Request::segment(1)=='createObra' ) class="mm-active" @endif>
    <i class="metismenu-icon lnr-construction"></i>Crear Obras
  </a>
</li>
@endif

@if( Session::get('createSistema'))
<li>
  <a href="{!! URL::to('/createSistema/') !!}" @if( Request::segment(1)=='createSistema' ) class="mm-active" @endif>
    <i class="metismenu-icon lnr-apartment"></i>Crear Sistemas
  </a>
</li>
@endif

@if(Session::has('createSolicitudInspeccion') || Session::has('SolicitudPuestaMarcha') )
<li>
  <a href="{!! URL::to('createSolicitudInspeccion') !!}" @if( Request::segment(1)=='createSolicitudInspeccion' ) class="mm-active" @endif>
    <i class="metismenu-icon pe-7s-bicycle"></i>Solicitud de Inspección
  </a>
</li>
@endif

@if(Session::has('garantiaonsite') )
<li>
  <a href="{!! URL::to('/garantiaonsite/') !!}" @if( Request::segment(1)=='garantiaonsite' ) class="mm-active" @endif>
    <i class="metismenu-icon pe-7s-shield"></i>Garantías
  </a>
</li>
@endif

@if(Session::get('usuario'))
<li>
  <a target="_blank" href="http://help.speeduplatam.com/abm-usuarios-bgh/">
    <i class="metismenu-icon lnr-users"></i>ABM Usuarios
  </a>
</li>
@endif

@if( Session::get('solicitudesTiposTarifas'))

<li>
  <a href="{!! URL::to('/solicitudesTiposTarifas/') !!}" @if( Request::segment(1)=='solicitudesTiposTarifas' ) class="mm-active" @endif>

    <i class="metismenu-icon pe-7s-cash"></i>Tarifas Solicitudes
  </a>
</li>
@endif

@if( Session::get('mapeo_usuarios'))

<li>
  <a href="{!! URL::to('/mapeo_usuarios/') !!}" @if( Request::segment(1)=='mapeo_usuarios' ) class="mm-active" @endif>

    <i class="metismenu-icon pe-7s-global"></i>Mapa Usuarios
  </a>
</li>
@endif

@if( Session::get('mapeo_obras'))

<li>
  <a href="{!! URL::to('/mapeo_obras/') !!}" @if( Request::segment(1)=='mapeo_obras' ) class="mm-active" @endif>

    <i class="metismenu-icon pe-7s-map-marker"></i>Mapa Obras
  </a>
</li>
@endif
@endif