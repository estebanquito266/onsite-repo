@if(Session::has('solicitudesOnsite') || Session::has('obrasOnsite') || Session::has('visitasOnsite') || Session::has('SolicitudPuestaMarcha') || Session::has('sistemaOnsite') || Session::has('unidadExterior') || Session::has('unidadInterior') || Session::has('viewDashboardObra'))
<li class="app-sidebar__heading">Solicitudes de Inspecciones</li>
@if(Session::has('obrasOnsite') || Session::has('SolicitudPuestaMarcha') )
<li>
  <a href="{!! URL::to('/obrasOnsite') !!}" @if( Request::segment(1)=='obrasOnsite' || Request::segment(1)=='filtrarObraOnsite' ) class="mm-active" @endif>
    <i class="metismenu-icon pe-7s-home"></i>Obras
  </a>
</li>
@endif
@if(Session::has('solicitudesOnsite') || Session::has('SolicitudPuestaMarcha') )
<li>
  <a href="{!! URL::to('/solicitudesOnsite') !!}" @if( Request::segment(1)=='solicitudesOnsite' || Request::segment(1)=='filtrarSolicitudesOnsite' ) class="mm-active" @endif>
    <i class="metismenu-icon pe-7s-call"></i>Solicitudes
  </a>
</li>
@endif

@if(Session::has('visitasOnsite') )
<li>
  <a href="{!! URL::to('/visitasOnsite') !!}" @if( Request::segment(1)=='visitasOnsite' || Request::segment(1)=='filtrarVisitas' ) class="mm-active" @endif>
    <i class="metismenu-icon pe-7s-map-2"></i>Visitas
  </a>
</li>
@endif

@if(Session::has('viewDashboardObra') )
<li>
  <a href="{!! URL::to('/viewDashboardObra') !!}" @if( Request::segment(1)=='viewDashboardObra' ) class="mm-active" @endif>
    <i class="metismenu-icon pe-7s-news-paper"></i>Generar Comprobante Visita
  </a>
</li>
@endif

@include('layouts.sidebar.basepro-body-sidebar-configuracion-sistema')
@endif