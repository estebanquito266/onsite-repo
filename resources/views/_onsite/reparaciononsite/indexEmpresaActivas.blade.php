@extends('layouts.baseprolist')

@section('content')

<div class="main-card mb-3 card">
  <div class="card-header">
    <span class="col-lg-8">
      <h3 class="mr-3">Listado de servicios en curso</h3>
    </span>
    <span class="col-lg-4 float-right">
      <span class="text-center col-md-4 small p-1 text-capitalize">
        <button type="button" data-toggle="collapse" href="#filtro" class="btn-shadow btn btn-secondary btn-sm">
          <i class="fa fa-filter"></i>
        </button>
        Filtrar
      </span>
    </span>
  </div>
  <div class="card-body">

    @include('_onsite.reparaciononsite.filtroIndexActivas')

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
          <th>Fecha Prim. Visita</th>
          <th>Fecha Vencimiento</th>
          <th>Nota</th>
        </tr>
      </thead>
      <tbody class="small">
        @foreach($reparacionesOnsite as $reparacionOnsite)
        <tr>
          <td>
          <a href="{{ url('reparacionOnsite/' . $reparacionOnsite->id) }}">{{ $reparacionOnsite->clave }}</a>

          </td>
          
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
          <td>{{$reparacionOnsite->primer_visita->fecha ?? null}}</td>
          <td>{{$reparacionOnsite->primer_visita->fecha_vencimiento ?? $reparacionOnsite->fecha_vencimiento}}</td>
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
          <th>Fecha Prim. Visita</th>
          <th>Fecha Vencimiento</th>
          <th>Nota</th>
        </tr>
      </tfoot>
    </table>

    <!---- PAGINATE -->

    @if( Request::path() =='reparacionOnsiteEmpresaActivas' )
    @include('pagination.default-limit-links', ['paginator' => $reparacionesOnsite, 'filters' => ''])
    @else
    @include('pagination.default-limit-links', ['paginator' => $reparacionesOnsite, 'filters' => '&texto='. $texto .'&id_empresa='. $id_empresa .'&id_tipo_servicio='. $id_tipo_servicio .'&id_estado='. $id_estado .'&id_tecnico='. $id_tecnico .'&fecha_vencimiento='. $fecha_vencimiento .'&estados_activo='. $estados_activo .'&ruta='. $ruta .'&liquidado_proveedor='. $liquidado_proveedor ])
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