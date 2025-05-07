@if (!count($empresasOnsite))

<p>El usuario no tiene empresas onsite asignadas.
  Si considera que es un error, contacte al administrador</p>
@else

<div class="row">
  <div class="col-12 col-md-6 mt-3">
    <div class="mb-3 card">
      <div class="card-header-tab card-header">
        <div class="card-header-title font-size-lg text-capitalize font-weight-normal">
          <i class="header-icon lnr-cloud-download icon-gradient bg-happy-itmeo"></i>
          Obras Mensuales
        </div>
        <!-- <div class="btn-actions-pane-right text-capitalize actions-icon-btn">
        <div class="btn-group dropdown">
          <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-icon btn-icon-only btn btn-link">
            <i class="pe-7s-menu btn-icon-wrapper"></i>
          </button>
          <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-right rm-pointers dropdown-menu-shadow dropdown-menu-hover-link dropdown-menu">
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
          </div>
        </div>
      </div> -->
      </div>
      <div class="p-0 card-body">
        <div class="p-1 slick-slider-sm mx-auto" id="chart">
          <!-- completa dinamicamente -->
        </div>


        <h6 class="text-muted text-uppercase font-size-md opacity-5 pl-3 pr-3 pb-1 font-weight-normal">
          Obras Totales</h6>
        <ul class="list-group list-group-flush">
          <li class="p-3 bg-transparent list-group-item">
            <div class="widget-content p-0">
              <div class="widget-content-outer">
                <div class="widget-content-wrapper">
                  <div class="widget-content-left">
                    <div class="widget-heading">Promedio Obras</div>
                    <div class="widget-subheading">Promedio Mensual</div>
                  </div>
                  <div class="widget-content-right">
                    <div class="widget-numbers text-success" id="total_obras_id">
                      <!-- completa dinámicamente -->
                    </div>
                  </div>
                </div>
                <!-- <div class="widget-progress-wrapper">
                <div class="progress-bar-sm progress-bar-animated-alt progress">
                  <div class="progress-bar bg-primary" role="progressbar" aria-valuenow="43" aria-valuemin="0" aria-valuemax="100" style="width: 43%;">
                  </div>
                </div>
                <div class="progress-sub-label">
                  <div class="sub-label-left">Promedio Mes</div>
                  <div class="sub-label-right">20</div>
                </div>
              </div> -->
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <div class="col-12 col-md-6 mt-3">
    <div class="mb-3 card">
      <div class="card-header-tab card-header">
        <div class="card-header-title font-size-lg text-capitalize font-weight-normal">
          <i class="header-icon lnr-cloud-download icon-gradient bg-happy-itmeo"></i>
          Obras Acumuladas
        </div>
        <!-- <div class="btn-actions-pane-right text-capitalize actions-icon-btn">
        <div class="btn-group dropdown">
          <button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-icon btn-icon-only btn btn-link">
            <i class="pe-7s-menu btn-icon-wrapper"></i>
          </button>
          <div tabindex="-1" role="menu" aria-hidden="true" class="dropdown-menu-right rm-pointers dropdown-menu-shadow dropdown-menu-hover-link dropdown-menu">
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
          </div>
        </div>
      </div> -->
      </div>
      <div class="p-0 card-body">
        <div class="p-1 slick-slider-sm mx-auto" id="chart1">
          <!-- completa dinamicamente -->
        </div>


        <h6 class="text-muted text-uppercase font-size-md opacity-5 pl-3 pr-3 pb-1 font-weight-normal">
          Obras Totales</h6>
        <ul class="list-group list-group-flush">
          <li class="p-3 bg-transparent list-group-item">
            <div class="widget-content p-0">
              <div class="widget-content-outer">
                <div class="widget-content-wrapper">
                  <div class="widget-content-left">
                    <div class="widget-heading">Total Obras</div>
                    <div class="widget-subheading">Acumulado 2022</div>
                  </div>
                  <div class="widget-content-right">
                    <div class="widget-numbers text-success" id="total_obras_id_acumulado">
                      <!-- completa dinámicamente -->
                    </div>
                  </div>
                </div>
                <!-- <div class="widget-progress-wrapper">
                <div class="progress-bar-sm progress-bar-animated-alt progress">
                  <div class="progress-bar bg-primary" role="progressbar" aria-valuenow="43" aria-valuemin="0" aria-valuemax="100" style="width: 43%;">
                  </div>
                </div>
                <div class="progress-sub-label">
                  <div class="sub-label-left">Promedio Mes</div>
                  <div class="sub-label-right">20</div>
                </div>
              </div> -->
              </div>
            </div>
          </li>
        </ul>
      </div>
    </div>
  </div>

  <div class="col-6">
    <div class="card mb-3 widget-chart">
      <div class="widget-chart-content">
        <div class="icon-wrapper rounded">
          <div class="icon-wrapper-bg bg-warning"></div>
          <i class="lnr-laptop-phone text-warning"></i>
        </div>
        <div class="widget-numbers" id="total_obras_sin_observaciones">
                      <!-- completa dinámicamente -->          
        </div>
        <div class="widget-subheading fsize-1 pt-2 opacity-10 text-warning font-weight-bold">
          Obras Sin Observaciones</div>
        <div class="widget-description opacity-8">
          <span class="text-danger pr-1">
            <!-- <i class="fa fa-angle-down"></i> -->
            <span class="pl-1" id="porcentaje_sin_observaciones">
              <!-- completa dinamico -->
            </span>
          </span>
          
        </div>
      </div>
      <div class="widget-chart-wrapper" id="grafico_obras_sin_observaciones">
                      <!-- completa dinámicamente -->          
        
        
      </div>
    </div>
  </div>

  <div class="col-6">
    <div class="card mb-3 widget-chart">
      <div class="widget-chart-content">
        <div class="icon-wrapper rounded">
          <div class="icon-wrapper-bg bg-warning"></div>
          <i class="lnr-laptop-phone text-warning"></i>
        </div>
        <div class="widget-numbers" id="total_obras_con_observaciones">
                      <!-- completa dinámicamente -->          
        </div>
        <div class="widget-subheading fsize-1 pt-2 opacity-10 text-warning font-weight-bold">
          Obras con Observaciones</div>
        <div class="widget-description opacity-8">
          <span class="text-danger pr-1">
            <!-- <i class="fa fa-angle-down"></i> -->
            <span class="pl-1" id="porcentaje_con_observaciones">
              <!-- completa dinamico -->
            </span>
          </span>
          
        </div>
      </div>
      <div class="widget-chart-wrapper" id="grafico_obras_con_observaciones">
                      <!-- completa dinámicamente -->          
        
        
      </div>
    </div>
  </div>

  <div class="col-6">
    <div class="card mb-3 widget-chart">
      <div class="widget-chart-content">
        <div class="icon-wrapper rounded">
          <div class="icon-wrapper-bg bg-warning"></div>
          <i class="lnr-laptop-phone text-warning"></i>
        </div>
        <div class="widget-numbers" id="promedio_coordinadas">
                      <!-- completa dinámicamente -->          
        </div>
        <div class="widget-subheading fsize-1 pt-2 opacity-10 text-warning font-weight-bold">
          Promedio días Coordinadas</div>
        
      </div>      
    </div>
  </div>

  <div class="col-6">
    <div class="card mb-3 widget-chart">
      <div class="widget-chart-content">
        <div class="icon-wrapper rounded">
          <div class="icon-wrapper-bg bg-warning"></div>
          <i class="lnr-laptop-phone text-warning"></i>
        </div>
        <div class="widget-numbers" id="promedio_cerradas">
                      <!-- completa dinámicamente -->          
        </div>
        <div class="widget-subheading fsize-1 pt-2 opacity-10 text-warning font-weight-bold">
          Promedio días Cerradas</div>
        
      </div>      
    </div>
  </div>

</div>



@endif