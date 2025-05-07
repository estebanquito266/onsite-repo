<?php

namespace App\Services\Onsite;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Models\User;
use App\Models\Onsite\SucursalOnsite;
use App\Models\Onsite\TerminalOnsite;
use App\Services\Onsite\LocalidadOnsiteService;
use App\Services\Onsite\EmpresaOnsiteService;
use Log;

//agregar terminalService

class SucursalOnsiteService
{
	protected $empresaOnsiteService;
	protected $localidadOnsiteService;
	protected $userService;

	public function __construct(
		EmpresaOnsiteService $empresaOnsiteService,
		LocalidadOnsiteService $localidadOnsiteService,
		UserService $userService
	) {
		$this->empresaOnsiteService = $empresaOnsiteService;
		$this->localidadOnsiteService = $localidadOnsiteService;
		$this->userService = $userService;
	}

	public function getDataIndex()
	{
		$userCompanyId = Session::get('userCompanyIdDefault');

		//$this->generarCsv($userCompanyId, null, null, null);

		$datos['empresasOnsite'] = $this->empresaOnsiteService->listadoAll($userCompanyId);
		$datos['localidadesOnsite'] = $this->localidadOnsiteService->listadoAll($userCompanyId);
		$datos['sucursalesOnsite'] = $this->listar($userCompanyId, null, null, null, null, null);
		$datos['request'] = '';

		return $datos;
	}

	public function getDataShow(Request $request, $id)
	{
		$userCompanyId = Session::get('userCompanyIdDefault');

		$datos['sucursalOnsite'] = SucursalOnsite::find($id);

		$datos['empresasOnsite'] = $this->empresaOnsiteService->listadoAll($userCompanyId);
		$datos['localidadesOnsite'] = $this->localidadOnsiteService->listadoAll($userCompanyId);


		return $datos;
	}

	public function update(Request $request,  $id)
	{

		$sucursalOnsite = SucursalOnsite::find($id);

		$sucursalOnsite->update($request->all());

		if ($request->user_tecnico_id) {
			// Elimino los tecnicos asociados
			$sucursalOnsite->tecnicosOnsite()->detach();
			// Inserto nuevos tecnicos
			$sucursalOnsite->tecnicosOnsite()->attach($request->user_tecnico_id);
		}

		return $sucursalOnsite;
	}

	public function store($arraySucursalOnsite)
	{
		if (!isset($arraySucursalOnsite['company_id']) || is_null($arraySucursalOnsite['company_id']))
			$userCompanyId = Session::get('userCompanyIdDefault');
		else $userCompanyId = $arraySucursalOnsite['company_id'];

		$localidad_onsite_id = $arraySucursalOnsite['localidad_onsite_id'];

		$request['company_id'] = $userCompanyId;

		$sucursalOnsite = SucursalOnsite::create($arraySucursalOnsite);

		$tecnicoSucursal = $this->insertarTecnicoDefault($localidad_onsite_id, $sucursalOnsite);

		$terminalOnsiteAll = $this->insertTerminalTodas($sucursalOnsite);

		return $sucursalOnsite;
	}

	public function getDataCreate()
	{
		$userCompanyId = Session::get('userCompanyIdDefault');

		$datos['empresasOnsite'] = $this->empresaOnsiteService->listadoAll($userCompanyId);
		$datos['localidadesOnsite'] = $this->localidadOnsiteService->listadoAll($userCompanyId);
		$datos['company_id'] = $userCompanyId;

		return $datos;
	}

	public function getDataEdit(Request $request, $id)
	{
		$userCompanyId = Session::get('userCompanyIdDefault');

		$sucursalOnsite = SucursalOnsite::find($id);

		$datos['empresasOnsite'] = $this->empresaOnsiteService->listadoAll($userCompanyId);
		$datos['localidadesOnsite'] = $this->localidadOnsiteService->listadoAll($userCompanyId);

		$datos['tecnicosOnsite'] = $this->userService->listarTecnicosOnsite($userCompanyId);
		$datos['tecnicosOnsiteSeleccionados'] = ($sucursalOnsite->tecnicosOnsite && $sucursalOnsite->tecnicosOnsite()) ? $sucursalOnsite->tecnicosOnsite()->pluck('user_id')->toArray() : null;

		$datos['sucursalOnsite'] = $sucursalOnsite;

		return $datos;
	}

	public function destroy($id)
	{
		$sucursalOnsite = SucursalOnsite::find($id);

		if (!in_array($sucursalOnsite->company_id, Session::get('userCompaniesId'))) {
			Session::flash('message-error', 'Sin Privilegios');
			return redirect('/terminalOnsite');
		}

		$sucursalOnsite->tecnicosOnsite()->detach();
		$sucursalOnsite->terminalesOnsite()->delete();

		$sucursalOnsite->delete();

		return $sucursalOnsite;
	}

