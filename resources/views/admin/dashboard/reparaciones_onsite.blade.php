@if (!count($empresasOnsite))
   
    <p>El usuario no tiene empresas onsite asignadas. 
    Si considera que es un error, contacte al administrador</p>
@else

<div class="main-card mb-3 card">
  <div class="card-header">
    <h3 class="mr-3">Estadisticas</h3>
    <!--
    <button type="button" data-toggle="collapse" href="#filtro" class="btn-shadow mr-3 btn btn-secondary">
      <i class="fa fa-filter"></i>
    </button>
    -->
  </div>
  <div class="card-body"> 
    
    <div class="border mb-5 pl-3 pr-3 pb-3" >
      <form action="{{ url('filtrarReparacionOnsitePorEmpresa') }}" method="POST">
        {{ csrf_field() }}
        <div class="form-row mt-3">
          <div class=' col-lg-5' style="margin: 0 auto;">
            <div class='form-group'>
              <label>Empresa Onsite</label>
              <select name="empresa_id" id="empresa_id" class="form-control multiselect-dropdown">
                @foreach ($empresasOnsite as $empresaOnsite)
                <option value="{{ $empresaOnsite->id }}" {{ (isset($empresa_id) && $empresa_id == $empresaOnsite->id) ? 'selected' : '' }}>{{ $empresaOnsite->nombre }}</option>
                @endforeach
              </select>
            </div>

            <button type="submit" class="btn btn-success btn-block btn-pill pull-right" name="boton" value="csv">Filtrar</button>
          </div>

           
        </div>
        <!--
        <div class="form-row mt-3">
          <div class=' col-lg-4'>
            <div class='form-group'>
              <button type="submit" class="btn btn-success btn-block btn-pill pull-right" name="boton" value="csv">Filtrar</button>
            </div>
          </div>
        </div>
        -->
      </form>
    </div>


  
    <!-- METRICAS -->
    <div class="main-card mb-3 card">
      <div class="card-header-tab card-header">
        <div class="card-header-title font-size-lg text-capitalize font-weight-normal">
          Reparaciones Onsite - {{ isset($empresa_seleccionada) ? $empresa_seleccionada->nombre: ''  }}
        </div>
      </div>
      <div class="grid-menu grid-menu-3col">
        <div class="no-gutters row">
          @foreach ($reparacionesCantidadesEstados as $key => $estado)
       
          <div class="col-sm-6 col-xl-2">

            <div class="widget-chart  widget-chart-hover card-border br-br" id="{{ $key }}">

              <div class="icon-wrapper rounded-circle">
                <div class="icon-wrapper-bg bg-primary"></div>
                <i class="{{ $estado['clase'] }}"></i>
              </div>
              <div class="widget-numbers">{{ $estado['cantidad'] }}</div>
              <div class="widget-subheading  text-uppercase">{{ $estado['etiqueta'] }}</div>
              <div class="widget-description text-primary">
                <span class="pl-1"> 
                    @if( $estado['cantidad'] > 0 )
                      {{ round( ($estado['cantidad'] * 100) / $totalPendientes) }} %
                    @endif

                </span>
              </div>

            </div>

          </div>
          
          @endforeach

          <div class="col-sm-6 col-xl-2">
            <div class="bg-premium-dark widget-chart-hover widget-chart card-border br-br" id="reparacionOnsiteTotal">
              <div class="widget-chart-content text-white">
                <div class="icon-wrapper rounded-circle">
                  <div class="icon-wrapper-bg bg-danger opacity-8"></div>
                  <i class="lnr-earth"></i>
                </div>
                <div class="widget-numbers">{{ $totalPendientes }}</div>
                <div class="widget-subheading text-uppercase">Total Pendientes</div>
                <div class="widget-description text-white">
                  <span class="pr-1">
                    @if ($totalPendientes > 0)
                      {{ round( ($estado['cantidad'] * 100) / $totalPendientes) }} %
                    @else
                      0 %
                    @endif
                  </span>
                </div>
              </div>
            </div>
          </div>

        </div>
      </div>
    </div>

  </div>
</div>
@endif
