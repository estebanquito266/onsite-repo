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

	public function getLocalidadByCodigoPostal($codigo_postal)
	{
		$localidad = LocalidadOnsite::where('codigo', $codigo_postal)->first();
		return $localidad;
	}
}
