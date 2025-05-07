@if(Session::has('sistemaOnsite') || Session::has('unidadExterior') || Session::has('unidadInterior'))
<li @if( Request::segment(1)=='sistemaOnsite' || Request::segment(1)=='filtrarSistemaOnsite' || Request::segment(1)=='unidadExterior' || Request::segment(1)=='filtrarUnidadExteriorOnsite' || Request::segment(1)=='unidadInterior' || Request::segment(1)=='filtrarUnidadInteriorOnsite' ) class="mm-active" @endif>
  <a href="#">
    <i class="metismenu-icon pe-7s-settings"></i>
    Configuración Sistemas
    <i class="metismenu-state-icon pe-7s-angle-down caret-left"></i>
  </a>
  <ul @if( Request::segment(1)=='sistemaOnsite' || Request::segment(1)=='filtrarSistemaOnsite' || Request::segment(1)=='unidadExterior' || Request::segment(1)=='filtrarUnidadExteriorOnsite' || Request::segment(1)=='unidadInterior' || Request::segment(1)=='filtrarUnidadInteriorOnsite' ) class="mm-show" @endif>

    @if(Session::has('sistemaOnsite') )
    <li>
      <a href="{!! URL::to('/sistemaOnsite') !!}" @if( Request::segment(1)=='sistemaOnsite' || Request::segment(1)=='filtrarSistemaOnsite' ) class="mm-active" @endif>
        <i class="metismenu-icon pe-7s-news-paper"></i>Sistemas Onsite
      </a>
    </li>
    @endif

    @if(Session::has('unidadExterior') )
    <li>
      <a href="{!! URL::to('/unidadExterior') !!}" @if( Request::segment(1)=='unidadExterior' || Request::segment(1)=='filtrarUnidadExteriorOnsite' ) class="mm-active" @endif>
        <i class="metismenu-icon pe-7s-news-paper"></i>Unidad Exterior Onsite
      </a>
    </li>
    @endif

    @if(Session::has('unidadInterior') )
    <li>
      <a href="{!! URL::to('/unidadInterior') !!}" @if( Request::segment(1)=='unidadInterior' || Request::segment(1)=='filtrarUnidadInteriorOnsite' ) class="mm-active" @endif>
        <i class="metismenu-icon pe-7s-news-paper"></i>Unidad Interior Onsite
      </a>
    </li>
    @endif
  </ul>
</li>
@endif