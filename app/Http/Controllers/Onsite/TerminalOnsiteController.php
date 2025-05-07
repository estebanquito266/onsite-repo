<?php

namespace App\Http\Controllers\Onsite;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

use App\Services\Onsite\TerminalOnsiteService;

class TerminalOnsiteController extends Controller
{
	protected $terminalOnsiteService;

	public function __construct(
		TerminalOnsiteService $terminalOnsiteService
	) {
		$this->middleware('auth');
		$this->terminalOnsiteService = $terminalOnsiteService;
	}
	
	public function index()
	{
		$datos = $this->terminalOnsiteService->getDataIndex();
		return view('_onsite.terminalonsite.index', $datos);
	}

	public function create()
	{
		$datos = $this->terminalOnsiteService->getDataCreate();

		return view('_onsite.terminalonsite.create', $datos);
	}

	public function store(Request $request)
	{
		$request->validate([
			'sucursal_onsite_id' => 'required',
		]);
		if (!$this->isTerminalAllUnica($request, null)) {
			$mjeCreate = 'Ya existe un Terminal Onsite ALL para esta Sucursal Onsite';
			if ($request->ajax()) {
				return response()->json([
					"mensaje" => $mjeCreate,
				], 400);
			} else {
				return redirect()->back()->withErrors($mjeCreate)->withInput($request->all());
			}
		}

		$terminalOnsite = $this->terminalOnsiteService->store($request);

		$mjeCreate = 'TerminalOnsite: ' . $terminalOnsite->nro . ' - registro creado correctamente!';

		if ($request->ajax()) { //se agrega p/ Jquery Ajax de reparaciones
			return response()->json([
				"mensaje" => $mjeCreate,
				"idTerminal" => $terminalOnsite->nro,
				"nombreTerminal" => $terminalOnsite->nro,
				"nro" => $terminalOnsite->nro,
				"marca" => $terminalOnsite->marca,
				"modelo" => $terminalOnsite->modelo,
				"serie" => $terminalOnsite->serie,
			]);
		}

		if ($request['botonGuardar']) {
			return redirect('/terminalOnsite/' . $terminalOnsite->nro . '/edit')->with('message', $mjeCreate);
		} elseif ($request['botonGuardarSistemaOnsite']) {
			return redirect('/sistemaOnsite/' . $request['sistemas_onsite_id'] . '/edit')->with('message', $mjeCreate);
		} else {
			return redirect('/terminalOnsite')->with('message', $mjeCreate);
		}
	}

	public function edit(Request $request, $terminalOnsite)
	{
		$datos = $this->terminalOnsiteService->getDataEdit($terminalOnsite);
		// Validar que el usuario sea de la misma compañia que la terminal
		if (!in_array($datos['terminalOnsite']->company_id, Session::get('userCompaniesId'))) {
			Session::flash('message-error', 'Sin Privilegios');
			return redirect('/terminalOnsite');
		}

		if ($request->ajax()) {  //se agrega p/ Jquery Ajax de reparaciones
			return response()->json(
				$terminalOnsite->toArray()
			);
		}

		//$datos = $this->terminalOnsiteService->getDataIndex($request,$terminalOnsite);

		return view('_onsite.terminalonsite.edit', $datos);
	}
	public function show($terminalOnsite)
	{
		$datos = $this->terminalOnsiteService->getDataEdit($terminalOnsite);
		// Validar que el usuario sea de la misma compañia que la terminal
		if (!in_array($datos['terminalOnsite']->company_id, Session::get('userCompaniesId'))) {
			Session::flash('message-error', 'Sin Privilegios');
			return redirect('/terminalOnsite');	}		

		

		return view('_onsite.terminalonsite.show', $datos);
	}

	public function update(Request $request, $terminalOnsite)
	{
		$request->validate([
			'empresa_onsite_id' => 'required',
			'sucursal_onsite_id' => 'required',
		]);

		if (!$this->isTerminalAllUnica($request, $terminalOnsite)) {
			return redirect()->back()->withErrors("Ya existe un Terminal Onsite ALL para esta Sucursal Onsite")->withInput();
		}

		$terminalOnsite = $this->terminalOnsiteService->update($request, $terminalOnsite);

		$mjeUpdate = 'TerminalOnsite: ' . $terminalOnsite->nro . ' - registro modificado correctamente!';

		if ($request->ajax()) { //se agrega p/ Jquery Ajax de reparaciones
			return response()->json([
				"mensaje" => $mjeUpdate,
				"idTerminal" => $terminalOnsite->nro,
				"nombreTerminal" => $terminalOnsite->nro,
				"nro" => $terminalOnsite->nro,
				"marca" => $terminalOnsite->marca,
				"modelo" => $terminalOnsite->modelo,
				"serie" => $terminalOnsite->serie,
			]);
		}
		if ($terminalOnsite->sistemas_onsite_id != null) {
			return redirect('/sistemaOnsite/' . $terminalOnsite->sistemas_onsite_id . '/edit')->with('message', $mjeUpdate);
		} elseif ($request['botonGuardar']) {
			return redirect('/terminalOnsite/' . $terminalOnsite->nro . '/edit')->with('message', $mjeUpdate);
		} else {
			return redirect('/terminalOnsite')->with('message', $mjeUpdate);
		}
	}

	public function destroy($id)
	{
		$terminalOnsite = $this->terminalOnsiteService->findTerminal($id);
		// Validar que el usuario sea de la misma compañia que la terminal
		if (!in_array($terminalOnsite->company_id, Session::get('userCompaniesId'))) {
			Session::flash('message-error', 'Sin Privilegios');
			return redirect('/terminalOnsite');
		}

		$terminalOnsite = $this->terminalOnsiteService->destroy($id);

		$mjeDelete = 'TerminalOnsite: ' . $terminalOnsite->nro . ' - registro eliminado correctamente!';

		return redirect('/terminalOnsite')->with('message', $mjeDelete);
	}

	public function getTerminalesOnsite(Request $request, $sucursal_onsite_id)
	{
		if ($request->ajax()) {
			$terminales = $this->terminalOnsiteService->getDataTerminalesOnsite($request, $sucursal_onsite_id);
			return response()->json($terminales);
		}
	}

	public function filtrarTerminalOnsite(Request $request)
	{
		$datos = $this->terminalOnsiteService->getDatafiltrarTerminalOnsite($request);

		return view('_onsite.terminalonsite.index', $datos);
	}

	public function generarCsv($userCompanyId, $texto, $sucursal_onsite_id, $sucursal_onsite_clave, $userId)
	{
		$this->terminalOnsiteService->generarXlsx($userCompanyId, $texto, $sucursal_onsite_id, $sucursal_onsite_clave, $userId);
	}

	public function getNroTerminalOnsite(Request $request)
	{
		$nro = $this->terminalOnsiteService->getNroTerminalOnsite($request);
		return $nro;
	}

	private function isTerminalAllUnica($request, $terminalOnsiteId)
	{

		$all = $request['all_terminales_sucursal'] == 'on';
		$terminalOnsite = $this->terminalOnsiteService->getTerminalAllSucursalOnsite($request['sucursal_onsite_id'], $terminalOnsiteId);

		return !($all && $terminalOnsite);
	}
}
