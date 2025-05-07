<?php

namespace App\Services\Onsite;

use App\Models\Company;
use App\Models\Parametro;
use Log;
use Session;

class ParametroService
{
    public function getParametroTerminosCondiciones($parametro, $valor)
    {
        $parametro = Parametro::where('nombre', $parametro)->pluck($valor)->first();

        return $parametro;
    }

    public function findParametroPorNombre($nombreParametro)
    {
        $company_id = Company::BGH;

        if (Session::has('userCompanyIdDefault')) {
            $company_id = Session::get('userCompanyIdDefault');
        } 

        $parametro = Parametro::where('company_id', $company_id)
            ->where('nombre', $nombreParametro)->first();

        

        return $parametro;
    }
}
