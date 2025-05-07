<?php

namespace App\Http\Controllers\Respuestos;

use App\Exports\PedidosExport;
use App\Http\Controllers\Controller;
use App\Services\Onsite\Respuestos\OrdenPedidoRespuestosService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Log;

use App\Exports\UsersExport;
use Maatwebsite\Excel\Facades\Excel;

class OrdenPedidoRespuestosController extends Controller
{
    protected $ordenPedidoRespuestosService;

    public function __construct(
        OrdenPedidoRespuestosService $ordenPedidoRespuestosService
    ) {
        $this->ordenPedidoRespuestosService = $ordenPedidoRespuestosService;
    }

    public function index()
    {
        $data = $this->ordenPedidoRespuestosService->getDataList();

        return view('_onsite.respuestosonsite.index', $data);
    }




    public function create()
    {
        $data = $this->ordenPedidoRespuestosService->getDataList();

        return view('_onsite.respuestosonsite.create', $data);
    }


    public function storeOrdenPedidoRespuestos(Request $request)
    {
        $ordenPedidoRespuestos = $request->validate([

            'company_id' => 'required',
            'user_id' => 'required',
            'estado_id' => 'required',
            'monto_dolar' => 'required',
            'monto_euro' => 'required',
            'monto_peso' => 'required',
            'comentarios' => 'required',
            'empresa_onsite_id' => 'required'

        ]);



        $ordenPedidoRespuestos = $this->ordenPedidoRespuestosService->store($request);

        return $ordenPedidoRespuestos;
    }

    public function store()
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $data = $this->ordenPedidoRespuestosService->getDataList();
        $data['idOrden'] = $id;


        return view('_onsite.respuestosonsite.create', $data);
    }


    public function update(Request $request,  $id)
    {
        //
    }


    public function destroy($id)
    {
        //
    }

	public function filtrarPedidoRepuestos(Request $request)
	{
		$datos = $this->ordenPedidoRespuestosService->getFiltrarPedidoRepuestos($request);
		return view('_onsite/respuestosonsite.index', $datos);
	}

    public function confirmarOrden($idOrden, Request $request)
    {
        $ordenPedido = $this->ordenPedidoRespuestosService->confirmarOrden($idOrden, $request);


        return response()->json($ordenPedido);
    }

    public function enviarEmailsRepuestos($idOrden, Request $request)
    {

        $email = $this->ordenPedidoRespuestosService->enviarEmailsRepuestos($idOrden, $request);


        return response()->json($email);
    }



    public function updateOrdenRespuestos($idOrden, Request $request)
    {
        $ordenPedido = $this->ordenPedidoRespuestosService->updateOrdenRespuestos($idOrden, $request);

        return response()->json($ordenPedido);
    }

    public function updateEstadoOrdenRespuestos($idOrden, Request $request)
    {
        $ordenPedido = $this->ordenPedidoRespuestosService->updateEstadoOrdenRespuestos($idOrden, $request);

        return response()->json($ordenPedido);
    }

    public function getUsuarioEmpresa($idEmpresa)
    {
        $usuarioEmpresa = $this->ordenPedidoRespuestosService->getUsuarioEmpresa($idEmpresa);

        return response()->json($usuarioEmpresa);
    }

    public function export()
    {
        $response = Excel::download((new PedidosExport), 'pedidos.xlsx', null, [\Maatwebsite\Excel\Excel::XLSX]);

        return $response;
    }
}
