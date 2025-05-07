@extends('layouts.baseprolist')

@section('content')


@include('_onsite.historialestadoonsite.top')

<div class="main-card mb-3 card">
  <div class="card-header">
    <h3 class="mr-3">Listado de notificaciones</h3>
    <button type="button" data-toggle="collapse" href="#filtro" class="btn-shadow mr-3 btn btn-secondary">
      <i class="fa fa-filter"></i>
    </button>
    <button type="button" data-toggle="collapse" href="#exportador" class="btn-shadow mr-3 btn btn-secondary">
      <i class="fa fa-download"></i>
    </button>
  </div>
  <div class="card-body">

    <div class="collapse border mb-5 pl-3 pr-3 pb-3" id="filtro">


      <form action="{{ url('filtrarHistorialEstadoOnsite') }}" method='POST'>

        {{ csrf_field() }}

        <div class="form-row mt-3">
          <div class="col-lg-4">
            <div class="form-group">
              <label>Ingrese texto </label>
              <input class="form-control" placeholder="Ingrese el texto de su búsqueda " name="texto" type="text" value="{{ (isset($texto)?$texto:null) }}">
            </div>
          </div>

          <div class="col-lg-4">
            <div class='form-group'>
              <label>ReparacionOnsite</label>
              <input class="form-control" placeholder="Ingrese la clave o el id_reparacion " name="id_reparacion" type="text" value="{{ (isset($id_reparacion) ? $id_reparacion : null) }}">
            </div>
          </div>

          <div class="col-lg-4">
            <div class='form-group'>
              <label>EstadoOnsite</label>


              <select name="id_estado" id="id_estado" class="form-control multiselect-dropdown" placeholder='Seleccione estadoonsite'>
                <option value=""> -- Seleccione uno --</option>
                @foreach ($estadosOnsite as $estadoOnsite)
                <option value="{{ $estadoOnsite->id }}" {{ ((isset($id_estado) && $id_estado == $estadoOnsite->id)?'selected':'') }}>{{ $estadoOnsite->nombre }}</option>
                @endforeach
              </select>
            </div>
          </div>

          <div class="col-lg-4">
            <div class='form-group'>
              <label>Usuarios</label>


              <select name="id_usuario" id="id_usuario" class="form-control multiselect-dropdown" placeholder='Seleccione usuario'>
                <option value=""> -- Seleccione uno --</option>
                @foreach ($usuarios as $id => $usuario)
                <option value="{{ $id }}" {{ ((isset($id_usuario) && $id_usuario == $id)? 'selected' : '') }}>{{ $usuario }}</option>
                @endforeach
              </select>
            </div>
          </div>



          <input name="visibilidad" type="hidden" value="{{ $visibilidad }}">
          <input name="mostrar_check" type="hidden" value="{{ $mostrarCheck ? '1' : '0' }}">

          <div class=' col-lg-12'>
            <div class='form-group'>
              <button type="submit" class="btn btn-primary btn-block btn-pill pull-right">Filtrar</button>
            </div>
          </div>
        </div>

      </form>
    </div>

    <div class="collapse border mb-5" id="exportador">
      <div class="form-group text-center">
        <a href="exports/listado_historialestadoonsite{{ Session::get('idUser') }}.xlsx" class="btn btn-success  btn-pill mt-3">Descargar </a>
      </div>
    </div>

    <table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered ">
      <thead>
        <tr>
          @if( Session::get('perfilAdmin') || Session::get('perfilAdminOnsite'))
          <th>#</th>
          @endif
          <th>Clave / Reparación Onsite / Empresa</th>
          <th>Estado Onsite</th>
          <th>Fecha</th>
          <th>Observación</th>
          <th>Usuario</th>
          <th>Visible</th>
        </tr>
      </thead>
      <tbody class="small">
        @foreach($historialEstadosOnsite as $historialEstadoOnsite)
        <tr id="trHistorialEstadoOnsite{{$historialEstadoOnsite->id}}">
          @if( Session::get('perfilAdmin') || Session::get('perfilAdminOnsite') )
          <td>
            <a href="{{ route('historialEstadoOnsite.edit', ['historialEstadoOnsite' => $historialEstadoOnsite->id ]) }}" class=''>{{ $historialEstadoOnsite->id }}</a>
          </td>
          @endif

          <td>
            @if( Session::get('perfilAdmin') || Session::get('perfilAdminOnsite'))
            <a href="{{ route('reparacionOnsite.edit', ['reparacionOnsite' => $historialEstadoOnsite->id_reparacion ]) }}" class=''>
              {{ ($historialEstadoOnsite->reparacion_onsite) ? $historialEstadoOnsite->reparacion_onsite->clave  : '-' }} / {{ $historialEstadoOnsite->id_reparacion }} / {{ ($historialEstadoOnsite->reparacion_onsite && $historialEstadoOnsite->reparacion_onsite->empresa_onsite) ? $historialEstadoOnsite->reparacion_onsite->empresa_onsite->nombre  : '-' }}
            </a>
            @else
            {{ ($historialEstadoOnsite->reparacion_onsite) ? $historialEstadoOnsite->reparacion_onsite->clave  : '-' }} / {{ $historialEstadoOnsite->id_reparacion }} / {{ ($historialEstadoOnsite->reparacion_onsite && $historialEstadoOnsite->reparacion_onsite->empresa_onsite) ? $historialEstadoOnsite->reparacion_onsite->empresa_onsite->nombre  : '-' }}
            @endif
          </td>

          <td class="text-center">
            <button class="btn btn-link" name="consultarHistorialEstadoOnsite" data-toggle="modal" data-target="#modalHistorialEstadosOnsite" value="{{ $historialEstadoOnsite->id_reparacion }}" type="button">{{$historialEstadoOnsite->estado_onsite->nombre}}</button>
          </td>

          <td>{{$historialEstadoOnsite->fecha}}</td>
          <td>{{$historialEstadoOnsite->observacion}}</td>
          <td>
            @if($historialEstadoOnsite->usuario)
            {{$historialEstadoOnsite->usuario->name}}
            @else
            -
            @endif
          </td>
          <td>
            @if($mostrarCheck)
            <button type="button" class="btn btn-circle text-info" name="visibilidadHistorialEstadoOnsite" value="{{$historialEstadoOnsite->id}}">
              <i class="fa fa-check-circle fa-2x"></i>
            </button>
            @endif
          </td>
        </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr>
          @if( Session::get('perfilAdmin') || Session::get('perfilAdminOnsite'))
          <th>#</th>
          @endif
          <th>Reparación Onsite</th>
          <th>Estado Onsite</th>
          <th>Fecha</th>
          <th>Observación</th>
          <th>Usuario</th>
          <th>Visible</th>
        </tr>
      </tfoot>
    </table>

    <!---- PAGINATE -->

    @if( Request::path() =='historialEstadoOnsite' || Request::path() =='historialEstadoOnsiteTodos' )
    @include('pagination.default-limit-links', ['paginator' => $historialEstadosOnsite, 'filters' => ''])
    @else
    @include('pagination.default-limit-links', ['paginator' => $historialEstadosOnsite, 'filters' => '&texto='. $texto .'&id_reparacion='. $id_reparacion .'&id_estado='. $id_estado .'&id_usuario='. $id_usuario .'&visibilidad='. $visibilidad ])
    @endif

    <!----  -->

  </div>
</div>


@endsection

@section('scripts')
<script type="text/javascript" src="{!! asset('/assets/js/_onsite/reparaciones-onsite-historial-estados.js') !!}"></script>
<script src="/assets/js/_onsite/historial-estados-onsite.js"></script>
@endsection

@section('modals')
@include('_onsite.historialestadoonsite.modalpro')
@endsection