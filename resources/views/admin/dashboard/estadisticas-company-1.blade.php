@foreach($empresasOnsite as $empresaOnsite)
@php
$totalActivas = $reparacionesEmpresa[$empresaOnsite->id]['totalActivas'];
$totalCerradas = $reparacionesEmpresa[$empresaOnsite->id]['totalCerradas'];
$reparacionesCantidadesEstados = $reparacionesEmpresa[$empresaOnsite->id]['reparacionesCantidadesEstados'];
@endphp

@if( $totalActivas > 0 || $totalCerradas > 0)
<div class="tabs-animation">
    <div class="main-card mb-3 card">
        <div class="card-header-tab card-header">
            <div class="card-header-title font-size-lg text-capitalize font-weight-normal">
                Reparaciones Onsite - {{ $empresaOnsite->nombre }}

            </div>
        </div>

        @if( $totalActivas > 0 )
        <div class="grid-menu grid-menu-3col">
            <div class="no-gutters row">
                <!------------------>
                <div class="col-sm-6 col-xl-3">
                    <div class="widget-chart  widget-chart-hover card-border br-br" id="reparacionOnsiteIN">
                        <div class="icon-wrapper rounded-circle">
                            <div class="icon-wrapper-bg bg-success"></div>
                            <i class="lnr-checkmark-circle text-success"></i>
                        </div>
                        <div class="widget-numbers">{{ $reparacionesCantidadesEstados['reparacionOnsiteIN']['cantidad'] }}</div>
                        <div class="widget-subheading  text-uppercase">IN</div>
                        <div class="widget-description text-success">
                            <span class="pl-1">{{ round( ($reparacionesCantidadesEstados['reparacionOnsiteIN']['cantidad'] * 100) / $totalActivas) }}%</span>
                        </div>
                    </div>
                </div>
                <!------------------>
                <div class="col-sm-6 col-xl-3">
                    <div class="widget-chart  widget-chart-hover card-border br-br" id="reparacionOnsiteIN24">
                        <div class="icon-wrapper rounded-circle">
                            <div class="icon-wrapper-bg bg-warning"></div>
                            <i class="lnr-warning text-warning"></i>
                        </div>
                        <div class="widget-numbers">{{ $reparacionesCantidadesEstados['reparacionOnsiteIN24']['cantidad'] }}</div>
                        <div class="widget-subheading  text-uppercase">IN (24 horas)</div>
                        <div class="widget-description text-warning">
                            <span class="pl-1">{{ round( ($reparacionesCantidadesEstados['reparacionOnsiteIN24']['cantidad'] * 100) / $totalActivas) }}%</span>
                        </div>
                    </div>
                </div>
                <!------------------>
                <div class="col-sm-6 col-xl-3">
                    <div class="widget-chart  widget-chart-hover card-border br-br" id="reparacionOnsiteOUT">
                        <div class="icon-wrapper rounded-circle">
                            <div class="icon-wrapper-bg bg-danger"></div>
                            <i class="lnr-cross-circle text-danger"></i>
                        </div>
                        <div class="widget-numbers">{{ $reparacionesCantidadesEstados['reparacionOnsiteOUT']['cantidad'] }}</div>
                        <div class="widget-subheading  text-uppercase">OUT</div>
                        <div class="widget-description text-danger">
                            <span class="pl-1">{{ round( ($reparacionesCantidadesEstados['reparacionOnsiteOUT']['cantidad'] * 100) / $totalActivas) }}%</span>
                        </div>
                    </div>
                </div>
                <!------------------>
                <div class="col-sm-6 col-xl-3">
                    <div class="bg-premium-dark widget-chart-hover widget-chart card-border br-br" id="reparacionOnsiteTotal">
                        <div class="widget-chart-content text-white">
                            <div class="icon-wrapper rounded-circle">
                                <div class="icon-wrapper-bg bg-danger opacity-8"></div>
                                <i class="lnr-earth"></i>
                            </div>
                            <div class="widget-numbers">{{ $totalActivas }}</div>
                            <div class="widget-subheading text-uppercase">Total Activas</div>
                            <div class="widget-description text-white">
                                <span class="pr-1">100%</span>
                            </div>
                        </div>
                    </div>
                </div>


            </div>
        </div>

        <div class="card-footer">
            <p ><b>ACTIVAS</b>: IN, IN 24 y OUT se calculan con las reparaciones sin fecha_cerrado seteada, y con un estado del tipo activo
                <br>
                Comparando la fecha de vencimiento con la fecha de hoy, o si el sla esta justificado
                <br>
                El total de Cerradas, se calcula en base da las reparaciones con estado del tipo activo.                
            </p>

        </div>

        @endif

        @if( $totalCerradas > 0)
        <div class="grid-menu grid-menu-3col">
            <div class="no-gutters row">

                <!------------------>

                <div class="col-sm-6 col-xl-4">
                    <div class="widget-chart  widget-chart-hover card-border br-br" id="reparacionOnsiteINCERRADO">
                        <div class="icon-wrapper rounded-circle">
                            <div class="icon-wrapper-bg bg-secondary"></div>
                            <i class="lnr-checkmark-circle text-secondary"></i>
                        </div>
                        <div class="widget-numbers">{{ $reparacionesCantidadesEstados['reparacionOnsiteINCERRADO']['cantidad'] }}</div>
                        <div class="widget-subheading  text-uppercase">Cerrado (IN)</div>
                        <div class="widget-description text-secondary">
                            <span class="pl-1">{{ round( ($reparacionesCantidadesEstados['reparacionOnsiteINCERRADO']['cantidad'] * 100) / $totalCerradas) }}%</span>
                        </div>
                    </div>
                </div>

                <!------------------>

                <div class="col-sm-6 col-xl-4">
                    <div class="widget-chart  widget-chart-hover card-border br-br" id="reparacionOnsiteOUTCERRADO">
                        <div class="icon-wrapper rounded-circle">
                            <div class="icon-wrapper-bg bg-alternate"></div>
                            <i class="lnr-cross-circle text-alternate"></i>
                        </div>
                        <div class="widget-numbers">{{ $reparacionesCantidadesEstados['reparacionOnsiteOUTCERRADO']['cantidad'] }}</div>
                        <div class="widget-subheading  text-uppercase">Cerrado (OUT)</div>
                        <div class="widget-description text-alternate">
                            <span class="pl-1">{{ round( ($reparacionesCantidadesEstados['reparacionOnsiteOUTCERRADO']['cantidad'] * 100) / $totalCerradas) }}%</span>
                        </div>
                    </div>
                </div>
                <!------------------>

                <div class="col-sm-6 col-xl-4">
                    <div class="bg-premium-dark widget-chart-hover widget-chart card-border br-br" id="reparacionOnsiteTotal">
                        <div class="widget-chart-content text-white">
                            <div class="icon-wrapper rounded-circle">
                                <div class="icon-wrapper-bg bg-danger opacity-8"></div>
                                <i class="lnr-earth"></i>
                            </div>
                            <div class="widget-numbers">{{ $totalCerradas }}</div>
                            <div class="widget-subheading text-uppercase">Total Cerradas</div>
                            <div class="widget-description text-white">
                                <span class="pr-1">100%</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!------------------>
            </div>
        </div>

        <div class="card-footer">
            <p >
                <b>CERRADAS</b>: Cerrada IN y Cerrada OUT se calculan con las reparaciones con fecha_cerrado seteada, y con un estado del tipo cerrado.
                <br>
                Comparando la fecha_vencimiento con la fecha_cerrado, o si el sla esta justificado
                <br>
                El total de Cerradas, se calcula en base da las reparaciones con estado del tipo cerrado.
            </p>

        </div>
        @endif

        
    </div>
