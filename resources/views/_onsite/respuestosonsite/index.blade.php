@extends('layouts.baseprolist')

@section('content')


@include('_onsite.respuestosonsite.top')
<meta name="csrf-token" content="{{ csrf_token() }}" />

<div class="main-card mb-3 card">
  <div class="card-header card_inicio_repuestos">
    <h3 class="mr-3">Listado de Ordenes de repuestos</h3>
    <button type="button" data-toggle="collapse" href="#filtro" class="btn-shadow mr-3 btn btn-secondary">
      <i class="fa fa-filter"></i>
    </button>
    <!-- 
    <button type="button" data-toggle="collapse" href="#exportador" class="btn-shadow mr-3 btn btn-secondary">
      <i class="fa fa-download"></i>
    </button>
    <button type="button" data-toggle="collapse" href="#importador" class="btn-shadow mr-3 btn btn-secondary">
      <i class="fa fa-upload"></i>
    </button> -->
  </div>
  <div class="card-body">

    <div class="collapse border mb-5 pl-3 pr-3 pb-3" id="filtro">
        <form action="{{ url('filtrarPedidoRepuestos') }}" method="POST">
          {{ csrf_field() }}
            <div class="form-row mt-3">
                <div class="col-lg-4">
                  <div class="form-group">
                    <label id="estado_repuestos_id">Estado de repuestos </label>
                    <select name="estado_repuestos_id" id="estado_repuestos_id" class="form-control">
                      <option value="0">Todas</option>
                      @foreach($estadosRespuestos as $idEstado => $estado)
                        <option value="{{ $idEstado}}" {{ (isset($estadosRespuestosId) && $estadosRespuestosId == $idEstado) ? 'selected' : '' }}>{{ $estado }}</option>
                      @endforeach								
                    </select>
                  </div>
                </div>	
                      
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
        <a href="exports/listado_reparaciononsite_{{ $user_id }}.xlsx" class="btn btn-success  btn-pill mt-3">Descargar </a>
      </div>
    </div>


    <table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered ">
      <thead>
        <tr>
          <th>id</th>
          <th>User</th>
          <th>Estado</th>
          <th>Monto Dolar</th>
          <th>Monto Euro</th>
          <th>Monto Peso</th>
          <th>Action</th>
          <th>Revisar</th>

        </tr>
      </thead>
      <tbody class="small">
        @foreach($ordenesPedidoRespuestos as $orden)
        <tr>
          <td>
            <a href="{{ url('respuestosOnsite/' . $orden->id . '/edit') }}">{{ $orden->id }}</a>
          </td>
          <td>{{($orden->user_id  ) ? $orden->user->name : ''}}</td>
          <td>{{($orden->estado) ? $orden->estado->nombre : ''}}</td>
          <td>{{($orden->monto_dolar) ? '$'.number_format($orden->monto_dolar, 2, ',', '.')  : '$'.number_format(0, 2, ',', '.')}}</td>
          <td>{{($orden->monto_euro) ? '$'.number_format($orden->monto_euro, 2, ',', '.')  :'$'. number_format(0, 2, ',', '.')}}</td>
          <td>{{($orden->monto_peso) ? '$'.number_format($orden->monto_peso, 2, ',', '.')  :'$'. number_format(0, 2, ',', '.')}}</td>

          <td>
						<span class="mr-2"><a href="{{ url('detalleOrdenRespuestos/'. $orden->id) }}"><i class="fa fa-eye fa-2x"></i></a></span>
            @if($orden->estado_id == 4 || $orden->estado_id == 1 || $orden->estado_id == 5)
            <span class="mr-2"><a href="{{ url('respuestosOnsite/'. $orden->id.'/edit/') }}"><i class="fa fa-edit fa-2x"></i></a></span>
            @endif
            <span class="mr-2">
              <a  alt="Reenviar email" title="Reenviar email" href="javascript:;" class="reenviar-email" data-order_id="{{$orden->id}}" data-nombre_solicitante="{{$orden->nombre_solicitante}}" data-email_solicitante="{{$orden->email_solicitante}}" data-empresa_onsite_id="{{$orden->empresa_onsite_id}}">
                <i class="fa fa-envelope fa-2x"></i>
              </a>
            </span>            
					</td>

          <td>
          @if($user->perfil_usuario[0]->perfil->id == 1 || $user->perfil_usuario[0]->perfil->id == 62)
            <div class="btn-actions-pane-right actions-icon-btn">
              <div  class="grupo_actions btn-group dropdown"><button type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false" class="btn-icon btn-icon-only btn btn-link"><i class="pe-7s-menu btn-icon-wrapper"></i></button>
                <div  tabindex="-1" role="menu" aria-hidden="true" class="grupo_actions_inner dropdown-menu-right rm-pointers dropdown-menu-shadow dropdown-menu-hover-link dropdown-menu" style="position: absolute; will-change: transform; top: 0px; left: 0px; transform: translate3d(28px, 36px, 0px);" x-placement="bottom-end">
                  <button type="button" tabindex="0" class="dropdown-item aprobar_index_btn" data-idorden="{{$orden->id}}"><i class="dropdown-icon lnr-thumbs-up"> </i><span>Aprobar</span></button>
                  <button type="button" tabindex="0" class="dropdown-item rechazar_index_btn" data-idorden="{{$orden->id}}"><i class="dropdown-icon lnr-thumbs-down"> </i><span>Rechazar</span></button>
                  <button type="button" tabindex="0" class="dropdown-item devolver_index_btn" data-idorden="{{$orden->id}}"><i class="dropdown-icon lnr-pointer-left"> </i><span>Devolver</span></button>
                </div>
              </div>
            </div>
            @endif

          </td>
        </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr>
        <th>id</th>
          <th>User</th>
          <th>Estado</th>
          <th>Monto Dolar</th>
          <th>Monto Euro</th>
          <th>Monto Peso</th>
          <th>Action</th>
          <th>Revisar</th>
        </tr>
      </tfoot>
    </table>

    <!---- PAGINATE -->


    <!----  -->

  </div>
</div>


@endsection

@section('scripts')
<script type="text/javascript" src="{!! asset('/assets/js/_onsite/respuestos-onsite-form.js') !!}"></script>
@endsection


