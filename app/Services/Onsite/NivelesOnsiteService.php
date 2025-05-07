<?php

namespace App\Services\Onsite;

use App\Models\Onsite\NivelOnsite;
use Illuminate\Support\Facades\Session;

class NivelesOnsiteService
{
    public function findNivelOnsite($idNivelOnsite)
    {
        $company_id = Session::get('userCompanyIdDefault');

        $nivelOnsite = NivelOnsite::where('company_id', $company_id)->find($idNivelOnsite);

        return $nivelOnsite;
    }

    public function findNivelesOnsiteAll()
    {
        $userCompanyId = Session::get('userCompanyIdDefault');
        $nivelesOnsite = NivelOnsite::where('company_id', $userCompanyId)
            ->orderBy('nombre', 'ASC')->get();

        return $nivelesOnsite;
    }

    public static function listado(){
        $userCompanyId = Session::get('userCompanyIdDefault');
        
		return NivelOnsite::select("id", "nombre")
            ->where('company_id', $userCompanyId)
			->orderBy('nombre', 'asc')
			->pluck('nombre', 'id');
	}
}
