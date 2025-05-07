<?php

namespace App\Http\Controllers\Onsite;

use App\Http\Controllers\Controller;
use App\Services\Onsite\LocalidadOnsiteService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Validation\Rule;

use App\Services\Onsite\SucursalOnsiteService;
use Illuminate\Support\Facades\Response;

class SucursalOnsiteController extends Controller
{
	protected $sucursalOnsiteService;
	protected $localidadOnsiteService;

	public function __construct(
		SucursalOnsiteService $sucursalOnsiteService,
		LocalidadOnsiteService $localidadOnsiteService
	) {
		$this->middleware('auth');
		$this->sucursalOnsiteService = $sucursalOnsiteService;
		$this->localidadOnsiteService = $localidadOnsiteService;
		//$this->middleware('permiso', ['only' => ['index', 'create', 'store', 'edit', 'update', 'destroy']] );
	}


	public function index()
	{
		$datos = $this->sucursalOnsiteService->getDataIndex();

		return view('_onsite.sucursalonsite.index', $datos);
	}

	public function show(Request $request, $id)
	{
		$datos = $this->sucursalOnsiteService->getDataShow($request, $id);

		if (!in_array($datos['sucursalOnsite']->company_id, Session::get('userCompaniesId'))) {
			Session::flash('message-error', 'Sin Privilegios');
			return redirect('/terminalOnsite');
		}

		if ($request->ajax()) {  //se agrega p/ Jquery Ajax de reparaciones
			return response()->json(
				$datos['sucursalOnsite']->toArray()
			);
		}

		return view('_onsite.sucursalonsite.show', $datos);
	}

	public function update(Request $request,  $id)
	{
		$request->validate([
			'codigo_sucursal' => 'required',
			'razon_social' => 'required',
			'empresa_onsite_id' => 'required',
			'localidad_onsite_id' => 'required',
			'telefono_contacto' => '|numeric',
		]);

		$sucursalOnsite = $this->sucursalOnsiteService->update($request, $id);

		// Valida que si se cambia el código sucursal no corresponda a uno dublicado
		// Ingnorando el código actual del registro
		$request->validate([
			'codigo_sucursal' => [
				Rule::unique('sucursales_onsite', 'codigo_sucursal')->ignore($sucursalOnsite)
			],
		]);

		$mjeUpdate = 'SucursalOnsite: ' . $sucursalOnsite->id . ' - registro modificado correctamente!';


		if ($request->ajax()) { //se agrega p/ Jquery Ajax de reparaciones
			return response()->json([
				"mensaje" => $mjeUpdate,
				"id" => $sucursalOnsite->id,
				"razon_social" => $sucursalOnsite->razon_social,
				"codigo_sucursal" => $sucursalOnsite->codigo_sucursal,
			]);
		}

		if ($request['botonGuardar'])
			return redirect('/sucursalesOnsite/' . $sucursalOnsite->id . '/edit')->with('message', $mjeUpdate);
		else
			return redirect('/sucursalesOnsite')->with('message', $mjeUpdate);
	}

	public function store(Request $request)
	{
		// Valida que no se inserte un código de sucursal duplicado

		$request->validate([
			'codigo_sucursal' => 'required',
			'codigo_sucursal' => [
				Rule::unique('sucursales_onsite')
			],
			'razon_social' => 'required',
			'empresa_onsite_id' => 'required',
			'localidad_onsite_id' => 'required',
			'telefono_contacto' => '|numeric',
		]);

		$sucursalOnsite = $this->sucursalOnsiteService->store($request->all());


		$mjeCreate = 'SucursalOnsite: ' . $sucursalOnsite->id . ' - registro creado correctamente!';

		if ($request->ajax()) { //se agrega p/ Jquery Ajax de reparaciones
			return response()->json([
				"mensaje" => $mjeCreate,
				"id" => $sucursalOnsite->id,
				"razon_social" => $sucursalOnsite->razon_social,
				"codigo_sucursal" => $sucursalOnsite->codigo_sucursal,
			]);
		}

		if ($request['botonGuardar'])
			return redirect('/sucursalesOnsite/' . $sucursalOnsite->id . '/edit')->with('message', $mjeCreate);
		else
			return redirect('/sucursalesOnsite')->with('message', $mjeCreate);
	}

