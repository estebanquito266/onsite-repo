<?php

namespace App\Services\Onsite;

use App\Exports\GenericExport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Models\Onsite\LocalidadOnsite;

use App\Models\Onsite\NivelOnsite;

use App\Models\Provincia;

use App\Models\User;
use Log;

class LocalidadOnsiteService
{
	protected $provinciaService;
	protected $nivelesOnsiteService;
	protected $userService;
	protected $userCompanyId;

	public function __construct(
		ProvinciasService $provinciaService,
		NivelesOnsiteService $nivelesOnsiteService,
		UserService $userService
	) {
		$this->provinciaService  = $provinciaService;
		$this->nivelesOnsiteService = $nivelesOnsiteService;
		$this->userService = $userService;
		$this->userCompanyId = Session::get('userCompanyIdDefault');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return \Illuminate\Http\Response
	 */

	public function getData()
	{
		$userCompanyId = Session::get('userCompanyIdDefault');

		$datos['provincias'] = $this->provinciaService->listado();
		$datos['nivelesOnsite'] = $this->nivelesOnsiteService->listado();
		$datos['tecnicosOnsite'] = $this->userService->listarTecnicosOnsite($userCompanyId);

		return $datos;
	}

	public function getDataIndex()
	{
		$datos = $this->getData();
		$datos['userId'] = Auth::user()->id;
		$datos['userCompanyId'] = Session::get('userCompanyIdDefault');
		$datos['localidadesOnsite'] = $this->listar($datos['userCompanyId'], null, null, null, null, null, null);

		$this->generarCsv($datos['userCompanyId'], null, null, null, null, $datos['userId']);

		return $datos;
	}

	public function filtrarLocalidadOnsite(Request $request)
	{
		$datos = $this->getData();

		$userCompanyId = Session::get('userCompanyIdDefault');

		$datos['texto'] = $request['texto'];
		$datos['id_provincia'] = $request['id_provincia'];
		$datos['id_nivel'] = $request['id_nivel'];
		$datos['id_tecnico'] = $request['id_tecnico'];

		$userCompaniesId = array();
		$userCompanyIdDefault = null;
		$user = Auth::user();

		foreach ($user->companies as $company) {
			$userCompaniesId[] = $company->id;
			if (!$userCompanyIdDefault) {
				$userCompanyIdDefault = $company->id;
			}
		}

		Session::put('userCompaniesId', $userCompaniesId);
		Session::put('userCompanyIdDefault', $userCompanyIdDefault);

		$datos['localidadesOnsite'] = $this->listar($userCompanyId, $datos['texto'], $datos['id_provincia'], $datos['id_nivel'], $datos['id_tecnico'], null, null);

		$datos['userId'] = $user->id;



		$this->generarCsv($userCompanyId, $datos['texto'], $datos['id_provincia'], $datos['id_nivel'], $datos['id_tecnico'], $datos['userId']);

		return $datos;
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return \Illuminate\Http\Response
	 */

	/**
	 * Store a newly created resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @return \Illuminate\Http\Response
	 */
	public function store($request)
	{

		$localidadOnsite = LocalidadOnsite::create($request->all());

		return $localidadOnsite;
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function edit($id)
	{
		$datos = $this->getData();
		$datos['localidadOnsite'] = LocalidadOnsite::find($id);

		return $datos;
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  \Illuminate\Http\Request  $request
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function update(Request $request, $id)
	{
		$localidadOnsite = LocalidadOnsite::find($id);
		$localidadOnsite->fill($request->all());
		$localidadOnsite->save();

		return $localidadOnsite;
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return \Illuminate\Http\Response
	 */
	public function destroy($id)
	{
		$localidadOnsite = LocalidadOnsite::find($id);

		$localidadOnsite->delete();

		return $localidadOnsite;
	}

	public function generarCsv($userCompanyId, $texto, $idProvincia, $idNivel, $idTecnico, $idUser)
	{
		$saltear = 0;
		$tomar = 5000;

		$fp = fopen("exports/listado_localidades_onsite_" . $idUser . ".csv", 'w');
		$cabecera = array(
			'ID',
			'ID_PROVINCIA',
			'PROVINCIA',
			'LOCALIDAD',
			'LOCALIDAD_ESTANDARD',
			'CODIGO',
			'KM',
			'ID_NIVEL',
			'NIVEL',
			'ATIENDE_DESDE',
			'ID_TECNICO',
			'TECNICO'
		);
		fputcsv($fp, $cabecera, ';');
		$localidadesOnsite = $this->listar($userCompanyId, $texto, $idProvincia, $idNivel, $idTecnico, $saltear, $tomar);

		while ($localidadesOnsite->count()) {

			foreach ($localidadesOnsite as $localidadOnsite) {
				$fila = array(
					'id' => $localidadOnsite->id,
					'id_provincia' => $localidadOnsite->id_provincia,
					'provincia' => ($localidadOnsite->provincia) ? $localidadOnsite->provincia->nombre : '',
					'localidad' => $localidadOnsite->localidad,
					'localidad_estandard' => $localidadOnsite->localidad_estandard,
					'codigo' => $localidadOnsite->codigo,
					'km' => $localidadOnsite->km,
					'id_nivel' => $localidadOnsite->id_nivel,
					'nivel' => $localidadOnsite->nivelOnsite->nombre,
					'atiende_desde' => $localidadOnsite->atiende_desde,
					'id_tecnico' => $localidadOnsite->id_usuario_tecnico,
					'tecnico' => ($localidadOnsite->usuarioTecnico) ? $localidadOnsite->usuarioTecnico->name : '',
				);

				fputcsv($fp, $fila, ';');
			}

			$saltear = $saltear + 5000;

			$localidadesOnsite = $this->listar($userCompanyId, $texto, $idProvincia, $idNivel, $idTecnico, $saltear, $tomar);
		}

		fclose($fp);
	}

	public function generarXlsx($userCompanyId, $texto, $idProvincia, $idNivel, $idTecnico, $idUser)
	{
		$saltear = 0;
		$tomar = 5000;

		$filename = "listado_localidades_onsite_" . $idUser . ".csv";
		$data[] = [
			'ID',
			'ID_PROVINCIA',
			'PROVINCIA',
			'LOCALIDAD',
			'LOCALIDAD_ESTANDARD',
			'CODIGO',
			'KM',
			'ID_NIVEL',
			'NIVEL',
			'ATIENDE_DESDE',
			'ID_TECNICO',
			'TECNICO'
		];

		$localidadesOnsite = $this->listar($userCompanyId, $texto, $idProvincia, $idNivel, $idTecnico, $saltear, $tomar);

		while ($localidadesOnsite->count()) {

			foreach ($localidadesOnsite as $localidadOnsite) {
				$data[] = [
					'id' => $localidadOnsite->id,
					'id_provincia' => $localidadOnsite->id_provincia,
					'provincia' => ($localidadOnsite->provincia) ? $localidadOnsite->provincia->nombre : '',
					'localidad' => $localidadOnsite->localidad,
					'localidad_estandard' => $localidadOnsite->localidad_estandard,
					'codigo' => $localidadOnsite->codigo,
					'km' => $localidadOnsite->km,
					'id_nivel' => $localidadOnsite->id_nivel,
					'nivel' => $localidadOnsite->nivelOnsite->nombre,
					'atiende_desde' => $localidadOnsite->atiende_desde,
					'id_tecnico' => $localidadOnsite->id_usuario_tecnico,
					'tecnico' => ($localidadOnsite->usuarioTecnico) ? $localidadOnsite->usuarioTecnico->name : '',
				];
			}

			$saltear = $saltear + 5000;

			$localidadesOnsite = $this->listar($userCompanyId, $texto, $idProvincia, $idNivel, $idTecnico, $saltear, $tomar);
		}

		$excelController = new GenericExport($data, $filename);
		$excelController->export();
	}

	public function listar($userCompanyId, $texto, $provinciaId, $nivelId, $tecnicoId, $saltear, $tomar)
	{
		$consulta = LocalidadOnsite::where('company_id', $userCompanyId);

		if (!empty($texto)) {
			$consulta = $consulta->whereRaw(" CONCAT(localidades_onsite.codigo, ' ', localidades_onsite.localidad, ' ', localidades_onsite.atiende_desde ) like '%$texto%'");
		}

		if (!empty($provinciaId)) {
			$consulta = $consulta->where("localidades_onsite.id_provincia", $provinciaId);
		}

		if (!empty($nivelId)) {
			$consulta = $consulta->where('localidades_onsite.id_nivel', $nivelId);
		}

		if (!empty($tecnicoId)) {
			$consulta = $consulta->where('localidades_onsite.id_usuario_tecnico', $tecnicoId);
		}

		$consulta = $consulta->orderBy("localidades_onsite.id_provincia", "asc")
			->orderBy("localidades_onsite.localidad", "asc");

		if ($tomar) {
			return $consulta->skip($saltear)->take($tomar)->get();
		} else {
			return $consulta->paginate(30);
		}
	}

	public function listadoAll($userCompanyId)
	{
		$localidad = LocalidadOnsite::select("id", "localidad as nombre")
			->where('company_id', $userCompanyId)
			->orderBy('localidad', 'asc')
			->pluck('nombre', 'id');
		return $localidad;
	}

	public function getLocalidad($id)
	{

		$company_id = Session::get('userCompanyIdDefault');
		$localidad = LocalidadOnsite::where('company_id', $company_id)->find($id);

		return $localidad;
	}

	public function getLocalidades($idProvincia)
	{
		$company_id = Session::get('userCompanyIdDefault');
		$localidades = LocalidadOnsite::where('company_id', $company_id)
			->where('id_provincia', $idProvincia)
			->get();

		return $localidades;
	}

	public function getAllLocalidades()
	{
		$company_id = Session::get('userCompanyIdDefault');
		$localidades = LocalidadOnsite::where('company_id', $company_id)
			->get();


		return $localidades;
	}


	public function getLocalidadByCodigoPostal($codigo_postal)
	{
		$localidad = LocalidadOnsite::where('codigo', $codigo_postal)->first();
		return $localidad;
	}


	public function getLocalidadById($id)
	{
		$localidad = LocalidadOnsite::where('id', $id)->first();
		return $localidad;
	}
}