</div>
@endif
@endforeach




@if( Session::get('idPerfil') == 2 && $totalActivas > 0)
<div class="tabs-animation">
    <div class="main-card mb-3 card">
        <div class="grid-menu grid-menu-3col">
            <div class="no-gutters row">
                <!------------------>
                <div class="col-sm-6 col-xl-4">
                    <div class="widget-chart  widget-chart-hover card-border br-br" id="reparacionEstadoDiagnosticarTecnico">
                        <div class="icon-wrapper rounded-circle">
                            <div class="icon-wrapper-bg bg-warning"></div>
                            <i class="lnr-magnifier text-warning"></i>
                        </div>
                        <div class="widget-numbers">{{ $reparacionesCantidadesEstados['reparacionEstadoDiagnosticar'] }}</div>
                        <div class="widget-subheading  text-uppercase">A Diagnosticar</div>
                        <div class="widget-description text-warning">
                            <span class="pl-1">{{ round( ($reparacionesCantidadesEstados['reparacionEstadoDiagnosticar'] * 100) / $totalActivas) }}%</span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-4">
                    <div class="widget-chart  widget-chart-hover card-border br-br" id="reparacionEstadoRepararTecnico">
                        <div class="icon-wrapper rounded-circle">
                            <div class="icon-wrapper-bg bg-warning"></div>
                            <i class="lnr-magic-wand text-warning"></i>
                        </div>
                        <div class="widget-numbers">{{ $reparacionesCantidadesEstados['reparacionEstadoReparar'] }}</div>
                        <div class="widget-subheading  text-uppercase">A Reparar</div>
                        <div class="widget-description text-warning">
                            <span class="pl-1">{{ round( ($reparacionesCantidadesEstados['reparacionEstadoReparar'] * 100) / $totalActivas) }}%</span>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xl-4">
                    <div class="bg-premium-dark widget-chart-hover widget-chart card-border br-br" id="reparacionTotalTecnico">
                        <div class="widget-chart-content text-white">
                            <div class="icon-wrapper rounded-circle">
                                <div class="icon-wrapper-bg bg-danger opacity-8"></div>
                                <i class="lnr-earth"></i>
                            </div>
                            <div class="widget-numbers">{{ $totalActivas }}</div>
                            <div class="widget-subheading text-uppercase">Total Pendientes</div>
                            <div class="widget-description text-white">
                                <span class="pr-1">100%</span>
                            </div>
                        </div>
                    </div>
                </div>
                <!------------------>
            </div>
        </div>
    </div>
</div>
@endif