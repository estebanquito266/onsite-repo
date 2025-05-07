@extends('layouts.baseprolist')

@section('content')


@include('_onsite.respuestosonsite.top')


<div class="card-header">
  <h3 class="mr-3">Detalle de Ordenes de repuestos</h3>

</div>

@if(isset($detallesOrden[0]))
<div class="card mt-3">
  <div class="card-body">
    <h4 class="card-title">Datos Solicitante</h4>
    <p class="card-text"><strong>Nombre:</strong> {{$detallesOrden[0]->orden_pedido->nombre_solicitante}} - <strong>Email:</strong> {{$detallesOrden[0]->orden_pedido->email_solicitante}} - <strong>Teléfono:</strong> {{$detallesOrden[0]->orden_pedido->user->telefono}}</p>

  </div>
</div>

<div class="card mt-3">

  <div class="card-body">
    <h4 class="card-title">Datos Pedido</h4>

    <p class="card-text"><strong>ID:</strong> {{$detallesOrden[0]->orden_pedido->id}} - <strong>Fecha:</strong> {{date( 'd-m-Y', strtotime($detallesOrden[0]->orden_pedido->created_at))}} - <strong>Estado:</strong> {{$detallesOrden[0]->orden_pedido->estado->nombre}}</p>
    <p class="card-text"><strong>User:</strong> {{$detallesOrden[0]->orden_pedido->user->name}} - <strong>Empresa:</strong> {{$detallesOrden[0]->orden_pedido->empresa_onsite->nombre}} </p>



  </div>
</div>
@endif

<div class="card mt-3">
  <div class="card-body">

  <h4 class="card-title">Detalle Pedido</h4>




    <table style="width: 100%;" id="example" class="table table-hover table-striped table-bordered ">
      <thead>
        <tr>
          <th>id</th>

          <th>idPieza</th>
          <th>idOrden</th>
          <th>Pieza</th>
          <th>Cantidad</th>
          <th>Precio_Fob</th>
          <th>Precio Total</th>
          <th>Precio Neto</th>
        </tr>
      </thead>
      <tbody class="small">
        @foreach($detallesOrden as $detalle)
        <tr>
          <td>
            <!-- <a href="{{ url('detalleOrdenesPedidosRespuestos/' . $detalle->id . '/edit') }}">{{ $detalle->id }}</a> -->
            {{ $detalle->id }}
          </td>

          <td>{{($detalle->pieza_respuestos_id) ? $detalle->pieza->id : ''}}</td>
          <td>{{($detalle->pieza_respuestos_id) ? $detalle->orden_respuestos_id : ''}}</td>
          <td>{{($detalle->pieza_respuestos_id) ? $detalle->pieza->part_name : ''}}</td>
          <td>{{($detalle->cantidad) ? $detalle->cantidad : ''}}</td>
          <td>{{($detalle->precio_fob) ? '$'.number_format($detalle->precio_fob, 2, ',', '.')  : ''}}</td>
          <td>{{($detalle->precio_total) ? '$'.number_format($detalle->precio_total, 2, ',', '.')  : ''}}</td>
          <td>{{($detalle->precio_neto) ? '$'.number_format($detalle->precio_neto, 2, ',', '.')  : ''}}</td>



        </tr>
        @endforeach
      </tbody>
      <tfoot>
        <tr>
          <th>id</th>
          <th>idPieza</th>
          <th>idOrden</th>
          <th>Pieza</th>
          <th>Cantidad</th>
          <th>Precio_Fob</th>
          <th>Precio Total</th>
          <th>Precio Neto</th>

        </tr>
      </tfoot>
    </table>

    <!---- PAGINATE -->


    <!----  -->

  </div>
</div>



@endsection

@section('scripts')
<script type="text/javascript" src="{!! asset('/assets/js/_onsite/visitas-onsite-historial-estados.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/assets/js/_onsite/visitas-onsite.js') !!}"></script>
<script type="text/javascript" src="{!! asset('/assets/js/_onsite/agregar-nota.js') !!}"></script>
@endsection