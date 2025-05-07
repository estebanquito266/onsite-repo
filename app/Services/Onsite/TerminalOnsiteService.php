<?php

namespace App\Services\Onsite;

use App\Exports\GenericExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Onsite\TerminalOnsite;

use App\Services\Onsite\EmpresaOnsiteService;
use App\Services\Onsite\SucursalOnsiteService;


class TerminalOnsiteService
{
	protected $empresaOnsiteService;
	protected $sucursalOnsiteService;

	public function __construct(
		EmpresaOnsiteService $empresaOnsiteService,
		SucursalOnsiteService $sucursalOnsiteService
	) {
		$this->empresaOnsiteService = $empresaOnsiteService;
		$this->sucursalOnsiteService = $sucursalOnsiteService;
	}

	public function getDataindex()
	{
		$userCompanyId = Session::get('userCompanyIdDefault');
		$userId = Auth::user()->id;
		$this->generarCsv($userCompanyId, null, null, null, $userId);
		$datos['terminalesOnsite'] = $this->listar($userCompanyId, null, null, null, null, null, 1);
		$datos['user_id'] = $userId;

		return $datos;
	}

	public function getDataCreate()
	{
		$userCompanyId = Session::get('userCompanyIdDefault');

		$datos['empresasOnsite'] = $this->empresaOnsiteService->listadoAll($userCompanyId);
		$datos['sucursalesOnsite'] = array();

		return $datos;
	}

	public function store($request)
	{


		if ((!isset($request['company_id']) || is_null($request['company_id'])) && Session::has('userCompanyIdDefault')) {
			$userCompanyId = Session::get('userCompanyIdDefault');
		} else $userCompanyId = $request['company_id'];

		$request['company_id'] = $userCompanyId;
		$request['all_terminales_sucursal'] = ($request['all_terminales_sucursal'] == 'on') ? true : false;

		if (!$request['nro']) {
			$request['nro'] = $this->getNroTerminalOnsite($request);
		}

		$terminalOnsite = TerminalOnsite::create($request->all());

		return $terminalOnsite;
	}

	public function getDataEdit($terminalOnsite)
	{

		$userCompanyId = Session::get('userCompanyIdDefault');

		$datos['empresasOnsite'] = $this->empresaOnsiteService->listadoAll($userCompanyId);

		$terminalOnsite = $this->findTerminalNro($terminalOnsite);

		$datos['terminalOnsite'] = $terminalOnsite[0];

		if ($terminalOnsite[0]->empresa_onsite_id) {
			$datos['sucursalesOnsite'] = $this->sucursalOnsiteService->getSucursalesOnsite($terminalOnsite[0]->empresa_onsite_id);
		}


		return $datos;
	}

	public function update($request, $terminalOnsite)
	{

		$request['all_terminales_sucursal'] = ($request['all_terminales_sucursal'] == 'on') ? true : false;
		$terminalOnsite = TerminalOnsite::find($terminalOnsite);
		$terminalOnsite->update($request->all());

		return $terminalOnsite;
	}

	public function destroy($id)
	{
		$terminalOnsite = $this->findTerminal($id);

		$terminalOnsite->delete();

		return $terminalOnsite;
	}

	public function getDataTerminalesOnsite($request, $sucursal_onsite_id)
	{
		$terminales = TerminalOnsite::where('sucursal_onsite_id', $sucursal_onsite_id)->orderBy('nro', 'ASC')->get();
		return $terminales;
	}

	public function getDatafiltrarTerminalOnsite($request)
	{
		$userCompanyId = Session::get('userCompanyIdDefault');
		$userId = Auth::user()->id;

		$texto = $request['texto'];
		$sucursal_onsite_id = $request['sucursal_onsite_id'];
		$sucursal_onsite_clave = $request['sucursal_onsite_clave'];

		$datos['terminalesOnsite'] = $this->listar($userCompanyId, $texto, $sucursal_onsite_id, $sucursal_onsite_clave, null, null, null);
		$datos['texto'] = $texto;
		$datos['sucursal_onsite_id'] = $sucursal_onsite_id;
		$datos['sucursal_onsite_clave'] = $sucursal_onsite_clave;
		$datos['user_id'] = $userId;
		$this->generarCsv($userCompanyId, $texto, $sucursal_onsite_id, $sucursal_onsite_clave, $userId);

		return $datos;
	}

	public function generarCsv($userCompanyId, $texto, $sucursal_onsite_id, $sucursal_onsite_clave, $userId)
	{
		$saltear = 0;
		$tomar = 5000;
		$terminalesOnsite = true;

		$fp = fopen("exports/listado_terminalonsite_" . $userId . ".csv", 'w');

		$cabecera = array(
			'NRO',
		);

		fputcsv($fp, $cabecera, ';');

		$terminalesOnsite = $this->listar($userCompanyId, $texto, $sucursal_onsite_id, $sucursal_onsite_clave, $saltear, $tomar, null);

		while ($terminalesOnsite->count()) {

			foreach ($terminalesOnsite as $terminalOnsite) {
				$fila = array(
					'nro' => $terminalOnsite->nro,
				);
				fputcsv($fp, $fila, ';');
			}

			$saltear = $saltear + $tomar;
			$terminalesOnsite = $this->listar($userCompanyId, $texto, $sucursal_onsite_id, $sucursal_onsite_clave, $saltear, $tomar, null);
		}

		fclose($fp);
	}