	public function create()
	{
		$datos = $this->sucursalOnsiteService->getDataCreate();

		return view('_onsite.sucursalonsite.create', $datos);
	}

	public function edit(Request $request, $id)
	{
		$datos = $this->sucursalOnsiteService->getDataEdit($request, $id);
		if (!in_array($datos['sucursalOnsite']->company_id, Session::get('userCompaniesId'))) {
			Session::flash('message-error', 'Sin Privilegios');
			return redirect('/terminalOnsite');
		}

		if ($request->ajax()) {  //se agrega p/ Jquery Ajax de reparaciones
			return response()->json(
				$datos['sucursalOnsite']->toArray()
			);
		}

		return view('_onsite.sucursalonsite.edit', $datos);
	}

	public function destroy($id)
	{
		$sucursalOnsite = $this->sucursalOnsiteService->destroy($id);

		$mjeDelete = 'SucursalOnsite: ' . $id . ' - registro eliminado correctamente!';

		return redirect('/sucursalesOnsite')->with('message', $mjeDelete);
	}

	public function filtrarSucursalesOnsite(Request $request)
	{
		$datos = $this->sucursalOnsiteService->getDataFiltrarSucursalesOnsite($request);

		return view('_onsite.sucursalonsite.index', $datos);
	}

	public function descargarSucursalesOnsite(Request $request)
	{
		$userCompanyId = Session::get('userCompaniesId');
		$texto = $request['texto'];
		$empresa_onsite_id = $request['empresa_onsite_id'];
		$localidad_onsite_id = $request['localidad_onsite_id'];

		$this->sucursalOnsiteService->generarCsv($userCompanyId, $texto, $empresa_onsite_id, $localidad_onsite_id);

		$file = 'exports/listado_sucursalonsite'.Session::get('idUser').'.csv';
		$filename = 'listado_sucursalonsite'.Session::get('idUser').'.csv';
		$headers = [
			'Content-Type' => 'text/csv',
		 ];
		 
		return Response::download($file, $filename, $headers);
	}

	private function insertarTecnicoDefault($sucursalOnsite)
	{
		$localidadOnsite = $this->sucursalOnsiteService->insertarTecnicoDefault($sucursalOnsite->localidad_onsite_id, $sucursalOnsite->id);
		if ($localidadOnsite) {
			$sucursalOnsite->tecnicosOnsite()->attach($localidadOnsite->id_usuario_tecnico);
		}
	}

	function insertTerminalTodas($sucursal_onsite_id = null)
	{
		$terminalOnsite = $this->sucursalOnsiteService->insertTerminalTodas($sucursal_onsite_id);

		return $terminalOnsite;
	}

	public function getSucursalesOnsite(Request $request, $empresa_onsite_id)
	{

		if ($request->ajax()) {
			$sucursalesOnsite = $this->sucursalOnsiteService->getSucursalesOnsite($empresa_onsite_id);
			return response()->json($sucursalesOnsite);
		}
	}

	public function searchSucursalesOnsite(Request $request, $empresa_onsite_id = null, $texto_buscar = null)
	{
		if ($request->ajax()) {
			$sucursalesOnsite = $this->sucursalOnsiteService->searchSucursalesOnsite($request, $empresa_onsite_id, $texto_buscar);
			return response()->json($sucursalesOnsite);
		}
	}

	public function generarCsv($userCompanyId, $texto, $empresaOnsiteId, $localidadOnsiteId)
	{
		$this->sucursalOnsiteService->generarCsv($userCompanyId, $texto, $empresaOnsiteId, $localidadOnsiteId);
	}
}