	public function getDataFiltrarSucursalesOnsite(Request $request)
	{
		$userCompanyId = Session::get('userCompanyIdDefault');

		$texto = $request['texto'];
		$empresa_onsite_id = $request['empresa_onsite_id'];
		$localidad_onsite_id = $request['localidad_onsite_id'];

		$datos['empresasOnsite'] = $this->empresaOnsiteService->listadoAll($userCompanyId);
		$datos['localidadesOnsite'] = $this->localidadOnsiteService->listadoAll($userCompanyId);

		$datos['sucursalesOnsite'] = $this->listar($userCompanyId, $texto, $empresa_onsite_id, $localidad_onsite_id, null, null);
		$datos['texto'] = $texto;
		$datos['empresa_onsite_id'] = $empresa_onsite_id;
		$datos['localidad_onsite_id'] = $localidad_onsite_id;

		$datos['request'] = '?texto=' . $request['texto'] . '&empresa_onsite_id=' . $request['empresa_onsite_id'] . '&localidad_onsite_id=' . $request['localidad_onsite_id'];
		//$this->generarCsv($userCompanyId, $texto, $empresa_onsite_id, $localidad_onsite_id);

		return $datos;
	}

	public function insertarTecnicoDefault($localidad_onsite_id, $sucursalOnsite)
	{
		Log::info('SucursalOnsiteService - insertarTecnicoDefault');
		$localidadOnsite = $this->localidadOnsiteService->getLocalidad($localidad_onsite_id);

		if ($localidadOnsite) {
			$sucursalOnsite->tecnicosOnsite()->attach($localidadOnsite->id_usuario_tecnico);
		}

		Log::alert('Creando atach de tecnico en sucursal');
		Log::alert($localidadOnsite);

		return $localidadOnsite;
	}

	function insertTerminalTodas($sucursal_onsite)
	{
		Log::info('SucursalOnsiteService - insertTerminalTodas');

		$terminalOnsite = null;

		if ($sucursal_onsite) {

			$nroSucursal = $sucursal_onsite->id . $sucursal_onsite->codigo_sucursal . 'ALL';
			$marcaSucursal = '';
			$modeloSucursal = '';
			$serieSucursal = '';
			$rotuloSucursal = '';
			$observacionesSucursal = 'ALL';

			$terminalOnsiteInsert = array(
				'company_id' => $sucursal_onsite->company_id,
				'nro' => $nroSucursal,
				'empresa_onsite_id' => $sucursal_onsite->empresa_onsite_id,
				'sucursal_onsite_id' => $sucursal_onsite->id,
				'marca' => $marcaSucursal,
				'modelo' => $modeloSucursal,
				'serie' => $serieSucursal,
				'rotulo' => $rotuloSucursal,
				'observaciones' => $observacionesSucursal,
				'all_terminales_sucursal' => true,


			);

			$terminalOnsite = TerminalOnsite::create($terminalOnsiteInsert);
		}

		return $terminalOnsite;
	}

	public function getSucursalesOnsite($empresa_onsite_id)
	{
		$sucursalesOnsite = SucursalOnsite::where('empresa_onsite_id', $empresa_onsite_id)->orderBy('razon_social', 'ASC')->get();
		return $sucursalesOnsite;
	}

	/*
	public function searchSucursalesOnsite($request, $empresa_onsite_id, $texto_buscar)
	{
		$sucursalesOnsite = SucursalOnsite::join('localidades_onsite', 'localidades_onsite.id', '=', 'sucursales_onsite.localidad_onsite_id')
			->select('sucursales_onsite.*', 'localidades_onsite.localidad')
			->where('sucursales_onsite.empresa_onsite_id', $empresa_onsite_id)
			->whereRaw(" CONCAT(sucursales_onsite.codigo_sucursal, ' ', sucursales_onsite.razon_social) like '%$texto_buscar%'")
			->orderBy('sucursales_onsite.razon_social', 'ASC')
			->get();

		return $sucursalesOnsite;
	}
	*/

	public function searchSucursalesOnsite($request, $empresa_onsite_id, $texto_buscar)
	{
		$sucursalesOnsite = SucursalOnsite::where('empresa_onsite_id', $empresa_onsite_id)
			->whereRaw(" CONCAT(codigo_sucursal, ' ', razon_social) like '%$texto_buscar%'")
			->orderBy('razon_social', 'ASC')
			->with('localidad_onsite')
			->get();

		return $sucursalesOnsite;
	}