	public function generarXlsx($userCompanyId, $texto, $sucursal_onsite_id, $sucursal_onsite_clave, $userId)
	{
		$saltear = 0;
		$tomar = 5000;
		$terminalesOnsite = true;

		$filename = "listado_terminalonsite_" . $userId . ".xlsx";

		$data[] = [
			'NRO',
		];

		$terminalesOnsite = $this->listar($userCompanyId, $texto, $sucursal_onsite_id, $sucursal_onsite_clave, $saltear, $tomar, null);

		while ($terminalesOnsite->count()) {

			foreach ($terminalesOnsite as $terminalOnsite) {
				$data[] = [
					'nro' => $terminalOnsite->nro,
				];
			}

			$saltear = $saltear + $tomar;
			$terminalesOnsite = $this->listar($userCompanyId, $texto, $sucursal_onsite_id, $sucursal_onsite_clave, $saltear, $tomar, null);
		}

		$excelController = new GenericExport($data, $filename);
		$excelController->export();
	}

	public function getNroTerminalOnsite($request)
	{
		$all = $request['all_terminales_sucursal'] == 'on';
		$empresaOnsite = $this->empresaOnsiteService->findEmpresaOnsite($request['empresa_onsite_id']);
		$idSucursalOnsite = $request['sucursal_onsite_id'];

		$nro = strtoupper(substr($empresaOnsite->nombre, 0, 2));
		$nro .= $idSucursalOnsite;
		if ($all) {
			$nro .= "ALL";
		} else {
			$cantTerminalesOnsite = TerminalOnsite::where('sucursal_onsite_id', $idSucursalOnsite)
				->where('all_terminales_sucursal', 0)->count();
			$cantTerminalesOnsite++;
			$cantTerminalesOnsite = str_pad($cantTerminalesOnsite, 3, "0", STR_PAD_LEFT);
			$nro .= $cantTerminalesOnsite;
		}

		return $nro;
	}

	/***********/

	public static function listar($userCompanyId, $texto, $sucursalOnsiteId, $sucursalOnsiteClave, $saltear, $tomar)
	{

		$consulta = TerminalOnsite::where('terminales_onsite.company_id', $userCompanyId);

		if (!empty($texto)) {
			$consulta = $consulta->whereRaw(" CONCAT( terminales_onsite.nro , ' ', terminales_onsite.marca , ' ', terminales_onsite.modelo  , ' ', terminales_onsite.serie  , ' ', terminales_onsite.rotulo , ' ', terminales_onsite.rotulo) like '%$texto%'");
		}

		if (!empty($sucursalOnsiteId)) {
			$consulta = $consulta->where("terminales_onsite.sucursal_onsite_id", $sucursalOnsiteId);
		}

		if (!empty($sucursalOnsiteClave)) {
			$consulta = $consulta->select("terminales_onsite.*")
				->join('sucursales_onsite', 'sucursales_onsite.id', '=', 'terminales_onsite.sucursal_onsite_id')
				->whereRaw(" CONCAT(sucursales_onsite.codigo_sucursal, ' ',  sucursales_onsite.razon_social) like '%$sucursalOnsiteClave%' ");
		}
		$consulta = $consulta->orderBy('terminales_onsite.created_at', 'desc');

		if ($tomar)
			return $consulta->skip($saltear)->take($tomar)->get();
		else
			return $consulta->paginate(100);
	}

	public static function getTerminalAllSucursalOnsite($sucursalOnsiteId, $terminalOnsiteId = null)
	{
		$consulta = TerminalOnsite::where('sucursal_onsite_id', $sucursalOnsiteId)
			->where('all_terminales_sucursal', true);

		if ($terminalOnsiteId) {
			$consulta->where('nro', '!=', $terminalOnsiteId);
		}
		return $consulta->first();
	}

	public function findTerminal($id)
	{
		$company_id = Session::get('userCompanyIdDefault');

		$terminalOnsite = TerminalOnsite::where('company_id', $company_id)
			->find($id);

		return $terminalOnsite;
	}

	public function findTerminalNro($nro)
	{
		$terminalOnsite = TerminalOnsite::where('nro', $nro)->get();
		return $terminalOnsite;
	}

	public function findTerminalTipo($tipo)
	{
		$terminalOnsite = TerminalOnsite::where('tipo_terminal', $tipo)->get();
		return $terminalOnsite;
	}

	public function getCountTerminalesBySucursal($sucursalOnsiteId)
	{
		$terminalOnsite = TerminalOnsite::where('sucursal_onsite_id', $sucursalOnsiteId)
			->where('all_terminales_sucursal', 0)
			->count();
		return $terminalOnsite;
	}

	public function getTerminalByNro($nro)
	{
		$terminalOnsite = TerminalOnsite::where('nro', $nro)->first();
		return $terminalOnsite;
	}
}
