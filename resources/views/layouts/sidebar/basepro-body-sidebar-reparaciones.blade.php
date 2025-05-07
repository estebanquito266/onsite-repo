        @if( Session::has('reparacionOnsite') || Session::has('reparacionOnsitePosnet') || Session::has('reparacionOnsiteFacturada') || Session::has('historialEstadoOnsite') || Session::has('reporteReparacionOnsite') || Session::has('reparacionOnsiteEmpresaActivas') || Session::has('reparacionOnsiteConPresupuestoPendiente') || Session::has('reparacionOnsiteEmpresaCerradas') || Session::has('localidadOnsite') || Session::has('terminalOnsite') || Session::has('sucursalesOnsite'))
        <li class="app-sidebar__heading">REPARACIONES</li>

        @if(Session::has('reparacionOnsite') )
        <li>
          <a href="{!! URL::to('/reparacionOnsite') !!}" @if( Request::segment(1)=='reparacionOnsite' || Request::segment(1)=='filtrarReparacionOnsite' ) class="mm-active" @endif>
            <i class="metismenu-icon pe-7s-tools"></i>Reparaciones
          </a>
        </li>
        @endif

        @if(Session::has('reparacionOnsitePosnet') )
        <li>
          <a href="{!! URL::to('/reparacionOnsitePosnet') !!}" @if( Request::segment(1)=='reparacionOnsitePosnet' ) class="mm-active" @endif>
            <i class="metismenu-icon pe-7s-usb"></i>Reparac. POSNET
          </a>
        </li>
        @endif

        @if(Session::has('reparacionOnsiteFacturada') )
        <li>
          <a href="{!! URL::to('/reparacionOnsiteFacturada') !!}" @if( Request::segment(1)=='reparacionOnsiteFacturada' ) class="mm-active" @endif>
            <i class="metismenu-icon pe-7s-note2"></i>Reparac. Fact. sin Liq.
          </a>
        </li>
        @endif

        @if(Session::has('historialEstadoOnsite') )
        <li>
          <a href="{!! URL::to('/historialEstadoOnsite') !!}" @if( (Request::segment(1)=='historialEstadoOnsite' ) || Request::segment(1)=='filtrarHistorialEstadoOnsite' || Request::segment(1)=='historialEstadoOnsiteTodos' ) class="mm-active" @endif>
            <i class="metismenu-icon pe-7s-mail-open-file"></i>Notificaciones
          </a>
        </li>
        @endif

        @include('layouts.sidebar.basepro-body-sidebar-configuracion')

        <li class="app-sidebar__heading">REPORTES</li>

        @if(Session::has('reporteReparacionOnsite') )
        <li>
          <a href="{!! URL::to('/reporteReparacionOnsite/0') !!}" @if( Request::segment(1)=='reporteReparacionOnsite' && Request::segment(2)=='0') class="mm-active" @endif>
            <i class="metismenu-icon pe-7s-graph2"></i>Reporte Reparación
          </a>
        </li>
        @endif

        @if(Session::has('reporteReparacionOnsite') || Session::has('reporteReparacionOnsiteExitoso') )
        <li>
          <a href="{!! URL::to('/reporteReparacionOnsite/1') !!}" @if( Request::segment(1)=='reporteReparacionOnsite' && Request::segment(2)=='1') class="mm-active" @endif>
            <i class="metismenu-icon pe-7s-graph2"></i>Reporte Reparación Exitoso
          </a>
        </li>
        @endif

        @if(Session::has('reporteReparacionOnsite') || Session::has('reporteReparacionOnsiteNoExitoso') )
        <li>
          <a href="{!! URL::to('/reporteReparacionOnsite/2') !!}" @if( Request::segment(1)=='reporteReparacionOnsite' && Request::segment(2)=='2' ) class="mm-active" @endif>
            <i class="metismenu-icon pe-7s-graph2"></i>Reporte Reparación No Exitoso
          </a>
        </li>
        @endif

        <li class="app-sidebar__heading">SERVICIOS</li>


        @if(Session::has('reparacionOnsiteEmpresaActivas') )
        <li>
          <a href="{!! URL::to('/reparacionOnsiteEmpresaActivas') !!}" @if( Request::segment(1)=='reparacionOnsiteEmpresaActivas' ) class="mm-active" @endif>
            <i class="metismenu-icon pe-7s-graph1"></i>Servicios en curso
          </a>
        </li>
        @endif
        @if(Session::has('reparacionOnsiteConPresupuestoPendiente') )
        <li>
          <a href="{!! URL::to('/reparacionOnsiteConPresupuestoPendienteDeAprobacion') !!}" @if( Request::segment(1)=='reparacionOnsiteConPresupuestoPendienteDeAprobacion' ) class="mm-active" @endif>
            <i class="metismenu-icon pe-7s-info"></i>Pendientes de aprobación
          </a>
        </li>
        @endif
        @if(Session::has('reparacionOnsiteEmpresaCerradas') )
        <li>
          <a href="{!! URL::to('/reparacionOnsiteEmpresaCerradas') !!}" @if( Request::segment(1)=='reparacionOnsiteEmpresaCerradas' ) class="mm-active" @endif>
            <i class="metismenu-icon pe-7s-hammer"></i>Servicios finalizados
          </a>
        </li>
        @endif
        <li>
          <a href="{!! URL::to('/excels/Stock.xlsx') !!}" >
            <i class="metismenu-icon pe-7s-tools"></i>STOCK DE REPUESTOS
          </a>
        </li>
        @endif