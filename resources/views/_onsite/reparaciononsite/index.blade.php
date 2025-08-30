@extends('layouts.baseprolist')

@section('content')


@include('_onsite.reparaciononsite.top')

<div class="main-card mb-3 card">


  <div class="card-header">
        <h3 class="mr-3">{{strtoupper("Reparaciones")}}</h3>

    </div>

  <div class="card-body">

  @include('_onsite.reparaciononsite.filtroIndex2')


    <div class="collapse border pl-3 pr-3 mb-5" id="importador">
      <div class="mt-3 mb-3">
        <form action="{{ url('importarReparacionesOnsite') }}" method="POST" class="form-inline" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="form-group mr-2">
            <a href="imports/template_reparaciones_onsite_2025.csv" class="btn btn-light btn-pill" style="display:inline;"><i class="fa fa-arrow-circle-down"></i> Template Importac. </a>
          </div>
          <div class="form-group">
            <input type="file" name="archivo" class="form-control mr-2">
            <input type="submit" class="btn btn-warning  btn-pill" value="Importar">
          </div>
        </form>
      </div>

      <div>
        <span>
          {!! $ultima_notificacion ?? '' !!}
        </span>
      </div>
      <div class="mt-3 mb-3">
        <label class="text-primary font-weight-bold">
          INSERT:
          <br>
          para insertar una nueva reparación desde el importador, se requieren los siguientes datos como mínimo:
          <br>
          Clave, Empresa, Sucursal, Terminal, Tipo Servicio
        </label>

        <label class="text-primary font-weight-bold">
          UPDATE:
          <br>
          campos disponibles para updatear:
          <br>
          id_empresa_onsite, sucursal_onsite_id, id_terminal, tarea, tarea_detalle, id_tipo_servicio, id_estado,
          fecha_ingreso, observacion_ubicacion, fecha_coordinada, fecha_vencimiento, fecha_cerrado, sla_status, sla_justificado,
          monto, monto_extra, liquidado_proveedor, visible_cliente, chequeado_cliente, problema_resuelto, usuario_id, nota_cliente,
          observaciones_internas
        </label>

        <label class="text-primary font-weight-bold">
          CONSIDERACIONES:
          <br>
          -se recomienda tener asignado un técnico por Sucursal
          <br>
          -se recomienda tener asignado un técnico por Localidad
        </label>
      </div>
    </div>

    <table style="width: 100%;" id="example22333" class="table table-hover table-striped table-bordered " >
      <thead>
        <tr>
          <th class="all">Clave</th>
          <th>Empresa</th>
          <th>Sucursal</th>
          <th>Terminal</th>
          <th>Servicio</th>
          <th>Estado</th>
          <th>Técnico</th>
          <th>Ingreso</th>
          <th>Vencimiento</th>
          <th>SLA</th>
          <th>Prioridad</th>
          <th class="all">Nota</th>
        </tr>
      </thead>
      <tbody class="small">
        @foreach($reparacionesOnsite as $reparacionOnsite)
        <tr>
          <td>
            <a href="{{ url('reparacionOnsite/' . $reparacionOnsite->id . '/edit') }}">{{ $reparacionOnsite->clave }}</a>
          </td>
          <td>
            {{($reparacionOnsite->empresa_onsite) ? $reparacionOnsite->empresa_onsite->nombre : ''}}
          </td>
          <td>
            {{ ($reparacionOnsite->sucursal_onsite) ? $reparacionOnsite->sucursal_onsite->razon_social : '' }}
          </td>
          <td>
            {{ ($reparacionOnsite->terminal_onsite) ? $reparacionOnsite->terminal_onsite->nro . ' - ' . $reparacionOnsite->terminal_onsite->marca . ' - ' . $reparacionOnsite->terminal_onsite->modelo . ' - ' . $reparacionOnsite->terminal_onsite->serie . ' - ' . $reparacionOnsite->terminal_onsite->rotulo : ''}}
          </td>
          <td>
            {{ ($reparacionOnsite->tipo_servicio_onsite) ? $reparacionOnsite->tipo_servicio_onsite->nombre : '' }}
          </td>
          <td class="text-left">
            <button class="btn btn-link" name="consultarHistorialEstadoOnsite" data-toggle="modal" data-target="#modalHistorialEstadosOnsite" value="{{ $reparacionOnsite->id }}" type="button">{{ ($reparacionOnsite->estado_onsite) ? $reparacionOnsite->estado_onsite->nombre : '' }}</button>
          </td>
          <td>
            {{ ($reparacionOnsite->tecnicoAsignado) ? $reparacionOnsite->tecnicoAsignado->name : '' }}
          </td>
          <td>
          {{$reparacionOnsite->fecha_ingreso?date('Y-m-d', strtotime($reparacionOnsite->fecha_ingreso)):' '}}
          </td>
          <td>
          {{$reparacionOnsite->fecha_vencimiento?date('Y-m-d', strtotime($reparacionOnsite->fecha_vencimiento)):' '}}
          </td>

          @if( $reparacionOnsite->fecha_cerrado == '0000-00-00 00:00:00' )

          @if( $sysdate <= $reparacionOnsite->fecha_vencimiento || $reparacionOnsite->sla_justificado )
            @if( round(abs( strtotime($reparacionOnsite->fecha_vencimiento) - strtotime($sysdate) ) /86400) <= 1 ) <td class="text-center ">
              <h5><span class="badge badge-warning" data-toggle="tooltip" data-placement="bottom" title="{{ round(abs( strtotime($reparacionOnsite->fecha_vencimiento) - strtotime($sysdate) ) /86400) }} día/s"> IN </span> </h5>
              </td>
              @else
              <td class="text-center ">
                <h5><span class="badge badge-success" data-toggle="tooltip" data-placement="bottom" title="{{ round(abs( strtotime($sysdate) - strtotime($reparacionOnsite->fecha_vencimiento) ) /86400) }} día/s"> IN </span> </h5>
              </td>
              @endif
              @else
              <td class="text-center ">
                <h5><span class="badge badge-danger" data-toggle="tooltip" data-placement="bottom" title="-{{ round(abs( strtotime($reparacionOnsite->fecha_vencimiento) - strtotime($sysdate) ) /86400) }} día/s"> OUT </span> </h5>
              </td>
              @endif

              @else

              @if( $reparacionOnsite->fecha_cerrado <= $reparacionOnsite->fecha_vencimiento || $reparacionOnsite->sla_justificado )
                <td class="text-center ">
                  <h5><span class="badge badge-secondary" data-toggle="tooltip" data-placement="bottom" data-html="true" title="{{ round(abs( strtotime($reparacionOnsite->fecha_cerrado) - strtotime($reparacionOnsite->fecha_vencimiento) ) /86400) }} día/s <br> {{ $reparacionOnsite->fecha_cerrado }}"> IN </span> </h5>
                  <br>
                  <label class="checkbox-inline label">
                    <input type="checkbox" value="{{ $reparacionOnsite->sla_justificado }}" id="{{ $reparacionOnsite->id }}" name="sla_justificado" {{ $reparacionOnsite->sla_justificado ? 'checked' : ''}}>
                  </label>
                </td>
                @else
                <td class="text-center ">
                  <h5><span class="badge badge-alternate" data-toggle="tooltip" data-placement="bottom" data-html="true" title="-{{ round(abs( strtotime($reparacionOnsite->fecha_vencimiento) - strtotime($reparacionOnsite->fecha_cerrado) ) /86400) }} día/s <br> {{ $reparacionOnsite->fecha_cerrado }}"> OUT </span> </h5>
                  <br>
                  <label class="checkbox-inline label">
                    <input type="checkbox" value="{{ $reparacionOnsite->sla_justificado }}" id="{{ $reparacionOnsite->id }}" name="sla_justificado" {{ $reparacionOnsite->sla_justificado ? 'checked' : ''}}>
                  </label>
                </td>
                @endif

                @endif

                <td>
                  {{$reparacionOnsite->prioridad}}
                </td>
                <td class="text-center ">
                  <button class="btn-icon btn-icon-only btn-shadow btn-outline-2x btn btn-outline-info btn-sm" style="padding: 1px 5px;" name='agregarNota' data-toggle='modal' data-target='#modalAgregarNota' value='{{$reparacionOnsite->id}}'>
                    <i class="pe-7s-note btn-icon-wrapper" style="font-size: 20px;"> </i>
                  </button>
                </td>
        </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr>
          <th>Clave</th>
          <th>Empresa</th>
          <th>Sucursal</th>
          <th>Terminal</th>
          <th>Servicio</th>
          <th>Estado</th>
          <th>Técnico</th>
          <th>Ingreso</th>
          <th>Vencimiento</th>
          <th>SLA</th>
          <th>Prioridad</th>
          <th>Nota</th>
        </tr>
      </tfoot>
    </table>

    <!---- PAGINATE -->

    @include('layouts.default-limit-links', ['paginator' => $reparacionesOnsite, 'filters' => '','frmsubmit'=>'filtrarReparacionOnsite','showmessage'=>true,'class_loader'=>'clickoverlay' ])

    <!----  -->

  </div>
