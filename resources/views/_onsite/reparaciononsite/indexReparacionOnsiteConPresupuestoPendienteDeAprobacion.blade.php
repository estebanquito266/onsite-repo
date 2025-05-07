@extends('layouts.baseprolist')

@section('content')

<div class="main-card mb-3 card">
  <div class="card-header">
    <h3 class="mr-3">Listado de reparaciones con presupuesto pendiente de aprobación</h3>
  </div>
  <div class="card-body">

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
          <th>Provincia</th>
          <th>Localidad</th>
          <th>Fecha Coordinación</th>
          <th>Fecha Vencimiento</th>
          <th>Nota</th>
        </tr>
      </thead>
      <tbody class="small">
        @foreach($reparacionesOnsite as $reparacionOnsite)
        <tr>
          <td> {{$reparacionOnsite->clave}} </td>
          <td>{{($reparacionOnsite->empresa_onsite) ? $reparacionOnsite->empresa_onsite->nombre : ''}}</td>
          <td>{{ ($reparacionOnsite->sucursal_onsite) ? $reparacionOnsite->sucursal_onsite->razon_social : '' }}</td>
          <td>{{ ($reparacionOnsite->terminal_onsite) ? $reparacionOnsite->terminal_onsite->nro . ' - ' . $reparacionOnsite->terminal_onsite->marca . ' - ' . $reparacionOnsite->terminal_onsite->modelo . ' - ' . $reparacionOnsite->terminal_onsite->serie . ' - ' . $reparacionOnsite->terminal_onsite->rotulo : ''}}</td>
          <td>{{ ($reparacionOnsite->tipo_servicio_onsite) ? $reparacionOnsite->tipo_servicio_onsite->nombre : '' }}</td>
          <td class="text-center">
            <button class="btn btn-link" name="consultarHistorialEstadoOnsite" data-toggle="modal" data-target="#modalHistorialEstadosOnsite" value="{{ $reparacionOnsite->id }}" type="button">{{ ($reparacionOnsite->estado_onsite) ? $reparacionOnsite->estado_onsite->nombre : '' }}</button>
          </td>
          <td>{{ ($reparacionOnsite->tecnicoAsignado) ? $reparacionOnsite->tecnicoAsignado->name : '' }}</td>
          <td>{{ ($reparacionOnsite->sucursal_onsite->localidad_onsite->provincia ) ? $reparacionOnsite->sucursal_onsite->localidad_onsite->provincia->nombre : '' }}</td>
          <td>{{ ($reparacionOnsite->sucursal_onsite->localidad_onsite ) ? $reparacionOnsite->sucursal_onsite->localidad_onsite->localidad : ''}}</td>
          <td>{{$reparacionOnsite->fecha_coordinada}}</td>
          <td>{{$reparacionOnsite->fecha_vencimiento}}</td>
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
          <th>Provincia</th>
          <th>Localidad</th>
          <th>Fecha Coordinación</th>
          <th>Fecha Vencimiento</th>
          <th>Nota</th>
        </tr>
      </tfoot>
    </table>

    <!---- PAGINATE -->

    @include('pagination.default-limit-links', ['paginator' => $reparacionesOnsite, 'filters' => ''])
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