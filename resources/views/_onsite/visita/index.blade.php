@extends('layouts.baseprolist')

@section('content')


@include('_onsite.visita.top')

<div class="main-card mb-3 card">
  <div class="card-header">
    <h3 class="mr-3">Listado de Visitas</h3>
    <button type="button" data-toggle="collapse" href="#filtro" class="btn-shadow mr-3 btn btn-secondary">
      <i class="fa fa-filter"></i>
    </button>
    <button type="button" data-toggle="collapse" href="#exportador" class="btn-shadow mr-3 btn btn-secondary">
      <i class="fa fa-download"></i>
    </button>
    <button type="button" data-toggle="collapse" href="#importador" class="btn-shadow mr-3 btn btn-secondary">
      <i class="fa fa-upload"></i>
    </button>
  </div>
  <div class="card-body">

    @include('_onsite.visita.filtroIndex')

    <div class="collapse border mb-5" id="exportador">
      <div class="form-group text-center">
        <a href="exports/listado_reparaciononsite_{{ $user_id }}.xlsx" class="btn btn-success  btn-pill mt-3">Descargar </a>
      </div>
    </div>

    <div class="collapse border pl-3 pr-3 mb-5" id="importador">
      <div class="mt-3 mb-3">
        <form action="{{ url('importarReparacionesOnsite') }}" method="POST" class="form-inline" enctype="multipart/form-data">
          {{ csrf_field() }}
          <div class="form-group mr-2">
            <a href="imports/template_reparaciones_onsite.csv" class="btn btn-light btn-pill" style="display:inline;"><i class="fa fa-arrow-circle-down"></i> Template Importac. </a>
          </div>



          <div class="form-group">
            <input type="file" name="archivo" class="form-control mr-2">
            <input type="submit" class="btn btn-warning  btn-pill" value="Importar">
          </div>
        </form>
      </div>
    </div>

    <table style="width: 100%;" class="table table-hover table-striped table-bordered ">
      <thead>
        <tr>
          <th>Clave</th>
          <th>Obra</th>



          <th>Tipo de Servicio</th>
          <th>Estado</th>

          <th>Técnico Asignado</th>

          <th>Fecha de Ingreso</th>
          <th>Fecha Vencimiento</th>

          <th>SLA</th>


          <th>Nota</th>
        </tr>
      </thead>
      <tbody class="small">
        @foreach($reparacionesOnsite as $reparacionOnsite)
        <tr>
          <td>
            <a href="{{ url('reparacionOnsite/' . $reparacionOnsite->id . '/edit') }}">{{ $reparacionOnsite->clave }}</a>
          </td>
          <td>{{($reparacionOnsite->sistema_onsite && $reparacionOnsite->sistema_onsite->obra_onsite) ? $reparacionOnsite->sistema_onsite->obra_onsite->nombre : ''}}</td>



          <!-- <td>{{ ($reparacionOnsite->tipo_servicio_onsite) ? $reparacionOnsite->tipo_servicio_onsite->nombre : '' }}</td> -->

          <td>{{ ($reparacionOnsite->id_tipo_servicio) ? $reparacionOnsite->tipo_servicio_onsite->nombre : '' }}</td>

          <td class="text-center">
            <button class="btn btn-link" name="consultarHistorialEstadoOnsite" data-toggle="modal" data-target="#modalHistorialEstadosOnsite" value="{{ $reparacionOnsite->id }}" type="button">{{ ($reparacionOnsite->estado_onsite) ? $reparacionOnsite->estado_onsite->nombre : '' }}</button>
          </td>

          <td>{{ ($reparacionOnsite->tecnicoAsignado) ? $reparacionOnsite->tecnicoAsignado->name : '' }}</td>

          <td>{{$reparacionOnsite->fecha_ingreso}}</td>
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
                  <button class='btn btn-info btn-link my-2' name='agregarNota' data-toggle='modal' data-target='#modalAgregarNota' value='{{$reparacionOnsite->id}}'><i class="fa fa-sticky-note" aria-hidden="true" title="Agregar nota" data-toggle="tooltip"></i></button>
                </td>
        </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr>
          <th>Clave</th>
          <th>Obra</th>



          <th>Tipo de Servicio</th>
          <th>Estado</th>

          <th>Técnico Asignado</th>

          <th>Fecha de Ingreso</th>
          <th>Fecha Vencimiento</th>

          <th>SLA</th>


          <th>Nota</th>
        </tr>
      </tfoot>
    </table>

    <!---- PAGINATE -->

    @if( Request::path() =='reparacionOnsite' )
    @include('pagination.default-limit-links', ['paginator' => $reparacionesOnsite, 'filters' => ''])
    @else
    @include('pagination.default-limit-links', ['paginator' => $reparacionesOnsite, 'filters' => '&texto='. $texto .'&id_empresa='. $id_empresa .'&id_tipo_servicio='. $id_tipo_servicio .'&id_estado='. $id_estado .'&id_tecnico='. $id_tecnico .'&fecha_vencimiento='. $fecha_vencimiento .'&estados_activo='. $estados_activo .'&ruta='. $ruta .'&liquidado_proveedor='. $liquidado_proveedor . '&sucursal_onsite=' . $sucursal_onsite . '&terminal_onsite=' . $terminal_onsite])
    @endif

    <!----  -->

  </div>
</div>


@endsection

@section('scripts')
<script type="text/javascript" src="{!! asset('/assets/js/_onsite/visitas-onsite-historial-estados.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/assets/js/_onsite/visitas-onsite.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/assets/js/_onsite/agregar-nota.js') !!}"></script>
@endsection

@section('modals')
@include('_onsite.historialestadoonsite.modalpro')
@include('_onsite.visita.nota.modal-agregar')
@endsection