	public function generarCsv($userCompanyId, $texto, $empresaOnsiteId, $localidadOnsiteId)
	{
		$saltear = 0;
		$tomar = 5000;

		$idUser = Auth::user()->id;

		$fp = fopen("exports/listado_sucursalonsite" . $idUser . ".csv", 'w');

		$cabecera = array(
			'ID',
			'COMPANY_ID',
			'CODIGO_SUCURSAL',
			'EMPRESA_ONSITE_ID',
			'NOMBREEMPRESAONSITEID',
			'RAZON_SOCIAL',
			'LOCALIDAD_ONSITE_ID',
			'NOMBRELOCALIDADONSITEID',
			'DIRECCION',
			'TELEFONO_CONTACTO',
			'NOMBRE_CONTACTO',
			'HORARIOS_ATENCION',
			'OBSERVACIONES',
		);

		fputcsv($fp, $cabecera, ';');

		$sucursalesOnsite = $this->listar($userCompanyId, $texto, $empresaOnsiteId, $localidadOnsiteId, $saltear, $tomar);

		while ($sucursalesOnsite->count()) {

			foreach ($sucursalesOnsite as $sucursalOnsite) {
				$fila = array(
					'id' => $sucursalOnsite->id,
					'company_id' => $sucursalOnsite->company_id,
					'codigo_sucursal' => $sucursalOnsite->codigo_sucursal,
					'empresa_onsite_id' => $sucursalOnsite->empresa_onsite_id,
					'nombreempresaonsiteid' => ($sucursalOnsite->empresa_onsite ? $sucursalOnsite->empresa_onsite->nombre : ''),

					'razon_social' => $sucursalOnsite->razon_social,
					'localidad_onsite_id' => $sucursalOnsite->localidad_onsite_id,
					'nombrelocalidadonsiteid' => ($sucursalOnsite->localidad_onsite ? $sucursalOnsite->localidad_onsite->localidad : ''),

					'direccion' => $sucursalOnsite->direccion,
					'telefono_contacto' => $sucursalOnsite->telefono_contacto,
					'nombre_contacto' => $sucursalOnsite->nombre_contacto,
					'horarios_atencion' => $sucursalOnsite->horarios_atencion,
					'observaciones' => $sucursalOnsite->observaciones,
				);

				fputcsv($fp, $fila, ';');
			}

			$saltear = $saltear + $tomar;
			$sucursalesOnsite = $this->listar($userCompanyId, $texto, $empresaOnsiteId, $localidadOnsiteId, $saltear, $tomar);
		}

		fclose($fp);
	}
	public static function listar($userCompanyId, $texto, $empresaOnsiteId, $localidadOnsiteId, $saltear, $tomar)
	{

		$consulta = SucursalOnsite::where('company_id', $userCompanyId);

		if (!empty($texto)) {
			//para forzar acá la clausula Where
			$consulta = $consulta->whereRaw(" CONCAT( sucursales_onsite.id , ' ', sucursales_onsite.company_id, ' ', sucursales_onsite.codigo_sucursal, ' ', sucursales_onsite.razon_social, ' ', sucursales_onsite.direccion, ' ', sucursales_onsite.telefono_contacto, ' ', sucursales_onsite.nombre_contacto, ' ', sucursales_onsite.horarios_atencion, ' ', sucursales_onsite.observaciones ) like '%$texto%'");
		}

		if (!empty($empresaOnsiteId)) {
			$consulta = $consulta->whereRaw(" sucursales_onsite.empresa_onsite_id = $empresaOnsiteId ");
		}

		if (!empty($localidadOnsiteId)) {
			$consulta = $consulta->whereRaw(" sucursales_onsite.localidad_onsite_id = $localidadOnsiteId ");
		}

		$consulta = $consulta->orderBy('sucursales_onsite.id', 'desc');

		if ($tomar) {
			return $consulta->skip($saltear)->take($tomar)->get();
		} else {
			return $consulta->paginate(100);
		}
		dd($consulta);
		return $consulta;
	}
	public function findSucursal($id)
	{


		$sucursalOnsite = SucursalOnsite::find($id);
		return $sucursalOnsite;
	}
	public function findSucursales($id)
	{
		$sucursalesOnsite = SucursalOnsite::where('id', $id)->get();
		return $sucursalesOnsite;
	}

	public function getSucursalEmpresa($empresa_onsite_id)
	{
		$sucursalesOnsite = SucursalOnsite::where('empresa_onsite_id', $empresa_onsite_id)->get();
		return $sucursalesOnsite;
	}

	public function getSucursales($company_id)
	{
		$sucursales = SucursalOnsite::where('company_id', $company_id)
			->get();

		$sucursales->each(function ($sucursal) {
			$sucursal->makeHidden('company_id');
		});

		return $sucursales;
	}

	public function getSucursalById($sucursal_id)
	{
		$sucursalOnsite = SucursalOnsite::where('id', $sucursal_id)->first();
		return $sucursalOnsite;
	}

	public function getSucursalByCodigo($sucursal_codigo)
	{
		$sucursalOnsite = SucursalOnsite::where('codigo_sucursal', $sucursal_codigo)->first();
		return $sucursalOnsite;
	}
}