</div>


@endsection


@section('css')


<!-- DataTables Responsive CSS -->
<link rel="stylesheet" href="/vendor/datatables-responsive/dataTables.responsive.css">
@endsection





@section('scripts')
<script type="text/javascript" src="{!! asset('/assets/js/_onsite/reparaciones-onsite-historial-estados.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/assets/js/_onsite/reparaciones-onsite.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/assets/js/_onsite/agregar-nota.js') !!}"></script>

<!-- DataTables JavaScript -->
<!-- DataTables JavaScript -->
<script src="/vendor/datatables/js/jquery.dataTables.min.js"></script>
<script src="/vendor/datatables-plugins/dataTables.bootstrap.min.js"></script>
<script src="/vendor/datatables-responsive/dataTables.responsive.js"></script>

<script>
  document.addEventListener("DOMContentLoaded", function(event) {

dataTableTickets = $('#example22333').DataTable({
            paging: false,
            responsive: true,
            columnDefs: [{
                "targets": [11], // Índice de la columna que no deseas que sea ordenable
                "orderable": false
            }],
            info: false,
            paging: false,
            searching: false,
           
            drawCallback: function(settings) {
                $('.dataTables_wrapper').removeClass('form-inline');
            }
        });
    

});
</script>



<style>
.select2-scollable {
    min-height: 300px !important;
    overflow-y: auto; 
} 
    .btn-menu{
        width: 150px !important;
    }

    .menu-footer{
        background-color: white;
        border-top: unset;
    }

    .modal-dialog {
    box-shadow: none !important;
}
    .my-header{
        text-transform: none !important;

    }

    table.dataTable.dtr-inline.collapsed > tbody > tr > td:first-child:before,
    table.dataTable.dtr-inline.collapsed > tbody > tr > th:first-child:before {
        background-color: #3ac47d;
    }
.dtr-details {
    font-size: 110% !important;
}
.select2-container--bootstrap4 .select2-results>.select2-results__options {
        min-height: 300px !important;
        overflow-y: auto;
    }
</style>

@endsection

@section('modals')
@include('_onsite.historialestadoonsite.modalpro')
@include('_onsite.reparaciononsite.nota.modal-agregar')
@endsection