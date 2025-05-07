<?php

namespace App\Http\Controllers\Respuestos;

use App\Http\Controllers\Controller;
use App\Models\Respuestos\DetalleOrdenPedidoRespuestosOnsite;
use App\Services\Onsite\Respuestos\DetallePedidoRespuestosService;
use App\Services\Onsite\Respuestos\OrdenPedidoRespuestosService;
use Illuminate\Http\Request;
use Log;

class DetallePedidoRespuestosController extends Controller
{
    protected $detallePedidosRespuestosService;
    protected $ordenPedidoRespuestosService;

    public function __construct(
        DetallePedidoRespuestosService $detallePedidosRespuestosService,
        OrdenPedidoRespuestosService $ordenPedidoRespuestosService
    )
    {
        $this->detallePedidosRespuestosService = $detallePedidosRespuestosService;
        $this->ordenPedidoRespuestosService = $ordenPedidoRespuestosService;
    }

    public function index()
    {
    }


    public function create()
    {
    }

    public function getDetalleOrden($idOrden)
    {
        $detallesOrden = $this->detallePedidosRespuestosService->getDetalleOrden($idOrden);
        
        return view('_onsite.respuestosonsite.index_detalle', ['detallesOrden'=>$detallesOrden]);
        
    }

    public function getDetalleOrdenAjax($idOrden)
    {
        $detallesOrden = $this->detallePedidosRespuestosService->getDetalleOrden($idOrden);
        
        
        return response()->json($detallesOrden);
        
    }


    public function storeDetalleOrdenRespuestos(Request $request)
    {
        $ordenPedidoRespuestos = $request->validate([

            'company_id' => 'required',
            'orden_respuestos_id' => 'required',           
            'pieza_respuestos_id' => 'required',
            'cantidad' => 'required',
            'precio_fob' => 'required ',
            'precio_total' => 'required',
            'precio_neto'=>'required',


        ]);

        

        $detallePedido = $this->detallePedidosRespuestosService->store($request);

        $ordenPedidoRespuestos = $this->ordenPedidoRespuestosService->updateMontoTotal($request);

        return $detallePedido;
    }

    public function deleteDetallePedido($idDetallePedido, Request $request)
    {
        $detallePedido = $this->detallePedidosRespuestosService->delete($idDetallePedido);
        $ordenPedidoRespuestos = $this->ordenPedidoRespuestosService->updateMontoTotal($request);

        return $detallePedido;
    }


    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }


    public function update(Request $request,  $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }

    public function updateDetalleOrdenRespuestos($idDetalleOrden, Request $request)
    {
        
        $detalleOrden = $this->detallePedidosRespuestosService->updateDetalleOrdenRespuestos($idDetalleOrden, $request);

        return response()->json($detalleOrden);


    }

    
}
