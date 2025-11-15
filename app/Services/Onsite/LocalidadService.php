<?php

namespace App\Services\Onsite;

use App\Models\Localidad;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

use App\Models\Onsite\LocalidadOnsite;

use App\Models\Onsite\NivelOnsite;

use App\Models\Provincia;

use App\Models\User;
use Log;

class LocalidadService
{
	protected $provinciaService;
	protected $nivelesOnsiteService;
	protected $userCompanyId;

	public function __construct(
		ProvinciasService $provinciaService,
		NivelesOnsiteService $nivelesOnsiteService
	) {
		$this->provinciaService  = $provinciaService;
		$this->nivelesOnsiteService = $nivelesOnsiteService;
		$this->userCompanyId = Session::get('userCompanyIdDefault');
	}





	public function getLocalidades($idProvincia)
	{
		$company_id = Session::get('userCompanyIdDefault');
		$localidades = Localidad::where('company_id', $company_id)
			->where('provincia_speedup_id', $idProvincia)
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

	public function getLocalidadesByCompany($company_id, $per_page = 100)
	{
		return LocalidadOnsite::leftJoin('users', 'users.id', '=', 'localidades_onsite.id_usuario_tecnico')
			->leftJoin('niveles_onsite', 'niveles_onsite.id', '=', 'localidades_onsite.id_nivel')
			->leftJoin('provincias', 'provincias.id', '=', 'localidades_onsite.id_provincia')
			->leftJoin('companies', 'companies.id', '=', 'localidades_onsite.company_id')
			->where('localidades_onsite.company_id', $company_id)
			->orderBy('localidades_onsite.id_provincia', 'asc')
			->orderBy('localidades_onsite.id_nivel', 'asc')
			->orderBy('localidades_onsite.localidad', 'asc')
			->select(
				'localidades_onsite.*',
				'users.name as usuario_tecnico_nombre',
				'niveles_onsite.nombre as nivel_nombre',
				'provincias.nombre as provincia_nombre',
				'companies.nombre as company_nombre'
			)
			->paginate($per_page);
	}

	public function getLocalidadeById($localidad_id)
	{
		return LocalidadOnsite::leftJoin('users', 'users.id', '=', 'localidades_onsite.id_usuario_tecnico')
			->leftJoin('niveles_onsite', 'niveles_onsite.id', '=', 'localidades_onsite.id_nivel')
			->leftJoin('provincias', 'provincias.id', '=', 'localidades_onsite.id_provincia')
			->leftJoin('companies', 'companies.id', '=', 'localidades_onsite.company_id')
			->where('localidades_onsite.id', $localidad_id)
			->orderBy('localidades_onsite.id_provincia', 'asc')
			->orderBy('localidades_onsite.id_nivel', 'asc')
			->orderBy('localidades_onsite.localidad', 'asc')
			->select(
				'localidades_onsite.*',
				'users.name as usuario_tecnico_nombre',
				'niveles_onsite.nombre as nivel_nombre',
				'provincias.nombre as provincia_nombre',
				'companies.nombre as company_nombre'
			)
			->first();
	}

	public function updateTecnico(Request $request, $localidad_id)
	{
	
			$localidad = LocalidadOnsite::findOrFail($localidad_id);

			$localidad->id_usuario_tecnico = $request->id_usuario_tecnico;
			$localidad->save();

			return $this->getLocalidadeById($localidad_id);

		
	}




	public function getLocalidadByCodigoPostal($codigo_postal)
	{
		$localidad = LocalidadOnsite::where('codigo', $codigo_postal)->first();
		return $localidad;
	}
}
