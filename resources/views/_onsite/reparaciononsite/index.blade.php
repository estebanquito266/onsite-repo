@extends('layouts.baseprolist')

@section('content')


@include('_onsite.reparaciononsite.top')

<div class="main-card mb-3 card">
  <div class="card-header">
    <span class="col-lg-8">
      <h3 class="mr-3">Reparaciones</h3>
    </span>
    <span class="col-lg-4 float-right">
      <span class="text-center col-md-4 small p-1 text-capitalize">
        <button type="button" data-toggle="collapse" href="#filtro" class="btn-shadow btn btn-secondary btn-sm">
          <i class="fa fa-filter"></i>
        </button>
        Filtrar
      </span>

      <span class="text-center col-md-4 small p-1 text-capitalize">
        <a type="button" href="/exports/listado_reparaciononsite_{{ $user_id }}.xlsx" class="btn-shadow btn btn-secondary btn-sm">
          <i class="fa fa-download"></i>
        </a>
        Descargar
      </span>
      <span class="text-center col-md-4 small p-1 text-capitalize">
        <button type="button" data-toggle="collapse" href="#importador" class="btn-shadow btn btn-secondary btn-sm">
          <i class="fa fa-upload"></i>
        </button>
        Importar
      </span>
    </span>

  </div>

  <div class="card-body">

    @include('_onsite.reparaciononsite.filtroIndex')


    <div class="collapse border pl-3 pr-3 mb-5" id="importador">
      <div class="mt-3 mb-3">
        <form action="{{ url('importarReparacionesOnsite') }}" method="POST" class="form-inline" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="form-group mr-2">
            <a href="imports/template_reparaciones_onsite_2024.csv" class="btn btn-light btn-pill" style="display:inline;"><i class="fa fa-arrow-circle-down"></i> Template Importac. </a>
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

    <table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered ">
      <thead>
        <tr>
          <th>Clave</th>
          <th>Empresa</th>
          <th>Sucursal</th>
          <th>Terminal</th>
          <th>Tipo de Servicio</th>
          <th>Estado</th>
          <th>Técnico Asignado</th>
          <th>Fecha de Ingreso</th>
          <th>Fecha Vencimiento</th>
          <th>SLA</th>
          <th>Prioridad</th>
          <th>Nota</th>
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
          <td class="text-center">
            <button class="btn btn-link" name="consultarHistorialEstadoOnsite" data-toggle="modal" data-target="#modalHistorialEstadosOnsite" value="{{ $reparacionOnsite->id }}" type="button">{{ ($reparacionOnsite->estado_onsite) ? $reparacionOnsite->estado_onsite->nombre : '' }}</button>
          </td>
          <td>
            {{ ($reparacionOnsite->tecnicoAsignado) ? $reparacionOnsite->tecnicoAsignado->name : '' }}
          </td>
          <td>
            {{$reparacionOnsite->fecha_ingreso}}
          </td>
          <td>
            {{$reparacionOnsite->fecha_vencimiento}}
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
                <td>
                  <button class='btn btn-info btn-link my-2' name='agregarNota' data-toggle='modal' data-target='#modalAgregarNota' value='{{$reparacionOnsite->id}}'><i class="fa fa-sticky-note" aria-hidden="true" title="Agregar nota" data-toggle="tooltip"></i></button>
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
          <th>Tipo de Servicio</th>
          <th>Estado</th>
          <th>Técnico Asignado</th>
          <th>Fecha de Ingreso</th>
          <th>Fecha Vencimiento</th>
          <th>SLA</th>
          <th>Prioridad</th>
          <th>Nota</th>
        </tr>
      </tfoot>
    </table>

    <!---- PAGINATE -->

    @if( Request::path() =='reparacionOnsite' || Request::path() =='reparacionOnsitePosnet' )
    @include('pagination.default-limit-links', ['paginator' => $reparacionesOnsite, 'filters' => ''])
    @else
    @include('pagination.default-limit-links', ['paginator' => $reparacionesOnsite, 'filters' => '&texto='. $texto .'&id_empresa='. $id_empresa .'&id_tipo_servicio='. $id_tipo_servicio .'&id_estado='. $id_estado .'&id_tecnico='. $id_tecnico .'&fecha_vencimiento='. $fecha_vencimiento .'&estados_activo='. $estados_activo .'&ruta='. $ruta .'&liquidado_proveedor='. $liquidado_proveedor . '&sucursal_onsite=' . $sucursal_onsite . '&terminal_onsite=' . $terminal_onsite])
    @endif

    <!----  -->

  </div>
</div>


@endsection

@section('scripts')
<script type="text/javascript" src="{!! asset('/assets/js/_onsite/reparaciones-onsite-historial-estados.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/assets/js/_onsite/reparaciones-onsite.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/assets/js/_onsite/agregar-nota.js') !!}"></script>
@endsection

@section('modals')
@include('_onsite.historialestadoonsite.modalpro')
@include('_onsite.reparaciononsite.nota.modal-agregar')
@endsection