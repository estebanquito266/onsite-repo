<?php

namespace App\Services\Onsite;

use App\Models\Provincia;
use Illuminate\Support\Facades\Session;

class ProvinciasService
{

    public function findProvinciaOnsite($idProvincia)
    {
        $company_id = Session::get('userCompanyIdDefault');

        $provinciaOnsite = Provincia::where('company_id', $company_id)->find($idProvincia);

        return $provinciaOnsite;
    }


    public function findProvinciasAll()
    {
        /*
        $userCompanyId = Session::get('userCompanyIdDefault');
        
        $provincias = Provincia::where('company_id', $userCompanyId)
            ->orderBy('nombre', 'ASC')->get();
            */
        $provincias = Provincia::orderBy('nombre', 'ASC')->get();

        return $provincias;
    }

    public function listado()
    {
        $userCompanyId = Session::get('userCompanyIdDefault');

        return Provincia::select('id', 'nombre')
            ->where('company_id', $userCompanyId)
            ->orderBy('nombre', 'asc')
            ->get();
    }
}
