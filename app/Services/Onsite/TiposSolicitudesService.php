<?php

namespace App\Services\Onsite;

use App\Models\Onsite\SolicitudTipo;
use Illuminate\Support\Facades\Session;

class TiposSolicitudesService
{
    /*
    *   Retorna una coleccion de TiposSolicitudes para la compania logeada
    *   @return coleccion;
    */
    public function getTiposSolicitudesAll()
    {
        $company_id = Session::get('userCompanyIdDefault');

        $tiposSolicitudes= SolicitudTipo::where('company_id', $company_id)->get();

        return $tiposSolicitudes;

    }
    /*
    *   Retorna una coleccion de TiposSolicitudes para una company_id
    *   @param company_id
    *   @return coleccion;
    */
    public function getTiposSolicitudes($company_id)
    {
        $tiposSolicitudes = SolicitudTipo::where('company_id', $company_id)->get();

        return $tiposSolicitudes;
    }
}
