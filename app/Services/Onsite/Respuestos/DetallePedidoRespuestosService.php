<?php

namespace App\Services\Onsite\Respuestos;

use App\Models\Respuestos\DetalleOrdenPedidoRespuestosOnsite;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use App\Models\Respuestos\OrdenPedidoRespuestosOnsite;
use App\Services\Onsite\Respuestos\CategoriaRespuestosService;
use App\Services\Onsite\Respuestos\ModeloRespuestosService;
use App\Services\Onsite\Respuestos\PiezaRespuestosService;
use Log;

class DetallePedidoRespuestosService
{
    protected $userCompanyId;
    protected $categoriasRespuestosService;
    protected $modelosRespuestosService;
    protected $piezasRespuestosService;

    public function __construct(
        CategoriaRespuestosService $categoriaRespuestosService,
        ModeloRespuestosService $modelosRespuestosService,
        PiezaRespuestosService $piezasRespuestosService
    ) {

        $this->userCompanyId =  session()->get('userCompanyIdDefault');
        $this->categoriasRespuestosService = $categoriaRespuestosService;
        $this->modelosRespuestosService = $modelosRespuestosService;
        $this->piezasRespuestosService = $piezasRespuestosService;
    }


    public function getDataList()
    {
    }



    public function create()
    {
    }

    public function show()
    {
    }

    public function store(Request $request)
    {
        $orden_id = $request->get('orden_respuestos_id');
        $pieza_id = $request->get('pieza_respuestos_id');

        $detallePedido = $this->piezasPorOrden($orden_id, $pieza_id);
        

        if (is_null($detallePedido)) {
            $detallePedido = DetalleOrdenPedidoRespuestosOnsite::create($request->all());
        } else {
            $detallePedido->update($request->all());
            
        }

        return $detallePedido;
    }

    public function piezasPorOrden($orden_id, $pieza_id)
    {
        $detalleOrden = DetalleOrdenPedidoRespuestosOnsite::where('company_id', $this->userCompanyId)
            ->where('orden_respuestos_id', $orden_id)
            ->where('pieza_respuestos_id', $pieza_id)
            ->first();

        return $detalleOrden;
    }

    public function delete($idDetallePedido)
    {
        $detallePedido = DetalleOrdenPedidoRespuestosOnsite::destroy($idDetallePedido);

        return $detallePedido;
    }

    public function update($request, $id)
    {
    }

    public function destroy($id)
    {
    }

    public function getDetalleOrden($idOrden)
    {
        $detalleOrden = DetalleOrdenPedidoRespuestosOnsite::/* with('categoria')
        ->with('modelo')
        ->*/with('pieza')
            ->where('orden_respuestos_id', $idOrden)
            ->where('company_id', $this->userCompanyId)
            ->get();

        
            return $detalleOrden;
    }

    public function updateDetalleOrdenRespuestos($idDetalleOrden, $request)
    {
        $detalleOrden = DetalleOrdenPedidoRespuestosOnsite::where('company_id', $this->userCompanyId)
            ->find($idDetalleOrden);

            

        $detalleOrden->cantidad = $request->get('cantidad');
        $detalleOrden->precio_total = $request->get('precio_total');
        $detalleOrden->precio_neto = $request->get('precio_neto');
        $detalleOrden->save();

        return $detalleOrden;
    }
}
