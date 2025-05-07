<?php

namespace App\Http\Controllers\Onsite;

use App\Http\Controllers\Controller;
use App\Models\Onsite\EmpresaInstaladoraEmpresaOnsite;
use App\Services\Onsite\EmpresaOnsiteService;
use Illuminate\Http\Request;
use Session;

class EmpresaOnsiteController extends Controller
{
	protected $EmpresaOnsiteService;

	public function __construct(EmpresaOnsiteService $EmpresaOnsiteService)
	{				
		$this->EmpresaOnsiteService = $EmpresaOnsiteService;		
	}

    public function getEmpresaOnsite($empresaOnsiteId) {
		$empresaOnsite = $this->EmpresaOnsiteService->findEmpresaOnsite($empresaOnsiteId);
		return response()->json($empresaOnsite);
	}

	public function getEmpresasOnsitePorInstaladora() {
		$empresaOnsite = $this->EmpresaOnsiteService->getEmpresaOnsitePorInstaladora();
		return response()->json($empresaOnsite);
	}

	public function getEmpresasOnsitePorInstaladoraId($idEmpresaInstaladora) {
		$empresaOnsite = $this->EmpresaOnsiteService->getEmpresaOnsitePorInstaladoraId($idEmpresaInstaladora);
		return response()->json($empresaOnsite);
	}

	

	public function storeEmpresaOnsite(Request $request) {
		$empresaOnsite = $this->EmpresaOnsiteService->store($request->all());

		/* CREA EMPRESA_INSTLADORA_EMPRESA_ONSITE (PIVOT) */

        EmpresaInstaladoraEmpresaOnsite::create([
            'company_id' =>     Session::get('userCompanyIdDefault'),
            'empresa_instaladora_id' => $request['empresa_instaladora_id'],
            'empresa_onsite_id' => $empresaOnsite->id
        ]);

        /* ******** */

		return response()->json($empresaOnsite);
	}


	

	
}
